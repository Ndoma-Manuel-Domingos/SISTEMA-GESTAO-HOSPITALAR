<?php

namespace App\Services;

use App\Models\ContaHospitalar;
use App\Models\ContaHospitalarItem;
use App\Models\ContaHospitalarMovimento;
use App\Models\Produto;
use App\Models\SeguradoraPlanoCobertura;
use App\Models\SeguradoraPlanoBeneficiador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContaHospitalarService
{

    public function create(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $conta = ContaHospitalar::where('atendimento_id', $request->atendimento_id)
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        if ($conta) {
            return $conta;
        }

        $conta = ContaHospitalar::create([
            'paciente_id'     => $request->paciente_id,
            'atendimento_id'  => $request->atendimento_id,
            'numero'          => $this->gerarNumeroConta(),
            'status'          => 'ABERTA',
            'subtotal'        => 0,
            'desconto'        => 0,
            'acrescimo'       => 0,
            'total'           => 0,
            'valor_paciente' => 0,
            'valor_seguradora' => 0,
            'valor_pago_paciente' => 0,
            'valor_pago_seguradora' => 0,
            'saldo_paciente' => 0,
            'saldo_seguradora' => 0,
            'valor_pago'      => 0,
            'saldo'           => 0,
            'observacao'      => $request->observacao,
            "user_id" => Auth::id(),
            "entidade_id" => $entidade->empresa->id,
        ]);

        ContaHospitalarMovimento::create([
            'conta_hospitalar_id' => $conta->id,
            'tipo' => 'CRIACAO',
            'descricao' => 'Conta Hospitalar criada.',
            "user_id" => Auth::id(),
            "entidade_id" => $entidade->empresa->id,
        ]);

        return $conta;
    }

    private function gerarNumeroConta()
    {
        // CH-2026-000001

        $ultimo = ContaHospitalar::max('id') + 1;

        return 'CH-' . date('Y') . '-' . str_pad($ultimo, 6, '0', STR_PAD_LEFT);
    }


    public function adicionarItem(Request $request, string $id)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::id());

        $conta = ContaHospitalar::findOrFail($id);

        if (!in_array($conta->status, ['ABERTA', 'EM_ANDAMENTO'])) {
            throw new \Exception('Conta já está fechada .');
        }

        $quantidade = (int) $request->quantidade;

        if ($quantidade <= 0) {
            throw new \Exception('Quantidade inválida.');
        }

        $subtotal = ($request->quantidade * $request->preco_unitario);

        $servico = Produto::findOrFail($request->produto_id);

        $beneficiario = SeguradoraPlanoBeneficiador::with(['plano'])->where('beneficiario_id', $conta->paciente_id)
            // ->whereDate('data_fim', '>=', date("Y-m-d"))
            ->where('status', 'ACTIVO')
            ->first();

        $plano = $beneficiario?->plano;

        $percentual = 0;
        $valorSeguradora = 0;
        $valorPaciente = $subtotal;
        $cobertura = null;

        if ($plano) {

            $cobertura = SeguradoraPlanoCobertura::where('plano_id', $plano->id)
                ->where('servico_id', $servico->id)
                ->where('status', 1)
                ->first();

            if ($cobertura) {

                $percentual = $cobertura->percentual;

                $valorSeguradora = ($subtotal * $percentual) / 100;
                $valorPaciente = $subtotal - $valorSeguradora;

                /* COPAGAMENTO */
                if ($cobertura->copagamento > 0) {
                    $valorPaciente += $cobertura->copagamento;
                    $valorSeguradora -= $cobertura->copagamento;
                }

                /* LIMITE */
                if ($cobertura->limite && $valorSeguradora > $cobertura->limite) {
                    $valorSeguradora = $cobertura->limite;
                    $valorPaciente = $subtotal - $valorSeguradora;
                }
            }
        }

        $item = ContaHospitalarItem::create([
            'conta_hospitalar_id' => $conta->id,
            'origem_id' => $servico->id,
            'quantidade' => $request->quantidade,
            'preco_unitario' => $request->preco_unitario,
            'desconto' => 0,

            'percentual_cobertura' => $percentual,
            'valor_seguradora' => $valorSeguradora,
            'valor_paciente' => $valorPaciente,
            'beneficiario_id' => $beneficiario?->id,
            'cobertura_id' => $cobertura?->id,

            'subtotal' => $subtotal,
            "user_id" => Auth::id(),
            "entidade_id" => $entidade->empresa->id,
        ]);

        ContaHospitalarMovimento::create([
            'conta_hospitalar_id' => $conta->id,
            'tipo' => 'ADICAO_ITEM',
            'descricao' => 'Item ' . $item->descricao . ' adicionado.',
            "user_id" => Auth::id(),
            "entidade_id" => $entidade->empresa->id,
        ]);

        $this->recalcularTotais($conta);

        return $conta;
    }


    public function removerItem(string $id)
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $item = ContaHospitalarItem::findOrFail($id);

        $conta = $item->conta;

        if ($conta->status == 'PAGA') {
            throw new \Exception('Conta já paga.');
        }

        ContaHospitalarMovimento::create([
            'conta_hospitalar_id' => $conta->id,
            'tipo' => 'REMOCAO_ITEM',
            'descricao' => 'Item ' . $item->descricao . ' removido.',
            "user_id" => Auth::id(),
            "entidade_id" => $entidade->empresa->id,
        ]);

        $item->delete();

        $this->recalcularTotais($conta);

        return $conta;
    }


    public function recalcularTotais(ContaHospitalar $conta)
    {

        $beneficiario = SeguradoraPlanoBeneficiador::with(['plano'])->where('beneficiario_id', $conta->paciente_id)
            // ->whereDate('data_fim', '>=', date("Y-m-d"))
            ->where('status', 'ACTIVO')
            ->first();

        $plano = $beneficiario?->plano;

        // Totais dos itens
        $subtotal = $conta->itens()->sum('subtotal');

        $valorPaciente = $conta->itens()->sum('valor_paciente');
        $valorSeguradora = $conta->itens()->sum('valor_seguradora');

        $total = $subtotal - ($conta->desconto ?? 0) + ($conta->acrescimo ?? 0);

        // Pagamentos separados
        $valorPagoPaciente = $conta->pagamentos()
            ->where('tipo', 'PACIENTE')
            ->sum('valor');

        $valorPagoSeguradora = $conta->pagamentos()
            ->where('tipo', 'SEGURADORA')
            ->sum('valor');

        // Saldos separados
        $saldoPaciente = max(0, $valorPaciente - $valorPagoPaciente);

        $saldoSeguradora = max(0, $valorSeguradora - $valorPagoSeguradora);

        // Totais gerais
        $valorPago = $valorPagoPaciente + $valorPagoSeguradora;

        $saldo = $saldoPaciente + $saldoSeguradora;

        $conta->update([
            'plano_id' => $plano ? $plano->id : NULL,
            'subtotal' => $subtotal,
            'total' => $total,
            'valor_paciente' => $valorPaciente,
            'valor_seguradora' => $valorSeguradora,
            'valor_pago_paciente' => $valorPagoPaciente,
            'valor_pago_seguradora' => $valorPagoSeguradora,
            'saldo_paciente' => $saldoPaciente,
            'saldo_seguradora' => $saldoSeguradora,
            'valor_pago' => $valorPago,
            'saldo' => $saldo,
        ]);
    }

    public function registrarMovimento($contaId, $tipo, $descricao)
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        ContaHospitalarMovimento::create([
            'conta_hospitalar_id' => $contaId,
            'tipo'     => $tipo,
            'descricao' => $descricao,
            "user_id" => Auth::id(),
            "entidade_id" => $entidade->empresa->id,
        ]);
    }

    public function fecharConta(string $id)
    {
        $conta = ContaHospitalar::with(['itens', 'pagamentos'])->findOrFail($id);

        if ($conta->status == 'CANCELADA') {
            throw new \Exception('Conta cancelada não pode ser fechada.');
        }

        if ($conta->status == 'PAGA') {
            throw new \Exception('Conta já está paga.');
        }

        if ($conta->itens->count() == 0) {
            throw new \Exception('Não é possível fechar uma conta sem itens.');
        }

        $conta->update([
            'status' => 'FECHADA'
        ]);

        $this->registrarMovimento(
            $conta->id,
            'FECHAMENTO',
            'Conta fechada para faturação final.'
        );

        return $conta;
    }

    public function reabrirConta(string $id)
    {

        $conta = ContaHospitalar::with('pagamentos')->findOrFail($id);

        if ($conta->status == 'CANCELADA') {
            throw new \Exception('Conta cancelada não pode ser reaberta.');
        }

        if ($conta->pagamentos->count() > 0) {
            throw new \Exception('Não é possível reabrir uma conta com pagamentos.');
        }

        if ($conta->status == 'ABERTA') {
            throw new \Exception('Conta já está aberta.');
        }

        $conta->update([
            'status' => 'ABERTA'
        ]);

        $this->registrarMovimento(
            $conta->id,
            'ALTERACAO',
            'Conta reaberta para edição.'
        );

        return $conta;
    }

    public function cancelarConta(string $id)
    {
        $conta = ContaHospitalar::with('pagamentos')->findOrFail($id);

        if ($conta->status == 'PAGA') {
            throw new \Exception('Conta paga não pode ser cancelada.');
        }

        if ($conta->status == 'CANCELADA') {
            throw new \Exception('Conta já está cancelada.');
        }

        if ($conta->pagamentos()->count() > 0) {
            throw new \Exception('Não é possível cancelar conta com pagamentos registados.');
        }

        $conta->update([
            'status' => 'CANCELADA'
        ]);

        $this->registrarMovimento(
            $conta->id,
            'CANCELAMENTO',
            'Conta cancelada pelo utilizador.'
        );

        return $conta;
    }
}

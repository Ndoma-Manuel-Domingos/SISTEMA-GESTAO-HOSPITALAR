<?php

namespace App\Http\Controllers\pdf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PDF;

class FacturasFacturacaoController extends Controller
{
    use TraitHelpers;
    // facturas geral - em atrazo ou aberto para este mes
    // parte dos clientes
    
    public function pdfFacturaFacturacao(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $query = Venda::when($request->factura, function($query, $value) {
            $query->where('factura_next', 'like' ,"%{$value}%");
        })
        ->when($request->data_inicio, function($query, $value){
            return $query->whereDate('created_at', '>=', Carbon::parse($value));
        })
        ->when($request->data_final, function($query, $value){
            return $query->whereDate('created_at', '<=',Carbon::parse($value));
        })
        ->when($request->cliente_id, function ($query, $value) {
            $query->where('cliente_id', $value);
        })->with(['cliente'])->where('entidade_id', '=', $entidade->empresa->id);
        
        if($request->relatorio == "contas_receber_mes"){
            $query->whereMonth('data_vencimento', now()->month)
                ->whereYear('data_vencimento', now()->year)
                ->whereIn('factura', ['FT'])
                ->whereIn('factura_divida', ['Y']);
        }else if($request->relatorio == "contas_receber_atraso"){
            $query->where('data_vencimento', '<', now()->startOfMonth())
                ->whereIn('factura', ['FT'])
                ->whereIn('factura_divida', ['Y']);
        }else {
            $query->whereIn('factura', ['FT', 'FG', 'FR']);
        }
        
        $facturas = $query->orderby('created_at', 'desc')->get();
   
        if($request->relatorio == "contas_receber_mes") {
            $titulo = __('messages.contas_receber_aberto_mes');
        }else if($request->relatorio == "contas_receber_atraso"){
            $titulo = __('messages.contas_receber_atraso');
        }else {
            $titulo = __('messages.listagem');
        }

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            'titulo' => $titulo,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'descricao' => env('APP_NAME'),
            "facturas" => $facturas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.factura-facturacao', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
    
    // facturas em atrazo ou abertos neste mes
    // parte dos fornecedores
    public function pdfFacturaFacturacaoFornecedor(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $query = FacturaEncomendaFornecedor::where('entidade_id', $entidade->empresa->id)
            ->when($request->fornecedor_id, function ($query, $value) {
                $query->where('fornecedor_id', $value);
            })
            ->when($request->filled('status_factura'), function ($query) use ($request) {
                // Converte o parâmetro para booleano
                $status = filter_var($request->input('status_factura'), FILTER_VALIDATE_BOOLEAN);
                $query->where('status', $status);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_factura', ">=", $value);
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_factura', "=<", $value);
            })
        ->with(['fornecedor', 'user', 'encomenda']);
     
        // contas a pagar dos meses passados
        if($request->relatorio == "contas_pagar_atraso") {
            $query->where('data_vencimento', '<', now()->startOfMonth());
        }
        
        // Contas a pagar deste meses
        if($request->relatorio == "contas_pagar_mes") {
            $query->whereMonth('data_vencimento', now()->month)
                ->whereYear('data_vencimento', now()->year);
        }
            
        $facturas= $query->orderBy('created_at', 'asc')->get();
   
        if($request->relatorio == "contas_pagar_mes") {
            $titulo = __('messages.contas_pagar_aberto_mes');
        }else if($request->relatorio == "contas_pagar_atraso"){
            $titulo = __('messages.contas_pagar_atraso');
        }else {
            $titulo = __('messages.listagem');
        }

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            'titulo' => $titulo,
            'descricao' => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "facturas" => $facturas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.facturacao-fornecedores', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
    
    
    
}

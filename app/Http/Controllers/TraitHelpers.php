<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Conta;
use App\Models\Dispesa;
use App\Models\Entidade;
use App\Models\OperacaoFinanceiro;
use App\Models\Movimento;
use App\Models\Exercicio;
use App\Models\Loja;
use App\Models\Periodo;
use App\Models\Receita;
use App\Models\Subconta;
use App\Models\User;
use App\Models\UserLoja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpseclib\Crypt\RSA;

trait TraitHelpers
{

    public function LOJA_ACTIVA_USER()
    {
        $userLoja = UserLoja::with(['entidade'])->where('status', 1)->where('usuario_id', Auth::user()->id)->first();
        
        $loja = null;
        
        if($userLoja) {
            $loja = Loja::findOrFail($userLoja->loja_id);
        }
        
        return $loja;
    }

    public function gerarNumeroAleatorio()
    {
        return rand(10000, 99999);
    }
    
    function gerarLetrasAleatorias($tamanho = 4) {
        $letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle(str_repeat($letras, $tamanho)), 0, $tamanho);
    }

    // ANO LECTIVO ACTIVO 
    public function exercicio()
    {
        $user = User::findOrFail(Auth::user()->id);
        $entidade = Entidade::findOrFail($user->entidade_id);

        $exercicio = Exercicio::where([
            ['entidade_id', '=', $entidade->id],
            ['status', '=', 'activo'],
        ])->first();

        if (!$exercicio) {
            return redirect()->route('dashboard-recurso-humanos')->with("warning", "Precisas activar um exercício para poder operar com o sistema!");
        }
        return $exercicio->id;
    }
    
    public function periodo()
    {
        $user = User::findOrFail(Auth::user()->id);
        $entidade = Entidade::findOrFail($user->entidade_id);

        $periodo = Periodo::where('entidade_id', '=', $entidade->id)
        ->where('exercicio_id', $this->exercicio())
        ->where('mes_processamento', date('m'))
        ->first();
        
        if (!$periodo) {
            return redirect()->route('dashboard-recurso-humanos')->with("warning", "Precisas activar um exercício para poder operar com o sistema!");
        }
        return $periodo->id;
    }
    
    public function dispesa_padrao()
    {
        $user = User::findOrFail(Auth::user()->id);
        $entidade = Entidade::findOrFail($user->entidade_id);

        $dispesa = Dispesa::where('entidade_id', '=', $entidade->id)->where('type', 'D')->where('nome', 'Outros custos e perdas operacionais')->first();
        
        if (!$dispesa) {
            return redirect()->route('dashboard')->with("warning", "Precisas cadastrar uma dispesa padrão!");
        }
        return $dispesa->id;
    }
    
    function gerarEmailCliente($nomeCompleto)
    {
    
        $numero = rand(100, 999); // Número aleatório entre 100 e 999
    
        // Limpa espaços extras e divide o nome em partes
        $partes = array_filter(explode(' ', trim($nomeCompleto)));
    
        if (count($partes) < 2) {
            
            $primeiroNome = strtolower($partes[0]);
        
            $email = "{$primeiroNome}.{$numero}@example.com";
        
            return $email; // Nome muito curto
        }
    
        $primeiroNome = strtolower($partes[0]);
        $ultimoNome = strtolower(end($partes));

        // Montar o e-mail
        $email = "{$primeiroNome}.{$numero}.{$ultimoNome}@example.com";
    
        return $email;
    }
    
    public function receita_padrao()
    {
        $user = User::findOrFail(Auth::user()->id);
        $entidade = Entidade::findOrFail($user->entidade_id);

        $receita = Receita::where('entidade_id', '=', $entidade->id)->where('type', 'R')->where('nome', 'Outros Proveitos Operacionais')->first();
        
        if (!$receita) {
            return redirect()->route('dashboard')->with("warning", "Precisas cadastrar uma receita padrão!");
        }
        return $receita->id;
    }

    public function mes_em_portugues($mes)
    {
        if ($mes == "January") {
            return "Janeiro";
        }
        if ($mes == "February") {
            return "Fevereiro";
        }
        if ($mes == "March") {
            return "Março";
        }
        if ($mes == "April") {
            return "Abril";
        }
        if ($mes == "May") {
            return "Maio";
        }
        if ($mes == "June") {
            return "Junho";
        }
        if ($mes == "July") {
            return "Julho";
        }
        if ($mes == "August") {
            return "Agosto";
        }
        if ($mes == "September") {
            return "Setembro";
        }
        if ($mes == "October") {
            return "Outubro";
        }
        if ($mes == "November") {
            return "Novembro";
        }
        if ($mes == "December") {
            return "Dezembro";
        }
    }

    function valor_por_extenso($v)
    {

        $v = filter_var($v, FILTER_SANITIZE_NUMBER_INT);

        $sin = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plu = array("centavos", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        $z = 0;

        $v = number_format($v, 2, ".", ".");
        $int = explode(".", $v);

        for ($i = 0; $i < count($int); $i++) {
            for ($ii = mb_strlen($int[$i]); $ii < 3; $ii++) {
                $int[$i] = "0" . $int[$i];
            }
        }

        $rt = null;
        $fim = count($int) - ($int[count($int) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($int); $i++) {
            $v = $int[$i];
            $rc = (($v > 100) && ($v < 200)) ? "cento" : $c[$v[0]];
            $rd = ($v[1] < 2) ? "" : $d[$v[1]];
            $ru = ($v > 0) ? (($v[1] == 1) ? $d10[$v[2]] : $u[$v[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($int) - 1 - $i;
            $r .= $r ? " " . ($v > 1 ? $plu[$t] : $sin[$t]) : "";
            if ($v == "000")
                $z++;
            elseif ($z > 0)
                $z--;

            if (($t == 1) && ($z > 0) && ($int[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plu[$t];

            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($int[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr($rt, 1);

        return ($rt ? trim($rt) : "zero");
    }


    public function registra_movimentos($subconta_caixa, $code, $observacao, $data_emissao, $empresa, $movimento, $credito = 0, $debito = 0, $exercicio_id = 1, $periodo_id = 12)
    {

        $movimeto = Movimento::create([
            'user_id' => Auth::user()->id,
            'subconta_id' => $subconta_caixa,
            'status' => true,
            'movimento' => $movimento,
            'credito' => $credito,
            'debito' => $debito,
            'observacao' => $observacao,
            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
            'code' => $code,
            'data_at' => $data_emissao,
            'entidade_id' => $empresa,
            'exercicio_id' => $exercicio_id,
            'periodo_id' => $periodo_id,
        ]);

        return $movimeto;
    }

    public function registra_operacoes(
        $valor,
        $subconta_caixa,
        $cliente,
        $type,
        $status,
        $code,
        $movimento,
        $data_emissao,
        $empresa,
        $observacao,
        $user_open_id,
        $status_caixa = null,
        $formas = "O",
        $code_caixa = null,
        $model = 1,
        $parcelado = "N",
        $parcelas = null,
        $fornecedor_id = null,
        $exercicio_id = 1,
        $periodo_id = 12
    ) {
    
        $operacoes = OperacaoFinanceiro::create([
            'nome' => $observacao,
            'status' => $status,
            'motante' => $valor,
            'formas' => $formas,
            'code_caixa' => $code_caixa,
            'status_caixa' => $status_caixa,
            'subconta_id' => $subconta_caixa,
            'cliente_id' => $cliente,
            'fornecedor_id' => $fornecedor_id,
            'model_id' => $model,
            'type' => $type,
            'parcelado' => $parcelado,
            'parcelas' => $parcelas,
            'status_pagamento' => $status,
            'code' => $code,
            'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
            'descricao' => $observacao,
            'movimento' => $movimento,
            'date_at' => $data_emissao,
            'user_id' => Auth::user()->id,
            'user_open_id' => $user_open_id,
            'entidade_id' => $empresa,
            'exercicio_id' => $exercicio_id,
            'periodo_id' => $periodo_id,
        ]);

        return $operacoes;
    }

    // retornar saldo de uma determinada conta
    public function saldo_conta($subconta_id)
    {
        $user = auth()->user();
        
        $saldos_caixas = OperacaoFinanceiro::where('subconta_id', $subconta_id)
        ->where('status_pagamento', 'pago')
        ->where('entidade_id', $user->entidade_id)
        ->selectRaw("
            SUM(CASE WHEN type = 'R' THEN motante ELSE 0 END) as receita_caixa,
            SUM(CASE WHEN type = 'D' THEN motante ELSE 0 END) as despesa_caixa
        ")
        ->first();
        
        $saldo = $saldos_caixas->receita_caixa - $saldos_caixas->despesa_caixa;
        
        return  $saldo;
    }

    // Função para criar contas
    public function tabela_taxas_irt()
    {
        $tabela = [
            [
                'nome' => '1º Escalão',
                'remuneracao' => '100000.00',
                'taxa' => '0.00',
                'abatimento' => '0.00',
                'valor_fixo' => '0.00',
                'excesso' => '0.00',
            ],
            [
                'nome' => '2º Escalão',
                'remuneracao' => '150000.00',
                'taxa' => '13.00',
                'abatimento' => '100001.00',
                'valor_fixo' => '0.00',
                'excesso' => '100000.00',
            ],
            [
                'nome' => '3º Escalão',
                'remuneracao' => '200000.00',
                'taxa' => '16.00',
                'abatimento' => '150001.00',
                'valor_fixo' => '12500.00',
                'excesso' => '150000.00',
            ],
            [
                'nome' => '4º Escalão',
                'remuneracao' => '300000.00',
                'taxa' => '18.00',
                'abatimento' => '200001.00',
                'valor_fixo' => '31250.00',
                'excesso' => '200000.00',
            ],
            [
                'nome' => '5º Escalão',
                'remuneracao' => '500000.00',
                'taxa' => '19.00',
                'abatimento' => '300001.00',
                'valor_fixo' => '49259.00',
                'excesso' => '300000.00',
            ],
            [
                'nome' => '6º Escalão',
                'remuneracao' => '1000000.00',
                'taxa' => '20.00',
                'abatimento' => '500001.00',
                'valor_fixo' => '87250.00',
                'excesso' => '500000.00',
            ],
            [
                'nome' => '7º Escalão',
                'remuneracao' => '1500000.00',
                'taxa' => '21.00',
                'abatimento' => '1000001.00',
                'valor_fixo' => '187249.00',
                'excesso' => '1000000.00',
            ],
            [
                'nome' => '8º Escalão',
                'remuneracao' => '2000000.00',
                'taxa' => '22.00',
                'abatimento' => '1500001.00',
                'valor_fixo' => '292249.00',
                'excesso' => '1500000.00',
            ],
            [
                'nome' => '9º Escalão',
                'remuneracao' => '2500000.00',
                'taxa' => '23.00',
                'abatimento' => '2000001.00',
                'valor_fixo' => '402249.00',
                'excesso' => '2000000.00',
            ],
            [
                'nome' => '10º Escalão',
                'remuneracao' => '5000000.00',
                'taxa' => '24.00',
                'abatimento' => '2500001.00',
                'valor_fixo' => '517249.00',
                'excesso' => '2500000.00',
            ],
            [
                'nome' => '11º Escalão',
                'remuneracao' => '10000000.00',
                'taxa' => '24.50',
                'abatimento' => '5000001.00',
                'valor_fixo' => '1117249.00',
                'excesso' => '5000000.00',
            ],
            [
                'nome' => '12º Escalão',
                'remuneracao' => '0.00',
                'taxa' => '25.00',
                'abatimento' => '10000001.00',
                'valor_fixo' => '2342248.00',
                'excesso' => '10000000.00',
            ],
        ];

        return $tabela;
    }

    // Função tipos de processamentos
    public function tipos_processamentos()
    {
        $processamentos = [
            [
                'nome' => 'Vencimento',
                'status' => 'activo',
                'sigla' => 'V',
            ],
            [
                'nome' => 'Subsídio de Férias',
                'status' => 'activo',
                'sigla' => 'F',
            ],
            [
                'nome' => 'Subsídio Natal',
                'status' => 'activo',
                'sigla' => 'N',
            ],
            [
                'nome' => 'Fim Contrato',
                'status' => 'activo',
                'sigla' => 'C',
            ],
            [
                'nome' => 'Extraordinário',
                'status' => 'activo',
                'sigla' => 'E',
            ],
        ];

        return $processamentos;
    }
    
    // Função tipos de subsidios
    public function subsidios()
    {
        $subsidios = [
            [
                'nome' => 'Subsídio de Alimentação',
                'numero' => 'S001',
                'status' => 'activo',
            ],
            [
                'nome' => 'Subsídio de Transporte',
                'numero' => 'S002',
                'status' => 'activo',
            ],
        ];

        return $subsidios;
    }
    
    // Função tipos de descontos
    public function descontos()
    {
        $descontos = [
            [
                'nome' => 'IRT - Remunerações não fixasss',
                'numero' => 'D001',
                'desconto' => 0,
                'status' => 'activo',
            ],
            [
                'nome' => 'Segurança Social',
                'numero' => 'D002',
                'desconto' => 3,
                'status' => 'activo',
            ],
        ];

        return $descontos;
    }
    
    // Função tipos de contratos
    public function tipos_contratos()
    {
        $contratos = [
            [
                'nome' => 'Determinado',
                'status' => 'activo',
            ],
            [
                'nome' => 'Indeterminado',
                'status' => 'activo',
            ],
        ];

        return $contratos;
    }
    
    // Função periodos de rendimentos
    public function periodos_rendimentos()
    {
        $contratos = [
            [
                'nome' => 'Mensal',
                'status' => 'activo',
                'numero' => '30',
            ],
            [
                'nome' => 'Quinzenal',
                'status' => 'activo',
                'numero' => '15',
            ],
        ];

        return $contratos;
    }


    // Função receitas padrões
    public function receitas_padroes()
    {
        $classes = [
            [
                'nome' => 'Vendas',
                'status' => 'activo',
                'type' => 'R',
                'sigla' => 'VE',
            ],
            [
                'nome' => 'Prestações de Serviços',
                'status' => 'activo',
                'type' => 'R',
                'sigla' => 'PS',
            ],
            [
                'nome' => 'Outros Proveitos Operacionais',
                'status' => 'activo',
                'type' => 'R',
                'sigla' => 'OPO',
            ],
            [
                'nome' => 'Proveitos e Ganhos Financeiros Gerais',
                'status' => 'activo',
                'type' => 'R',
                'sigla' => 'OPO',
            ],
            [
                'nome' => 'Proveitos e Ganhos Financeiros em filiares e associadas',
                'status' => 'activo',
                'type' => 'R',
                'sigla' => 'OPO',
            ],
            [
                'nome' => 'Depositos',
                'status' => 'activo',
                'type' => 'R',
                'sigla' => 'OPO',
            ],
            [
                'nome' => 'Transferência',
                'status' => 'activo',
                'type' => 'R',
                'sigla' => 'OPO',
            ],
            [
                'nome' => 'Alugares de quartos',
                'status' => 'activo',
                'type' => 'R',
                'sigla' => 'ALQ',
            ],
        ];

        return $classes;
    }

    // Função receitas padrões
    public function dispesas_padroes()
    {
        $classes = [
            [
                'nome' => 'Custos com o pessoal',
                'status' => 'activo',
                'type' => 'D',
                'sigla' => 'OPO',
            ],
            [
                'nome' => 'Outros custos e perdas operacionais',
                'status' => 'activo',
                'type' => 'D',
                'sigla' => 'OPO',
            ],
            [
                'nome' => 'Levantamentos',
                'status' => 'activo',
                'type' => 'D',
                'sigla' => 'OPO',
            ],
            [
                'nome' => 'Transferência',
                'status' => 'activo',
                'type' => 'D',
                'sigla' => 'OPO',
            ],
            [
                'nome' => 'Reembolso',
                'status' => 'activo',
                'type' => 'D',
                'sigla' => 'REM',
            ],
        ];

        return $classes;
    }

    // Função para criar contas
    public function classes_pgc()
    {
        $classes = [
            [
                'nome' => 'Meios Fixos e Investimentos',
                'status' => 'activo',
                'conta' => 'Classe 1',
                'sigla' => 'MFI',
            ],
            [
                'nome' => 'Existências',
                'status' => 'activo',
                'conta' => 'Classe 2',
                'sigla' => 'EX',
            ],
            [
                'nome' => 'Terceiros',
                'status' => 'activo',
                'conta' => 'Classe 3',
                'sigla' => 'TER',
            ],
            [
                'nome' => 'Meios Monetários',
                'status' => 'activo',
                'conta' => 'Classe 4',
                'sigla' => 'MMON',
            ],
            [
                'nome' => 'Capital e Reservas',
                'status' => 'activo',
                'conta' => 'Classe 5',
                'sigla' => 'CRE',
            ],
            [
                'nome' => 'Proveitos por Natureza',
                'status' => 'activo',
                'conta' => 'Classe 6',
                'sigla' => 'PR.NA',
            ],
            [
                'nome' => 'Custos por Natureza',
                'status' => 'activo',
                'conta' => 'Classe 7',
                'sigla' => 'CU.NA',
            ],
            [
                'nome' => 'Resultados',
                'status' => 'activo',
                'conta' => 'Classe 8',
                'sigla' => 'RES',
            ],
        ];

        return $classes;
    }

    // Função para criar contas
    public function criarClasse($entidade, $classe, $nome, $sigla)
    {
        if (!Classe::where('entidade_id', $entidade->empresa->id)->where('conta', $classe)->where('nome', $nome)->first()) {
            return Classe::create([
                'entidade_id' => $entidade->empresa->id,
                'nome' => $nome,
                'sigla' => $sigla,
                'status' => 'activo',
                'conta' => $classe,
                'user_id' => Auth::id(),
            ]);
        }
    }

    // Função para criar contas
    public function criarConta($entidade, $classe, $nome, $conta)
    {
        if (!Conta::where('entidade_id', $entidade->empresa->id)->where('conta', $conta)->where('nome', $nome)->first()) {
            return Conta::create([
                'entidade_id' => $entidade->empresa->id,
                'nome' => $nome,
                'serie' => 1,
                'status' => 'activo',
                'conta' => $conta,
                'classe_id' => $classe->id,
                'user_id' => Auth::id(),
            ]);
        }
    }

    // Função para criar subcontas
    public function criarSubconta($entidade, $conta, $numero, $nome)
    {
        if (!Subconta::where('entidade_id', $entidade->empresa->id)->where('numero', $numero)->where('nome', $nome)->first()) {
            return Subconta::create([
                'entidade_id' => $entidade->empresa->id,
                'numero' => $numero,
                'nome' => $nome,
                'tipo_conta' => 'E',
                'code' => uniqid(time() . $numero),
                'status' => 'activo',
                'conta_id' => $conta->id,
                'user_id' => Auth::id(),
            ]);
        }
    }

    public function plano_geral_contas_classe_1()
    {
        // Criar contas e subcontas de forma dinâmica
        $contas = [
            '11' => [
                'nome' => 'Imobilizações corpóreas',
                'subcontas' => [
                    '11.1' => 'Terrenos e recursos naturais',
                    '11.1.1' => 'Terrenos em bruto',
                    '11.1.2' => 'Terrenos com arranjos',
                    '11.1.3' => 'Subsolos',
                    '11.1.4' => 'Terrenos com edifícios',
                    '11.1.4.1' => 'Relativos a edifícios industriais',
                    '11.1.4.2' => 'Relativos a edifícios administrativos e comerciais',
                    '11.1.4.3' => 'Relativos a outros edifícios',
                    '11.2' => 'Edifícios e outras construções',
                    '11.2.1' => 'Edifícios',
                    '11.2.1.1' => 'Integrados em conjuntos industriais',
                    '11.2.1.2' => 'Integrados em conjuntos administrativos e comerciais',
                    '11.2.1.3' => 'Outros conjuntos industriais',
                    '11.2.1.4' => 'Implantados em propriedade alheia',
                    '11.2.2' => 'Outras construções',
                    '11.2.3' => 'Instalações',
                    '11.3' => 'Equipamento básico',
                    '11.3.1' => 'Material industria',
                    '11.3.2' => 'Ferramentas industriais',
                    '11.3.3' => 'Melhoramentos em equipamentos básicos',
                    '11.4' => 'Equipamento de carga e transporte',
                    '11.5' => 'Equipamento administrativo',
                    '11.6' => 'Equipamento administrativo',
                    '11.9' => 'Equipamento administrativo',
                ],
            ],
            '12' => [
                'nome' => 'Imobilizações incorpóreas',
                'subcontas' => [
                    '12.1' => 'Trespasses',
                    '12.3' => 'Propriedade industrial e outros direitos e contratos',
                    '12.4' => 'Despesas de constituição',
                    '12.9' => 'Outras imobilizações incorpóreas',
                ],
            ],
            '13' => [
                'nome' => 'Investimentos financeiros',
                'subcontas' => [
                    '13.1' => 'Empresas subsidiárias',
                    '13.1.1' => 'Partes de capital',
                    '13.1.2' => 'Obrigações e títulos de participação',
                    '13.1.3' => 'Empréstimos',
                    '13.2' => 'Empresas associadas',
                    '13.2.1' => 'Partes de capital',
                    '13.2.2' => 'Obrigações e títulos de participação',
                    '13.2.3' => 'Empréstimos',
                    '13.3' => 'Outras empresas',
                    '13.3.1' => 'Partes de capital',
                    '13.3.2' => 'Obrigações e títulos de participação',
                    '13.3.3' => 'Empréstimos',
                    '13.4' => 'Investimentos em imóveis',
                    '13.5' => 'Fundos',
                    '13.9' => 'Outros investimentos Financeiros',
                    '13.9.1' => 'Diamantes',
                    '13.9.2' => 'Ouro',
                    '13.9.3' => 'Depósitos bancários',
                ],
            ],
            '14' => [
                'nome' => 'Imobilizações em curso',
                'subcontas' => [
                    '14.1' => 'Obra em curso',
                    '14.2' => 'Obra em curso',
                    '14.7' => 'Adiantamentos por conta de imobilizado corpóreo',
                    '14.8' => 'Adiantamentos por conta de imobilizado incorpóreo',
                    '14.9' => 'Adiantamentos por conta de investimentos financeiros',
                ],
            ],
            '18' => [
                'nome' => 'Amortizações acumuladas',
                'subcontas' => [
                    '18.1' => 'Imobilizações corpóreas',
                    '18.1.1' => 'Terrenos e recursos naturais',
                    '18.1.2' => 'Edifícios e outras construções',
                    '18.1.3' => 'Equipamento básico',
                    '18.1.4' => 'Equipamento de carga e transporte',
                    '18.1.5' => 'Equipamento administrativo',
                    '18.1.6' => 'Taras e vasilhame',
                    '18.1.9' => 'Outras imobilizações corpóreas',
                    '18.2' => 'Imobilizações incorpóreas',
                    '18.2.1' => 'Trespasses',
                    '18.2.2' => 'Despesas de investigação e desenvolvimento',
                    '18.2.3' => 'Propriedade industrial e outros direitos e contratos',
                    '18.2.4' => 'Despesas de constituição',
                    '18.2.9' => 'Outras imobilizações incorpóreas',
                    '18.3' => 'Investimentos financeiros em imóveis',
                    '18.3.1' => 'Terrenos e recursos naturais',
                    '18.3.2' => 'Edifícios e outras construções',
                ],
            ],
            '19' => [
                'nome' => '',
                'subcontas' => [
                    '19.1' => 'Empresas subsidiárias"',
                    '19.1.1' => 'Partes de capital',
                    '19.1.2' => 'Obrigações e títulos de participação',
                    '19.1.3' => 'Empréstimos',
                    '19.2' => 'Empresas associadas',
                    '19.2.1' => 'Partes de capital',
                    '19.2.2' => 'Obrigações e títulos de participação',
                    '19.2.3' => 'Empréstimos',
                    '19.3' => 'Outras empresas',
                    '19.3.1' => 'Partes de capital',
                    '19.3.2' => 'Obrigações e títulos de participação',
                    '19.3.3' => 'Empréstimos',
                    '19.4' => 'Fundos',
                    '19.4.1' => 'Partes de capital',
                    '19.9' => 'Outros investimentos financeiros',
                    '19.9.1' => 'Diamantes',
                    '19.9.2' => 'Ouro',
                    '19.9.3' => 'Depósitos bancários',
                ],
            ],
        ];
        return  $contas;
    }
    
    public function plano_geral_contas_classe_2()
    {
        $contas =  [
            '21' => [
                'nome' => ' Compras',
                'subcontas' => [
                    '21.1' => 'Matérias-primas, subsidiárias e de consumo',
                    '21.2' => 'Mercadorias',
                    '21.7' => 'Devoluções de compras',
                    '21.8' => 'Descontos e abatimentos em compras',
                    '21.9' => '................................',
                ],
            ],
            '22' => [
                'nome' => 'Matérias-primas, subsidiárias e de consumo',
                'subcontas' => [
                    '22.1' => 'Matérias-primas',
                    '22.2' => 'Matérias subsidiárias',
                    '22.3' => 'Materiais diversos',
                    '22.4' => 'Embalagens de consumo',
                    '22.5' => 'Outros materiais',
                ],
            ],
            '23' => [
                'nome' => 'Produtos e trabalhos em curso',
                'subcontas' => [],
            ],
            '24' => [
                'nome' => 'Produtos acabados e intermédios',
                'subcontas' => [
                    '24.1' => 'Produtos acabados',
                    '24.2' => 'Produtos intermédios',
                    '24.9' => 'Em poder de terceiros',
                ],
            ],
            '25' => [
                'nome' => 'Sub-produtos, desperdícios, resíduos e refugos',
                'subcontas' => [
                    '25.1' => 'Sub-produtos',
                    '25.2' => 'Desperdícios, resíduos e refugos',
                ],
            ],
            '26' => [
                'nome' => 'Mercadorias',
                'subcontas' => [
                    '26.1' => 'Mercadorias',
                    '26.9' => 'Em poder de terceiros',
                ],
            ],
            '27' => [
                'nome' => 'Matérias-primas, mercadorias e outros materiais em trânsito',
                'subcontas' => [
                    '27.1' => 'Matérias-primas',
                    '27.2' => 'Outros materiais',
                    '27.3' => 'Mercadorias',
                ],
            ],
            '28' => [
                'nome' => 'Adiantamentos por conta de compras',
                'subcontas' => [
                    '28.1' => 'Matérias-primas e outros materiais',
                    '28.2' => 'Mercadorias',
                ],
            ],
            '29' => [
                'nome' => 'Provisão para depreciação de existências',
                'subcontas' => [
                    '29.2' => 'Matérias-primas subsidiárias e de consumo',
                    '29.3' => 'Produtos e trabalhos em curso',
                    '29.4' => 'Produtos acabados e intermédios',
                    '29.5' => 'Sub-produtos, desperdícios, resíduos e refugos',
                    '29.6' => 'Mercadorias',
                ],
            ],

        ];

        return  $contas;
    }
    
    public function plano_geral_contas_classe_3()
    {
        $contas = [
            '31' => [
                'nome' => 'Clientes',
                'subcontas' => [
                    '31.1' => 'Clientes – correntes',
                    '31.1.1' => 'Grupo',
                    '31.1.2' => 'Não grupo',
                    '31.2' => 'Clientes – títulos a receber',
                    '31.2.1' => 'Grupo',
                    '31.2.2' => 'Não grupo',
                    '31.3' => 'Clientes – títulos descontados',
                    '31.3.1' => 'Grupo',
                    '31.3.2' => 'Não grupo',
                    '31.8' => 'Clientes de cobrança duvidosa',
                    '31.8.1' => 'Clientes – correntes',
                    '31.8.2' => 'Clientes – títulos',
                    '31.9' => 'Clientes - saldos credores',
                    '31.9.1' => 'Adiantamento',
                    '31.9.2' => 'Embalagens a devolver',
                    '31.9.3' => 'Material à consignação',
                ],
            ],

            '32' => [
                'nome' => 'Fornecedores',
                'subcontas' => [
                    '32.1' => 'Fornecedores – correntes',
                    '32.1.1' => 'Grupo',
                    '32.1.2' => 'Não grupo',
                    '32.1.2.1' => 'Nacionais',
                    '32.1.2.2' => 'Estrangeiros',
                    '32.2' => 'Fornecedores – títulos a pagar',
                    '32.2.1' => 'Grupo',
                    '32.2.1.1' => 'Subsidiárias',
                    '32.2.1.2' => 'Associadas',
                    '32.2.2' => 'Associadas',
                    '32.2.2.1' => 'Nacionais',
                    '32.2.2.2' => 'Estrangeiros',
                    '32.8' => 'Fornecedores – facturas em recepção e conferência',
                    '32.9' => 'Fornecedores – saldos devedores',
                ],
            ],

            '33' => [
                'nome' => 'Empréstimos',
                'subcontas' => [
                    '33.1' => 'Empréstimos bancários',
                    '33.1.1' => 'Moeda nacional',
                    '33.1.2' => 'Moeda estrangeira',
                    '33.2' => 'Empréstimos por obrigações',
                    '33.3' => 'Empréstimos por títulos de participação',
                    '33.9' => 'Outros empréstimos obtidos',
                ],
            ],

            '34' => [
                'nome' => 'Estado',
                'subcontas' => [
                    '34.1' => 'Imposto sobre os lucros',
                    '34.2' => 'Imposto de produção e consumo',
                    '34.3' => 'Imposto de rendimento de trabalho',
                    '34.4' => 'Imposto de circulação',
                    '34.5' => 'IVA',
                    '34.5.1' => 'IVA suportado:',
                    '34.5.1.1' => 'Existências',
                    '34.5.1.2' => 'Meios fixos e investimentos',
                    '34.5.1.3' => 'Outros bens e serviço',
                    '34.5.2' => 'IVA dedutível',
                    '34.5.2.1' => 'Existências',
                    '34.5.2.2' => 'Meios fixos e investimentos',
                    '34.5.2.3' => 'Outros bens e serviços',
                    '34.5.3' => 'IVA liquidado',
                    '34.5.3.1' => 'Operações gerais',
                    '34.5.3.2' => 'Operações abrangidas pelo regime de IVA de caixa',
                    '34.5.3.3' => 'Autoconsumo e operações gratuitas',
                    '34.5.3.4' => 'Operações especiais',
                    '34.5.4' => 'IVA regularizações',
                    '34.5.4.1' => 'Mensais a favor do sujeito passivo',
                    '34.5.4.2' => 'Mensais a favor do Estado',
                    '34.5.4.3' => 'Anual por cálculo do pró rata definitivo',
                    '34.5.4.4' => 'Outras regularizações anuais',
                    '34.5.5' => 'IVA apuramento',
                    '34.5.5.1' => 'Apuramento do regime de IVA normal',
                    '34.5.5.2' => 'Apuramento do regime de IVA de caixa',
                    '34.5.6' => 'IVA a pagar',
                    '34.5.6.1' => 'IVA a pagar de apuramento',
                    '34.5.6.2' => 'IVA a pagar de cativo',
                    '34.5.6.3' => 'IVA a pagar de liquidações oficiosas',
                    '34.5.7' => 'IVA a recuperar',
                    '34.5.7.1' => 'IVA a recuperar de apuramentos',
                    '34.5.7.2' => 'IVA a recuperar de cativo',
                    '34.5.8' => 'IVA reembolsos pedidos',
                    '34.5.8.1' => 'Reembolsos pedidos',
                    '34.5.8.2' => 'Reembolsos deferidos',
                    '34.5.8.3' => 'Reembolsos indeferidos',
                    '34.5.8.4' => 'Reembolsos reclamados, recorridos ou impugnados',
                    '34.5.9' => 'IVA Liquidações oficiosas',
                    '34.6' => 'Certificado de crédito fiscal a compensar',
                    '34.8' => 'Subsídios a preços',
                    '34.9' => 'Outros impostos',
                ],
            ],

            '35' => [
                'nome' => 'Entidades participantes e participadas',
                'subcontas' => [
                    '35.1' => 'Entidades participantes',
                    '35.1.1' => 'Estado',
                    '35.1.1.1' => 'c/subscrição',
                    '35.1.1.2' => 'c/adiantamentos sobre lucros',
                    '35.1.1.3' => 'c/lucros',
                    '35.1.1.4' => 'Empréstimos',
                    '35.1.2' => 'Empresas do grupo – subsidiárias',
                    '35.1.2.1' => 'c/subscrição',
                    '35.1.2.2' => 'c/adiantamentos sobre lucro',
                    '35.1.2.3' => 'c/lucros',
                    '35.1.2.4' => 'Empréstimos',
                    '35.1.3' => 'Empresas do grupo – associadas',
                    '35.1.3.1' => 'c/subscrição',
                    '35.1.3.2' => 'c/adiantamentos sobre lucros',
                    '35.1.3.3' => 'c/lucros',
                    '35.1.3.4' => 'Empréstimos',
                    '35.1.4' => 'Outros',
                    '35.1.4.1' => 'c/subscrição',
                    '35.1.4.2' => 'c/adiantamentos sobre lucros',
                    '35.1.4.3' => 'c/lucros',
                    '35.1.4.4' => 'Empréstimos',
                    '35.2' => 'Entidades participadas',
                    '35.2.1' => 'Estado',
                    '35.2.1.1' => 'c/subscrição',
                    '35.2.1.2' => 'c/adiantamentos sobre lucros',
                    '35.2.1.3' => 'c/lucros',
                    '35.2.1.4' => 'Empréstimos',
                    '35.2.2' => 'Empresas do grupo – subsidiárias',
                    '35.2.2.1' => 'c/subscrição',
                    '35.2.2.2' => 'c/adiantamentos sobre lucros',
                    '35.2.2.3' => 'c/lucros',
                    '35.2.2.4' => 'Empréstimos',
                    '35.2.3' => 'Empresas do grupo – associadas',
                    '35.2.3.1' => 'c/subscrição',
                    '35.2.3.2' => 'c/adiantamentos sobre lucros',
                    '35.2.3.3' => 'c/lucros',
                    '35.2.3.4' => 'Empréstimos',
                    '35.2.4' => 'Outros',
                    '35.2.4.1' => 'c/subscrição',
                    '35.2.4.2' => 'c/adiantamentos sobre lucros',
                    '35.2.4.3' => 'c/lucros',
                    '35.2.4.4' => 'Empréstimos',
                ],
            ],
            '36' => [
                'nome' => 'Pessoal',
                'subcontas' => [],
            ],

            '37' => [
                'nome' => 'Outros valores a receber e a pagar',
                'subcontas' => [
                    '37.1' => 'Compras de imobilizado',
                    '37.1.1' => 'Corpóreo',
                    '37.1.2' => 'Incorpóreo',
                    '37.1.3' => 'Financeiro',
                    '37.2' => 'Vendas de imobilizado',
                    '37.2.1' => 'Corpóreo',
                    '37.2.2' => 'Incorpóreo',
                    '37.2.3' => 'Financeiro',
                    '37.3' => 'Proveitos a facturar',
                    '37.3.1' => 'Vendas',
                    '37.3.2' => 'Prestações de serviço',
                    '37.3.3' => 'Juros',
                    '37.4' => 'Encargos a repartir por períodos futuros',
                    '37.4.1' => 'Descontos de emissão de obrigações',
                    '37.4.2' => 'Descontos de emissão de títulos de participação',
                    '37.5' => 'Encargos a pagar',
                    '37.5.1' => 'Remunerações',
                    '37.5.2' => 'Juros',
                    '37.6' => 'Proveitos a repartir por períodos futuros',
                    '37.6.1' => 'Prémios de emissão de obrigações',
                    '37.6.2' => 'Prémios de emissão de títulos de participação',
                    '37.6.3' => 'Subsídios para investimento',
                    '37.6.4' => 'Diferenças de câmbio favoráveis reversíveis',
                    '37.7' => 'Contas transitórias',
                    '37.7.1' => 'Transacções entre a sede e as dependências da empresa',
                    '37.9' => 'Outros valores a receber e a pagar',
                    '37.9.1' => 'Credores Diversos',
                ],
            ],
            '38' => [
                'nome' => 'Provisões para cobranças duvidosas',
                'subcontas' => [
                    '38.1' => 'Provisões para clientes',
                    '38.1.1' => 'Clientes – corrente',
                ],
            ],
            '39' => [
                'nome' => 'Provisões para outros riscos e encargos',
                'subcontas' => [
                    '39.1' => 'Provisões para pensões',
                    '39.2' => 'Provisões para processos judiciais em curso',
                    '39.3' => 'Provisões para acidentes de trabalho',
                    '39.4' => 'Provisões para garantias dadas a clientes',
                    '39.9' => 'Provisões para outros riscos e encargos',
                ],
            ],

        ];

        return  $contas;
    }
    
    public function plano_geral_contas_classe_4()
    {

        $contas = [
            '41' => [
                'nome' => 'Títulos negociáveis',
                'subcontas' => [
                    '41.1' => 'Acções',
                    '41.1.1' => 'Empresas do grupo',
                    '41.1.2' => 'Associadas',
                    '41.1.3' => 'Outras empresas',
                    '41.2' => 'Obrigações',
                    '41.2.1' => 'Empresas do grupo',
                    '41.2.2' => 'Associadas',
                    '41.2.3' => 'Outras empresas',
                    '41.3' => 'Títulos da dívida pública',
                ],
            ],
            '42' => [
                'nome' => 'Depósitos a prazo',
                'subcontas' => [
                    '42.1' => 'Moeda nacional',
                    '42.2' => 'Moeda estrangeira',
                ]
            ],
            '43' => [
                'nome' => 'Depósitos à ordém',
                'subcontas' => [
                    '43.1' => 'Moeda nacional',
                    '43.2' => 'Moeda estrangeira',
                ]
            ],
            '44' => [
                'nome' => 'Outros depósitos',
                'subcontas' => [
                    '44.1' => 'Moeda nacional',
                    '44.2' => 'Moeda estrangeira',
                ]
            ],
            '45' => [
                'nome' => 'Caixa',
                'subcontas' => [
                    '45.1' => 'Fundo fixo',
                    '45.2' => 'Valores para depositar',
                    '45.3' => 'Valores destinados a pagamentos específicos',
                ]
            ],
            '48' => [
                'nome' => 'Conta transitória',
                'subcontas' => [],
            ],
            '49' => [
                'nome' => 'Provisões para aplicações de tesouraria',
                'subcontas' => [
                    '49.1' => 'Títulos negociáveis',
                    '49.1.1' => 'Acçõe',
                    '49.1.2' => 'Obrigações',
                    '49.1.3' => 'Títulos da dívida pública',
                    '49.2' => 'Outras aplicações de tesouraria',
                ]
            ],
        ];

        return  $contas;
    }
    
    public function plano_geral_contas_classe_5()
    {
        $contas = [

            '51' => [
                'nome' => 'Capital',
                'subcontas' => [
                    '51.1' => 'Capital',
                ],
            ],
            '52' => [
                'nome' => 'Acções/quotas próprias',
                'subcontas' => [
                    '52.1' => 'Valor nomina',
                    '52.2' => 'Descontos',
                    '52.3' => 'Prémios',
                ],
            ],
            '53' => [
                'nome' => 'Prémios de emissão',
                'subcontas' => [],
            ],
            '54' => [
                'nome' => 'Prestações suplementares',
                'subcontas' => [],
            ],
            '55' => [
                'nome' => 'Reservas legais',
                'subcontas' => [],
            ],
            '56' => [
                'nome' => 'Reservas de reavaliação',
                'subcontas' => [
                    '56.1' => 'Legais',
                    '56.1.1' => 'Decreto-Lei n.º ___',
                    '56.1.2' => 'Decreto-Lei n.º ___',
                    '56.2' => 'Autónomas',
                    '56.2.1' => 'Avaliação',
                ],
            ],
            '57' => [
                'nome' => 'Reservas com fins especiais',
                'subcontas' => [
                    '57.1' => 'Avaliação',
                ],
            ],
        ];

        return  $contas;
    }
    
    public function plano_geral_contas_classe_6()
    {
        $contas = [

            '61' => [
                'nome' => 'Vendas',
                'subcontas' => [
                    '61.1' => 'Produtos acabados e intermédios',
                    '61.1.1' => 'Mercado nacional',
                    '61.1.2' => 'Mercado estrangeiro',
                    '61.2' => 'Sub-produtos, desperdícios',
                    '61.2.1' => 'Mercado nacional',
                    '61.2.2' => 'Mercado estrangeiro',
                    '61.3' => 'Mercadorias',
                    '61.3.1' => 'Mercado nacional',
                    '61.3.2' => 'Mercado estrangeiro',
                    '61.4' => 'Embalagens de consumo',
                    '61.4.1' => 'Mercado nacional',
                    '61.4.2' => 'Mercado estrangeiro',
                    '61.5' => 'Subsídios a preços',
                    '61.7' => 'Devoluções',
                    '61.7.1' => 'Mercado nacional',
                    '61.7.2' => 'Mercado estrangeiro',
                    '61.8' => 'Descontos e abatimento',
                    '61.8.1' => 'Mercado nacional',
                    '61.8.2' => 'Mercado estrangeiro',
                    '61.9' => 'Transferência para resultados operacionais',
                ],
            ],
            '62' => [
                'nome' => 'Prestações de serviços',
                'subcontas' => [
                    '62.1' => 'Serviços principais',
                    '62.1.1' => 'Mercado nacional',
                    '62.1.2' => 'Mercado estrangeiro',
                    '62.2' => 'Serviços secundários',
                    '62.2.1' => 'Mercado nacional"',
                    '62.2.2' => 'Mercado estrangeiro',
                    '62.8' => 'Descontos e abatimentos',
                    '62.8.1' => 'Mercado nacional',
                    '62.8.2' => 'Mercado estrangeiro',
                    '62.9' => 'Mercado estrangeiro',
                ],
            ],
            '63' => [
                'nome' => 'Outros proveitos operacionais',
                'subcontas' => [
                    '63.1' => 'Serviços suplementares',
                    '63.1.1' => 'Aluguer de equipamento',
                    '63.1.2' => 'Cedência de pessoal',
                    '63.1.3' => 'Cedência de energia',
                    '63.1.4' => 'Estudos, projectos e assistência técnica',
                    '63.2' => 'Royalties',
                    '63.3' => 'Subsídios à exploração',
                    '63.4' => 'Subsídios a investimento',
                    '63.5' => 'IVA',
                    '63.8' => 'Outros proveitos e ganhos operacionais',
                ],
            ],
            '64' => [
                'nome' => 'Variação nos inventários de produtos acabados e de produção em curso',
                'subcontas' => [
                    '64.1' => 'Produtos e trabalhos em curso',
                    '64.2' => 'Produtos acabados',
                    '64.3' => 'Produtos intermédios',
                ]
            ],
            '65' => [
                'nome' => 'Trabalhos para a própria empresa',
                'subcontas' => [
                    '65.1' => 'Para imobilizado',
                    '65.1.1' => 'Corpóreo',
                    '65.1.2' => 'Incorpóreo',
                    '65.1.3' => 'Financeiro',
                    '65.1.4' => 'Em curso',
                    '65.2' => 'Para encargos a repartir por exercícios futuros',
                    '65.9' => 'Transferência para resultados operacionais',
                ]
            ],
            '66' => [
                'nome' => 'Proveitos e ganhos financeiros gerais',
                'subcontas' => [
                    '66.1' => 'Juros',
                    '66.1.1' => 'De investimentos financeiros',
                    '66.1.1.1' => 'Obrigações',
                    '66.1.1.3' => 'Títulos de participação',
                    '66.1.1.4' => 'Empréstimos',
                    '66.1.1.9' => 'Outros',
                    '66.1.2' => 'De mora relativos a dívidas de terceiros',
                    '66.1.2.1' => 'Dívidas recebidas a prestações',
                    '66.1.2.2' => 'De empréstimos a terceiros',
                    '66.1.4' => 'Desconto de títulos',
                    '66.1.5' => 'De aplicações de tesouraria',
                    '66.2' => 'Diferenças de câmbio favoráveis',
                    '66.2.1' => 'Realizadas',
                    '66.2.2' => 'Não realizadas',
                    '66.3' => 'Descontos de pronto pagamento obtidos',
                    '66.4' => 'Rendimentos de investimentos em imóveis',
                    '66.5' => 'Rendimento de participações de capital',
                    '66.5.1' => 'Acções, quotas em outras empresas',
                    '66.5.2' => 'Acções, quotas incluídas nos fundos',
                    '66.5.3' => 'Acções, quotas incluídas nos títulos negociáveis',
                    '66.6' => 'Ganhos na alienação de aplicações financeiras',
                    '66.6.1' => 'Investimentos financeiros',
                    '66.6.1.1' => 'Subsidiárias',
                    '66.6.1.2' => 'Associadas',
                    '66.6.1.3' => 'Outras empresas',
                    '66.6.1.4' => 'Imóveis',
                    '66.6.1.5' => 'Fundos',
                    '66.6.1.9' => 'Outros investimentos',
                    '66.6.2' => 'Títulos negociáveis',
                    '66.7' => 'Reposição de provisões',
                    '66.7.1' => 'Investimentos financeiros',
                    '66.7.1.1' => 'Subsidiárias',
                    '66.7.1.2' => 'Associadas',
                    '66.7.1.3' => 'Outras empresas',
                    '66.7.1.4' => 'Fundos',
                    '66.7.1.9' => 'Outros investimentos',
                    '66.7.2' => 'Aplicações de tesouraria',
                    '66.7.2.1' => 'Títulos negociáveis',
                    '66.7.2.2' => 'Depósitos a prazo',
                    '66.7.2.3' => 'Outros depósitos',
                    '66.7.2.9' => 'Outros investimentos',
                    '66.9' => 'Transferência para resultados financeiros',
                ],
            ],
            '67' => [
                'nome' => 'Proveitos e ganhos financeiros em filiais e associadas',
                'subcontas' => [
                    '67.1' => 'Rendimento de participações de capital',
                    '67.1.1' => 'Subsidiárias',
                    '67.1.2' => 'Associadas',
                    '67.9' => 'Transferência para resultados em filiais e associadas',
                ]
            ],
            '68' => [
                'nome' => 'Outros proveitos não operacionais',
                'subcontas' => [
                    '68.1.1' => 'Existências',
                    '68.1.1.1' => 'Matérias-primas subsidiárias e de consumo',
                    '68.1.1.2' => 'Produtos e trabalhos em curso',
                    '68.1.1.3' => 'Produtos acabados e intermédios',
                    '68.1.1.4' => 'Sub-produtos',
                    '68.1.1.5' => 'Mercadorias',
                    '68.1.2' => 'Cobranças duvidosas',
                    '68.1.2.1' => 'Clientes',
                    '68.1.2.2' => 'Clientes – títulos a receber',
                    '68.1.2.3' => 'Clientes – cobrança duvidosa',
                    '68.1.2.4' => 'Saldos devedores de fornecedores',
                    '68.1.2.5' => 'Participantes e participadas',
                    '68.1.2.6' => 'Dívidas do Pessoal',
                    '68.1.2.9' => 'Outros saldos a receber',
                    '68.1.3' => 'Riscos e encargos',
                    '68.1.3.1' => 'Pensões',
                    '68.1.3.2' => 'Processos judiciais em curso',
                    '68.1.3.3' => 'Acidentes de trabalho',
                    '68.1.3.4' => 'Garantias dadas a clientes',
                    '68.1.3.9' => 'Outros riscos e encargos',
                    '68.10' => 'Correcções relativas a exercícios anteriores',
                    '68.10.1' => 'Estimativa impostos',
                    '68.10.2' => 'Restituição de impostos',
                    '68.11' => 'Outros ganhos e perdas não operacionais',
                    '68.11.1' => 'Donativos',
                    '68.19' => 'Transferência para resultados não operacionais',
                    '68.2' => 'Anulação de amortizações extraordinárias',
                    '68.2.1' => 'Imobilizações corpóreas',
                    '68.2.2' => 'Imobilizações incorpóreas',
                    '68.3' => 'Ganhos em imobilizações',
                    '68.3.1' => 'Venda de imobilizações corpóreas',
                    '68.3.2' => 'Venda de imobilizações incorpóreas',
                    '68.4' => 'Ganhos em existências',
                    '68.4.1' => 'Sobras',
                    '68.5' => 'Recuperação de dívidas',
                    '68.6' => 'Benefícios de penalidades contratuais',
                    '68.8' => 'Descontinuidade de operações',
                    '68.9' => 'Alterações de políticas contabilísticas',
                ],
            ],
            '69' => [
                'nome' => 'Proveitos e ganhos extraordinários',
                'subcontas' => [
                    '69.1' => 'Ganhos resultantes de catástrofes naturais',
                    '69.2' => 'Ganhos resultantes de convulsões políticas',
                    '69.3' => 'Ganhos resultantes de expropriações',
                    '69.4' => 'Ganhos resultantes de sinistros',
                    '69.5' => 'Subsídios',
                    '69.6' => 'Anulação de passivos não exigíveis',
                    '69.9' => 'Transferência para resultados extraordinários',
                ],
            ],
        ];

        return  $contas;
    }
    
    public function plano_geral_contas_classe_7()
    {
        $contas = [
            '71' => [
                'nome' => 'Custo das mercadorias vendidas e das matérias consumidas',
                'subcontas' => [
                    '71.1' => 'Matérias-primas',
                    '71.2' => 'Matérias subsidiárias',
                    '71.3' => 'Materiais diversos',
                    '71.4' => 'Embalagens de consumo',
                    '71.5' => 'Outros materiais',
                    '71.6' => 'Custos de Mercadorias Vendidas',
                    '71.9' => 'Transferência para resultados operacionais',
                ],
            ],
            '72' => [
                'nome' => 'Custos com o pessoal',
                'subcontas' => [
                    '72.1' => 'Remunerações – Órgãos sociais',
                    '72.2' => 'Remunerações – Pessoal',
                    '72.3' => 'Pensões',
                    '72.3.1' => 'Órgãos sociais',
                    '72.3.2' => 'Pessoal',
                    '72.4' => 'Prémios para pensões',
                    '72.4.1' => 'Órgãos sociais',
                    '72.4.2' => 'Pessoal',
                    '72.5' => 'Encargos sobre remunerações',
                    '72.5.1' => 'Órgãos sociais',
                    '72.5.2' => 'Pessoal',
                ],
            ],
            '73' => [
                'nome' => 'Amortizações do exercício',
                'subcontas' => [
                    '73.1' => 'Imobilizações corpóreas',
                    '73.1.2' => 'Edifícios e outras construções',
                    '73.1.3' => 'Equipamento básico',
                    '73.1.4' => 'Equipamento de carga e transporte',
                    '73.1.5' => 'Equipamento administrativo',
                    '73.1.6' => 'Taras e vasilhame',
                    '73.1.9' => 'Outras imobilizações corpóreas',
                    '73.2' => 'Imobilizações incorpóreas',
                    '73.2.1' => 'Trespasses',
                    '73.2.2' => 'Despesas de investigação e desenvolvimento',
                    '73.2.3' => 'Propriedade industrial e outros direitos e contratos',
                    '73.2.4' => 'Despesas de constituição',
                    '73.2.9' => 'Outras imobilizações incorpóreas',
                    '73.9' => 'Transferência para resultados operacionais',
                ],
            ],
            '75' => [
                'nome' => 'Outros custos e perdas operacionais',
                'subcontas' => [
                    '75.1' => 'Sub-contratos',
                    '75.2' => 'Fomecimentos e serviços de terceiros',
                    '75.2.11' => 'Água',
                    '75.2.12' => 'Electricidade',
                    '75.2.13' => 'Combustíveis e outros fluídos',
                    '75.2.14' => 'Conservação e reparação',
                    '75.2.15' => 'Material de protecção segurança e conforto',
                    '75.2.16' => 'Ferramentas e utensílios de desgaste rápido',
                    '75.2.17' => 'Material de escritório',
                    '75.2.18' => 'Livros e documentação técnica',
                    '75.2.19' => 'Outros fornecimentos',
                    '75.2.20' => 'Comunicação',
                    '75.2.21' => 'Rendas e alugueres',
                    '75.2.22' => 'Seguros',
                    '75.2.23' => 'Deslocações e estadas',
                    '75.2.24' => 'Despesas de representação',
                    '75.2.26' => 'Conservação e reparação',
                    '75.2.27' => 'Vigilância e segurança',
                    '75.2.28' => 'Limpeza, higiene e conforto',
                    '75.2.29' => 'Publicidade e propaganda',
                    '75.2.30' => 'Contencioso e notariado',
                    '75.2.31' => 'Comissões a intermediários',
                    '75.2.32' => 'Assistência técnica',
                    '75.2.32.1' => 'Estrangeira',
                    '75.2.32.2' => 'Nacional',
                    '75.2.33' => 'Trabalhos executados no exterior',
                    '75.2.34' => 'Honorários e avenças',
                    '75.2.35' => 'Royalties',
                    '75.2.39' => 'Outros serviços',
                    '75.3' => 'Impostos',
                    '75.3.1' => 'Indirectos',
                    '75.3.1.1' => 'Imposto de selo',
                    '75.3.1.2' => 'IVA',
                    '75.3.1.9' => 'Outros impostos',
                    '75.3.2' => 'Directos',
                    '75.3.2.1' => 'Imposto de capitais',
                    '75.3.2.2' => 'Contribuição predial',
                    '75.3.2.9' => 'Outros impostos',
                    '75.4' => 'Despesas confidênciais',
                    '75.5' => 'Quotizações',
                    '75.6' => 'Ofertas e Amostras de existências',
                    '75.8' => 'Outros custos e perdas operacionais',
                    '75.9' => 'Transferências para resultados operacionais',
                ],
            ],
            '76' => [
                'nome' => 'Custos e perdas financeiros gerais',
                'subcontas' => [
                    '76.1' => 'Juros',
                    '76.1.1' => 'De empréstimos',
                    '76.1.1.1' => 'Bancários',
                    '76.1.1.2' => 'Obrigações',
                    '76.1.1.3' => 'Títulos de participação',
                    '76.1.2' => 'De descobertos bancários',
                    '76.1.3' => 'De mora relativos a dívidas a terceiros',
                    '76.1.4' => 'De desconto de títulos',
                    '76.2' => 'Diferenças de câmbio desfavoráveis',
                    '76.2.1' => 'Realizadas',
                    '76.3' => 'Descontos de pronto pagamento concedidos',
                    '76.4' => 'Amortizações de investimentos em imóveis',
                    '76.5' => 'Provisões para aplicações financeiras',
                    '76.5.1' => 'Investimentos financeiros',
                    '76.5.1.1' => 'Subsidiárias',
                    '76.5.1.2' => 'Associadas',
                    '76.5.1.3' => 'Outras empresas',
                    '76.5.1.4' => 'Fundos',
                    '76.5.1.9' => 'Outros investimentos',
                    '76.5.2' => 'Aplicações de tesouraria',
                    '76.5.2.1' => 'Títulos negociáveis',
                    '76.5.2.2' => 'Depósitos a prazo',
                    '76.5.2.3' => 'Outros depósitos',
                    '76.5.2.9' => 'Outros',
                    '76.6' => 'Perdas na alienação de aplicações financeiras',
                    '76.6.1' => 'Investimentos financeiros',
                    '76.6.1.1' => 'Subsidiárias',
                    '76.6.1.2' => 'Associadas',
                    '76.6.1.3' => 'Outras empresas',
                    '76.6.1.9' => 'Outros investimentos',
                    '76.6.2' => 'Aplicações de títulos negociáveis',
                    '76.7' => 'Serviços bancários',
                    '76.9' => 'Transferência para resultados financeiros',
                ],
            ],
            '77' => [
                'nome' => 'Custos e perdas financeiros em filiais e associadas',
                'subcontas' => [
                    '77.9' => 'Transferência para resultados financeiros',
                ],
            ],
            '78' => [
                'nome' => 'Outros custos e perdas não operacionais',
                'subcontas' => [
                    '78.1' => 'Provisões do exercício',
                    '78.1.1' => 'Existências',
                    '78.1.1.1' => 'Matérias-primas subsidiárias e de consumo',
                    '78.1.1.2' => 'Produtos e trabalhos em curso',
                    '78.1.1.3' => 'Produtos acabados e intermédios',
                    '78.1.1.4' => 'Sub-produtos, desperdícios, resíduos e refugos',
                    '78.1.1.5' => 'Mercadorias',
                    '78.1.2' => 'Cobranças Duvidosas',
                    '78.1.2.1' => 'Clientes',
                    '78.1.2.2' => 'Clientes – títulos a receber',
                    '78.1.2.3' => 'Clientes – cobrança duvidosa',
                    '78.1.2.4' => 'Saldos devedores de fornecedores',
                    '78.1.2.5' => 'Participantes e participadas',
                    '78.1.2.6' => 'Dívidas do pessoal',
                    '78.1.2.9' => 'Outros saldos a receber',
                    '78.1.3' => 'Riscos e encargos',
                    '78.1.3.1' => 'Pensões',
                    '78.1.3.2' => 'Processos judiciais em curso',
                    '78.1.3.3' => 'Acidentes de trabalho',
                    '78.1.3.4' => 'Garantias dadas a clientes',
                    '78.1.3.9' => 'Outros riscos e encargos',
                    '78.2' => 'Amortizações extraordinárias',
                    '78.2.1' => 'Imobilizações Corpóreas',
                    '78.2.2' => 'Imobilizações Incorpóreas',
                    '78.3' => 'Perdas em imobilizações',
                    '78.3.1' => 'Venda de imobilizações corpóreas',
                    '78.3.2' => 'Venda de imobilizações incorpóreas',
                    '78.3.3' => 'Abates',
                    '78.3.9' => 'Outras',
                    '78.4' => 'Perdas em existências',
                    '78.4.1' => 'Quebras',
                    '78.5' => 'Dívidas incobráveis',
                    '78.6' => 'Multas e penalidades contratuais',
                    '78.6.1' => 'Fiscais',
                    '78.6.2' => 'Não fiscais',
                    '78.6.3' => 'Penalidades contratuais',
                    '78.7' => 'Custos de reestruturação',
                    '78.8' => 'Descontinuidade de operações',
                    '78.9' => 'Alterações de políticas contabilísticas',
                    '78.10' => 'Correcções relativas a exercícios anteriores',
                    '78.10.1' => 'Estimativa impostos',
                    '78.11' => 'Outros custos e perdas não operacionais',
                    '78.11.1' => 'Donativos',
                    '78.11.2' => 'Reembolso de subsídios à exploração',
                    '78.11.3' => 'Reembolso de subsídios a investimentos',
                    '78.19' => 'Transferência para resultados não operacionais',
                ],
            ],
            '79' => [
                'nome' => 'Custos e perdas extraordinárias',
                'subcontas' => [
                    '79.1' => 'Perdas resultantes de catástrofes naturais',
                    '79.2' => 'Perdas resultantes de convulsões políticas',
                    '79.3' => 'Perdas resultantes de expropriações',
                    '79.4' => 'Perdas resultantes de sinistros',
                    '79.9' => 'Transferência para resultados extraordinários',
                ],
            ],
        ];

        return  $contas;
    }
    
    public function plano_geral_contas_classe_8()
    {
        $contas = [
            '81' => [
                'nome' => 'Resultados transitados',
                'subcontas' => [
                    '81.1' => 'Ano',
                    '81.1.1' => 'Resultado do ano',
                    '81.1.2' => 'Aplicação de resultados',
                    '81.1.3' => 'Correcções de erros fundamentais, no exercício seguinte',
                    '81.1.4' => 'Efeito das alterações de políticas contabilísticas',
                    '81.1.5' => 'Imposto relativo a correcções de erros fundamentais e alterações de políticas contabilísticas',
                    '81.2' => 'Ano',
                    '81.2.1' => 'Resultado do ano',
                    '81.2.2' => 'Aplicação de resultados',
                    '81.2.3' => 'Correcções de erros fundamentais, no exercício seguinte',
                    '81.2.4' => 'Efeito das alterações de políticas contabilísticas',
                    '81.2.5' => 'Imposto relativo a correcções de erros fundamentais e alterações de políticas contabilísticas',
                ],
            ],
            '82' => [
                'nome' => 'Resultados operacionais',
                'subcontas' => [
                    '82.1' => 'Vendas',
                    '82.2' => 'Prestações de serviço',
                    '82.3' => 'Outros proveitos operacionais',
                    '82.4' => 'Variação nos inventários de produtos acabados e produtos em vias de fabrico',
                    '82.5' => 'Trabalhos para a própria empresa',
                    '82.6' => 'Custo das mercadorias vendidas e das matérias consumidas',
                    '82.7' => 'Custos com o pessoal',
                    '82.8' => 'Amortizações do exercício',
                    '82.9' => 'Outros custos operacionais',
                    '82.19' => 'Transferência para resultados líquidos',
                ]
            ],
            '83' => [
                'nome' => 'Resultados financeiros',
                'subcontas' => [
                    '83.1' => 'Proveitos e ganhos financeiros gerais',
                    '83.2' => 'Custos e perdas financeiros gerais',
                    '83.9' => 'Transferência para resultados líquidos',
                ],
            ],
            '84' => [
                'nome' => 'Resultados em filiais e associadas',
                'subcontas' => [
                    '84.1' => 'Proveitos e ganhos em filiais e associadas',
                    '84.2' => 'Custos e perdas em filiais e associadas',
                    '84.9' => 'Transferência para resultados líquidos',
                ],
            ],
            '85' => [
                'nome' => 'Resultados não operacionais',
                'subcontas' => [
                    '85.1' => 'Proveitos e ganhos não operacionais',
                    '85.2' => 'Custos e perdas não operacionais',
                    '85.9' => 'Transferência para resultados líquidos',
                ],
            ],
            '86' => [
                'nome' => 'Resultados extraordinários',
                'subcontas' => [
                    '86.1' => 'Proveitos e ganhos extraordinários',
                    '86.2' => 'Custos e perdas extraordinários',
                    '86.9' => 'Transferência para resultados líquidos',
                ],
            ],
            '87' => [
                'nome' => 'Imposto sobre os lucros',
                'subcontas' => [
                    '87.1' => 'Imposto sobre os resultados correntes',
                    '87.2' => 'Imposto sobre os resultados extraordinários',
                    '87.9' => 'Transferência para resultados líquidos',
                ],
            ],
            '88' => [
                'nome' => 'Resultado líquido do exercício',
                'subcontas' => [
                    '88.1' => 'Resultados operacionais',
                    '88.2' => 'Resultados financeiros gerais',
                    '88.3' => 'Resultados em filiais e associadas',
                    '88.4' => 'Resultados não operacionais',
                    '88.5' => 'Imposto sobre os resultados correntes',
                    '88.6' => 'Resultados extraordinários',
                    '88.7' => 'Imposto sobre os resultados extraordinários',
                ],
            ],
            '89' => [
                'nome' => 'Dividendos antecipados',
                'subcontas' => [
                    '88.9' => 'Transferência para resultados transitados',
                ],
            ],
        ];

        return  $contas;
    }

    public function plano_geral_contas()
    {
        // Criar contas e subcontas de forma dinâmica
        $contas = [
            '11' => [
                'nome' => 'Imobilizações corpóreas',
                'subcontas' => [
                    '11.1' => 'Terrenos e recursos naturais',
                    '11.1.1' => 'Terrenos em bruto',
                    '11.1.2' => 'Terrenos com arranjos',
                    '11.1.3' => 'Subsolos',
                    '11.1.4' => 'Terrenos com edifícios',
                    '11.1.4.1' => 'Relativos a edifícios industriais',
                    '11.1.4.2' => 'Relativos a edifícios administrativos e comerciais',
                    '11.1.4.3' => 'Relativos a outros edifícios',
                    '11.2' => 'Edifícios e outras construções',
                    '11.2.1' => 'Edifícios',
                    '11.2.1.1' => 'Integrados em conjuntos industriais',
                    '11.2.1.2' => 'Integrados em conjuntos administrativos e comerciais',
                    '11.2.1.3' => 'Outros conjuntos industriais',
                    '11.2.1.4' => 'Implantados em propriedade alheia',
                    '11.2.2' => 'Outras construções',
                    '11.2.3' => 'Instalações',
                    '11.3' => 'Equipamento básico',
                    '11.3.1' => 'Material industria',
                    '11.3.2' => 'Ferramentas industriais',
                    '11.3.3' => 'Melhoramentos em equipamentos básicos',
                    '11.4' => 'Equipamento de carga e transporte',
                    '11.5' => 'Equipamento administrativo',
                    '11.6' => 'Equipamento administrativo',
                    '11.9' => 'Equipamento administrativo',
                ],
            ],
            '12' => [
                'nome' => 'Imobilizações incorpóreas',
                'subcontas' => [
                    '12.1' => 'Trespasses',
                    '12.3' => 'Propriedade industrial e outros direitos e contratos',
                    '12.4' => 'Despesas de constituição',
                    '12.9' => 'Outras imobilizações incorpóreas',
                ],
            ],
            '13' => [
                'nome' => 'Investimentos financeiros',
                'subcontas' => [
                    '13.1' => 'Empresas subsidiárias',
                    '13.1.1' => 'Partes de capital',
                    '13.1.2' => 'Obrigações e títulos de participação',
                    '13.1.3' => 'Empréstimos',
                    '13.2' => 'Empresas associadas',
                    '13.2.1' => 'Partes de capital',
                    '13.2.2' => 'Obrigações e títulos de participação',
                    '13.2.3' => 'Empréstimos',
                    '13.3' => 'Outras empresas',
                    '13.3.1' => 'Partes de capital',
                    '13.3.2' => 'Obrigações e títulos de participação',
                    '13.3.3' => 'Empréstimos',
                    '13.4' => 'Investimentos em imóveis',
                    '13.5' => 'Fundos',
                    '13.9' => 'Outros investimentos Financeiros',
                    '13.9.1' => 'Diamantes',
                    '13.9.2' => 'Ouro',
                    '13.9.3' => 'Depósitos bancários',
                ],
            ],
            '14' => [
                'nome' => 'Imobilizações em curso',
                'subcontas' => [
                    '14.1' => 'Obra em curso',
                    '14.2' => 'Obra em curso',
                    '14.7' => 'Adiantamentos por conta de imobilizado corpóreo',
                    '14.8' => 'Adiantamentos por conta de imobilizado incorpóreo',
                    '14.9' => 'Adiantamentos por conta de investimentos financeiros',
                ],
            ],
            '18' => [
                'nome' => 'Amortizações acumuladas',
                'subcontas' => [
                    '18.1' => 'Imobilizações corpóreas',
                    '18.1.1' => 'Terrenos e recursos naturais',
                    '18.1.2' => 'Edifícios e outras construções',
                    '18.1.3' => 'Equipamento básico',
                    '18.1.4' => 'Equipamento de carga e transporte',
                    '18.1.5' => 'Equipamento administrativo',
                    '18.1.6' => 'Taras e vasilhame',
                    '18.1.9' => 'Outras imobilizações corpóreas',
                    '18.2' => 'Imobilizações incorpóreas',
                    '18.2.1' => 'Trespasses',
                    '18.2.2' => 'Despesas de investigação e desenvolvimento',
                    '18.2.3' => 'Propriedade industrial e outros direitos e contratos',
                    '18.2.4' => 'Despesas de constituição',
                    '18.2.9' => 'Outras imobilizações incorpóreas',
                    '18.3' => 'Investimentos financeiros em imóveis',
                    '18.3.1' => 'Terrenos e recursos naturais',
                    '18.3.2' => 'Edifícios e outras construções',
                ],
            ],
            '19' => [
                'nome' => '',
                'subcontas' => [
                    '19.1' => 'Empresas subsidiárias"',
                    '19.1.1' => 'Partes de capital',
                    '19.1.2' => 'Obrigações e títulos de participação',
                    '19.1.3' => 'Empréstimos',
                    '19.2' => 'Empresas associadas',
                    '19.2.1' => 'Partes de capital',
                    '19.2.2' => 'Obrigações e títulos de participação',
                    '19.2.3' => 'Empréstimos',
                    '19.3' => 'Outras empresas',
                    '19.3.1' => 'Partes de capital',
                    '19.3.2' => 'Obrigações e títulos de participação',
                    '19.3.3' => 'Empréstimos',
                    '19.4' => 'Fundos',
                    '19.4.1' => 'Partes de capital',
                    '19.9' => 'Outros investimentos financeiros',
                    '19.9.1' => 'Diamantes',
                    '19.9.2' => 'Ouro',
                    '19.9.3' => 'Depósitos bancários',
                ],
            ],

            '21' => [
                'nome' => 'Compras',
                'subcontas' => [
                    '21.1' => 'Matérias-primas, subsidiárias e de consumo',
                    '21.2' => 'Mercadorias',
                    '21.7' => 'Devoluções de compras',
                    '21.8' => 'Descontos e abatimentos em compras',
                ],
            ],

            '22' => [
                'nome' => 'Matérias-primas, subsidiárias e de consumo',
                'subcontas' => [
                    '22.1' => 'Matérias-primas',
                    '22.2' => 'Matérias subsidiárias',
                    '22.3' => 'Materiais diversos',
                    '22.4' => 'Embalagens de consumo',
                    '22.5' => 'Outros materiais',
                ],
            ],
            '23' => [
                'nome' => 'Produtos e trabalhos em curso',
                'subcontas' => [],
            ],
            '24' => [
                'nome' => 'Produtos acabados e intermédios',
                'subcontas' => [
                    '24.1' => 'Produtos acabados',
                    '24.2' => 'Produtos intermédios',
                    '24.9' => 'Em poder de terceiros',
                ],
            ],
            '25' => [
                'nome' => 'Sub-produtos, desperdícios, resíduos e refugos',
                'subcontas' => [
                    '25.1' => 'Sub-produtos',
                    '25.2' => 'Desperdícios, resíduos e refugos',
                ],
            ],
            '26' => [
                'nome' => 'Mercadorias',
                'subcontas' => [
                    '26.9' => 'Em poder de terceiros',
                ],
            ],
            '27' => [
                'nome' => 'Matérias-primas, mercadorias e outros materiais em trânsito',
                'subcontas' => [
                    '27.1' => 'Matérias-primas',
                    '27.2' => 'Outros materiais',
                    '27.3' => 'Mercadorias',
                ],
            ],
            '28' => [
                'nome' => 'Adiantamentos por conta de compras',
                'subcontas' => [
                    '28.1' => 'Matérias-primas e outros materiais',
                    '28.2' => 'Mercadorias',
                ],
            ],
            '29' => [
                'nome' => 'Provisão para depreciação de existências',
                'subcontas' => [
                    '29.2' => 'Matérias-primas subsidiárias e de consumo',
                    '29.3' => 'Produtos e trabalhos em curso',
                    '29.4' => 'Produtos acabados e intermédios',
                    '29.5' => 'Sub-produtos, desperdícios, resíduos e refugos',
                    '29.6' => 'Mercadorias',
                ],
            ],

            '31' => [
                'nome' => 'Clientes',
                'subcontas' => [
                    '31.1' => 'Clientes – correntes',
                    '31.1.1' => 'Grupo',
                    '31.1.2' => 'Não grupo',
                    '31.2' => 'Clientes – títulos a receber',
                    '31.2.1' => 'Grupo',
                    '31.2.2' => 'Não grupo',
                    '31.3' => 'Clientes – títulos descontados',
                    '31.3.1' => 'Grupo',
                    '31.3.2' => 'Não grupo',
                    '31.8' => 'Clientes de cobrança duvidosa',
                    '31.8.1' => 'Clientes – correntes',
                    '31.8.2' => 'Clientes – títulos',
                    '31.9' => 'Clientes - saldos credores',
                    '31.9.1' => 'Adiantamento',
                    '31.9.2' => 'Embalagens a devolver',
                    '31.9.3' => 'Material à consignação',
                ],
            ],

            '32' => [
                'nome' => 'Fornecedores',
                'subcontas' => [
                    '32.1' => 'Fornecedores – correntes',
                    '32.1.1' => 'Grupo',
                    '32.1.2' => 'Não grupo',
                    '32.1.2.1' => 'Nacionais',
                    '32.1.2.2' => 'Estrangeiros',
                    '32.2' => 'Fornecedores – títulos a pagar',
                    '32.2.1' => 'Grupo',
                    '32.2.1.1' => 'Subsidiárias',
                    '32.2.1.2' => 'Associadas',
                    '32.2.2' => 'Associadas',
                    '32.2.2.1' => 'Nacionais',
                    '32.2.2.2' => 'Estrangeiros',
                    '32.8' => 'Fornecedores – facturas em recepção e conferência',
                    '32.9' => 'Fornecedores – saldos devedores',
                ],
            ],

            '33' => [
                'nome' => 'Empréstimos',
                'subcontas' => [
                    '33.1' => 'Empréstimos bancários',
                    '33.1.1' => 'Moeda nacional',
                    '33.1.2' => 'Moeda estrangeira',
                    '33.2' => 'Empréstimos por obrigações',
                    '33.3' => 'Empréstimos por títulos de participação',
                    '33.9' => 'Outros empréstimos obtidos',
                ],
            ],

            '34' => [
                'nome' => 'Estado',
                'subcontas' => [
                    '34.1' => 'Imposto sobre os lucros',
                    '34.2' => 'Imposto de produção e consumo',
                    '34.3' => 'Imposto de rendimento de trabalho',
                    '34.4' => 'Imposto de circulação',
                    '34.5' => 'IVA',
                    '34.5.1' => 'IVA suportado:',
                    '34.5.1.1' => 'Existências',
                    '34.5.1.2' => 'Meios fixos e investimentos',
                    '34.5.1.3' => 'Outros bens e serviço',
                    '34.5.2' => 'IVA dedutível',
                    '34.5.2.1' => 'Existências',
                    '34.5.2.2' => 'Meios fixos e investimentos',
                    '34.5.2.3' => 'Outros bens e serviços',
                    '34.5.3' => 'IVA liquidado',
                    '34.5.3.1' => 'Operações gerais',
                    '34.5.3.2' => 'Operações abrangidas pelo regime de IVA de caixa',
                    '34.5.3.3' => 'Autoconsumo e operações gratuitas',
                    '34.5.3.4' => 'Operações especiais',
                    '34.5.4' => 'IVA regularizações',
                    '34.5.4.1' => 'Mensais a favor do sujeito passivo',
                    '34.5.4.2' => 'Mensais a favor do Estado',
                    '34.5.4.3' => 'Anual por cálculo do pró rata definitivo',
                    '34.5.4.4' => 'Outras regularizações anuais',
                    '34.5.5' => 'IVA apuramento',
                    '34.5.5.1' => 'Apuramento do regime de IVA normal',
                    '34.5.5.2' => 'Apuramento do regime de IVA de caixa',
                    '34.5.6' => 'IVA a pagar',
                    '34.5.6.1' => 'IVA a pagar de apuramento',
                    '34.5.6.2' => 'IVA a pagar de cativo',
                    '34.5.6.3' => 'IVA a pagar de liquidações oficiosas',
                    '34.5.7' => 'IVA a recuperar',
                    '34.5.7.1' => 'IVA a recuperar de apuramentos',
                    '34.5.7.2' => 'IVA a recuperar de cativo',
                    '34.5.8' => 'IVA reembolsos pedidos',
                    '34.5.8.1' => 'Reembolsos pedidos',
                    '34.5.8.2' => 'Reembolsos deferidos',
                    '34.5.8.3' => 'Reembolsos indeferidos',
                    '34.5.8.4' => 'Reembolsos reclamados, recorridos ou impugnados',
                    '34.5.9' => 'IVA Liquidações oficiosas',
                    '34.6' => 'Certificado de crédito fiscal a compensar',
                    '34.8' => 'Subsídios a preços',
                    '34.9' => 'Outros impostos',
                ],
            ],

            '35' => [
                'nome' => 'Entidades participantes e participadas',
                'subcontas' => [
                    '35.1' => 'Entidades participantes',
                    '35.1.1' => 'Estado',
                    '35.1.1.1' => 'c/subscrição',
                    '35.1.1.2' => 'c/adiantamentos sobre lucros',
                    '35.1.1.3' => 'c/lucros',
                    '35.1.1.4' => 'Empréstimos',
                    '35.1.2' => 'Empresas do grupo – subsidiárias',
                    '35.1.2.1' => 'c/subscrição',
                    '35.1.2.2' => 'c/adiantamentos sobre lucro',
                    '35.1.2.3' => 'c/lucros',
                    '35.1.2.4' => 'Empréstimos',
                    '35.1.3' => 'Empresas do grupo – associadas',
                    '35.1.3.1' => 'c/subscrição',
                    '35.1.3.2' => 'c/adiantamentos sobre lucros',
                    '35.1.3.3' => 'c/lucros',
                    '35.1.3.4' => 'Empréstimos',
                    '35.1.4' => 'Outros',
                    '35.1.4.1' => 'c/subscrição',
                    '35.1.4.2' => 'c/adiantamentos sobre lucros',
                    '35.1.4.3' => 'c/lucros',
                    '35.1.4.4' => 'Empréstimos',
                    '35.2' => 'Entidades participadas',
                    '35.2.1' => 'Estado',
                    '35.2.1.1' => 'c/subscrição',
                    '35.2.1.2' => 'c/adiantamentos sobre lucros',
                    '35.2.1.3' => 'c/lucros',
                    '35.2.1.4' => 'Empréstimos',
                    '35.2.2' => 'Empresas do grupo – subsidiárias',
                    '35.2.2.1' => 'c/subscrição',
                    '35.2.2.2' => 'c/adiantamentos sobre lucros',
                    '35.2.2.3' => 'c/lucros',
                    '35.2.2.4' => 'Empréstimos',
                    '35.2.3' => 'Empresas do grupo – associadas',
                    '35.2.3.1' => 'c/subscrição',
                    '35.2.3.2' => 'c/adiantamentos sobre lucros',
                    '35.2.3.3' => 'c/lucros',
                    '35.2.3.4' => 'Empréstimos',
                    '35.2.4' => 'Outros',
                    '35.2.4.1' => 'c/subscrição',
                    '35.2.4.2' => 'c/adiantamentos sobre lucros',
                    '35.2.4.3' => 'c/lucros',
                    '35.2.4.4' => 'Empréstimos',
                ],
            ],
            '36' => [
                'nome' => 'Pessoal',
                'subcontas' => [],
            ],

            '37' => [
                'nome' => 'Outros valores a receber e a pagar',
                'subcontas' => [
                    '37.1' => 'Compras de imobilizado',
                    '37.1.1' => 'Corpóreo',
                    '37.1.2' => 'Incorpóreo',
                    '37.1.3' => 'Financeiro',
                    '37.2' => 'Vendas de imobilizado',
                    '37.2.1' => 'Corpóreo',
                    '37.2.2' => 'Incorpóreo',
                    '37.2.3' => 'Financeiro',
                    '37.3' => 'Proveitos a facturar',
                    '37.3.1' => 'Vendas',
                    '37.3.2' => 'Prestações de serviço',
                    '37.3.3' => 'Juros',
                    '37.4' => 'Encargos a repartir por períodos futuros',
                    '37.4.1' => 'Descontos de emissão de obrigações',
                    '37.4.2' => 'Descontos de emissão de títulos de participação',
                    '37.5' => 'Encargos a pagar',
                    '37.5.1' => 'Remunerações',
                    '37.5.2' => 'Juros',
                    '37.6' => 'Proveitos a repartir por períodos futuros',
                    '37.6.1' => 'Prémios de emissão de obrigações',
                    '37.6.2' => 'Prémios de emissão de títulos de participação',
                    '37.6.3' => 'Subsídios para investimento',
                    '37.6.4' => 'Diferenças de câmbio favoráveis reversíveis',
                    '37.7' => 'Contas transitórias',
                    '37.7.1' => 'Transacções entre a sede e as dependências da empresa',
                    '37.9' => 'Outros valores a receber e a pagar',
                    '37.9.1' => 'Credores Diversos',
                ],
            ],
            '38' => [
                'nome' => 'Provisões para cobranças duvidosas',
                'subcontas' => [
                    '38.1' => 'Provisões para clientes',
                    '38.1.1' => 'Clientes – corrente',
                ],
            ],
            '39' => [
                'nome' => 'Provisões para outros riscos e encargos',
                'subcontas' => [
                    '39.1' => 'Provisões para pensões',
                    '39.2' => 'Provisões para processos judiciais em curso',
                    '39.3' => 'Provisões para acidentes de trabalho',
                    '39.4' => 'Provisões para garantias dadas a clientes',
                    '39.9' => 'Provisões para outros riscos e encargos',
                ],
            ],

            '41' => [
                'nome' => 'Títulos negociáveis',
                'subcontas' => [
                    '41.1' => 'Acções',
                    '41.1.1' => 'Empresas do grupo',
                    '41.1.2' => 'Associadas',
                    '41.1.3' => 'Outras empresas',
                    '41.2' => 'Obrigações',
                    '41.2.1' => 'Empresas do grupo',
                    '41.2.2' => 'Associadas',
                    '41.2.3' => 'Outras empresas',
                    '41.3' => 'Títulos da dívida pública',
                ],
            ],
            '42' => [
                'nome' => 'Depósitos a prazo',
                'subcontas' => [
                    '42.1' => 'Moeda nacional',
                    '42.2' => 'Moeda estrangeira',
                ]
            ],
            '43' => [
                'nome' => 'Depósitos à ordém',
                'subcontas' => [
                    '43.1' => 'Moeda nacional',
                    '43.2' => 'Moeda estrangeira',
                ]
            ],
            '44' => [
                'nome' => 'Outros depósitos',
                'subcontas' => [
                    '44.1' => 'Moeda nacional',
                    '44.2' => 'Moeda estrangeira',
                ]
            ],
            '45' => [
                'nome' => 'Caixa',
                'subcontas' => [
                    '45.1' => 'Fundo fixo',
                    '45.2' => 'Valores para depositar',
                    '45.3' => 'Valores destinados a pagamentos específicos',
                ]
            ],
            '48' => [
                'nome' => 'Conta transitória',
                'subcontas' => [],
            ],
            '49' => [
                'nome' => 'Provisões para aplicações de tesouraria',
                'subcontas' => [
                    '49.1' => 'Títulos negociáveis',
                    '49.1.1' => 'Acçõe',
                    '49.1.2' => 'Obrigações',
                    '49.1.3' => 'Títulos da dívida pública',
                    '49.2' => 'Outras aplicações de tesouraria',
                ]
            ],


            '51' => [
                'nome' => 'Capital',
                'subcontas' => [
                    '51.1' => 'Capital',
                ],
            ],
            '52' => [
                'nome' => 'Acções/quotas próprias',
                'subcontas' => [
                    '52.1' => 'Valor nomina',
                    '52.2' => 'Descontos',
                    '52.3' => 'Prémios',
                ],
            ],
            '53' => [
                'nome' => 'Prémios de emissão',
                'subcontas' => [],
            ],
            '54' => [
                'nome' => 'Prestações suplementares',
                'subcontas' => [],
            ],
            '55' => [
                'nome' => 'Reservas legais',
                'subcontas' => [],
            ],
            '56' => [
                'nome' => 'Reservas de reavaliação',
                'subcontas' => [
                    '56.1' => 'Legais',
                    '56.1.1' => 'Decreto-Lei n.º ___',
                    '56.1.2' => 'Decreto-Lei n.º ___',
                    '56.2' => 'Autónomas',
                    '56.2.1' => 'Avaliação',
                ],
            ],
            '57' => [
                'nome' => 'Reservas com fins especiais',
                'subcontas' => [
                    '57.1' => 'Avaliação',
                ],
            ],


            '61' => [
                'nome' => 'Vendas',
                'subcontas' => [
                    '61.1' => 'Produtos acabados e intermédios',
                    '61.1.1' => 'Mercado nacional',
                    '61.1.2' => 'Mercado estrangeiro',
                    '61.2' => 'Sub-produtos, desperdícios',
                    '61.2.1' => 'Mercado nacional',
                    '61.2.2' => 'Mercado estrangeiro',
                    '61.3' => 'Mercadorias',
                    '61.3.1' => 'Mercado nacional',
                    '61.3.2' => 'Mercado estrangeiro',
                    '61.4' => 'Embalagens de consumo',
                    '61.4.1' => 'Mercado nacional',
                    '61.4.2' => 'Mercado estrangeiro',
                    '61.5' => 'Subsídios a preços',
                    '61.7' => 'Devoluções',
                    '61.7.1' => 'Mercado nacional',
                    '61.7.2' => 'Mercado estrangeiro',
                    '61.8' => 'Descontos e abatimento',
                    '61.8.1' => 'Mercado nacional',
                    '61.8.2' => 'Mercado estrangeiro',
                    '61.9' => 'Transferência para resultados operacionais',
                ],
            ],
            '62' => [
                'nome' => 'Prestações de serviços',
                'subcontas' => [
                    '62.1' => 'Serviços principais',
                    '62.1.1' => 'Mercado nacional',
                    '62.1.2' => 'Mercado estrangeiro',
                    '62.2' => 'Serviços secundários',
                    '62.2.1' => 'Mercado nacional"',
                    '62.2.2' => 'Mercado estrangeiro',
                    '62.8' => 'Descontos e abatimentos',
                    '62.8.1' => 'Mercado nacional',
                    '62.8.2' => 'Mercado estrangeiro',
                    '62.9' => 'Mercado estrangeiro',
                ],
            ],
            '63' => [
                'nome' => 'Outros proveitos operacionais',
                'subcontas' => [
                    '63.1' => 'Serviços suplementares',
                    '63.1.1' => 'Aluguer de equipamento',
                    '63.1.2' => 'Cedência de pessoal',
                    '63.1.3' => 'Cedência de energia',
                    '63.1.4' => 'Estudos, projectos e assistência técnica',
                    '63.2' => 'Royalties',
                    '63.3' => 'Subsídios à exploração',
                    '63.4' => 'Subsídios a investimento',
                    '63.5' => 'IVA',
                    '63.8' => 'Outros proveitos e ganhos operacionais',
                ],
            ],
            '64' => [
                'nome' => 'Variação nos inventários de produtos acabados e de produção em curso',
                'subcontas' => [
                    '64.1' => 'Produtos e trabalhos em curso',
                    '64.2' => 'Produtos acabados',
                    '64.3' => 'Produtos intermédios',
                ]
            ],
            '65' => [
                'nome' => 'Trabalhos para a própria empresa',
                'subcontas' => [
                    '65.1' => 'Para imobilizado',
                    '65.1.1' => 'Corpóreo',
                    '65.1.2' => 'Incorpóreo',
                    '65.1.3' => 'Financeiro',
                    '65.1.4' => 'Em curso',
                    '65.2' => 'Para encargos a repartir por exercícios futuros',
                    '65.9' => 'Transferência para resultados operacionais',
                ]
            ],
            '66' => [
                'nome' => 'Proveitos e ganhos financeiros gerais',
                'subcontas' => [
                    '66.1' => 'Juros',
                    '66.1.1' => 'De investimentos financeiros',
                    '66.1.1.1' => 'Obrigações',
                    '66.1.1.3' => 'Títulos de participação',
                    '66.1.1.4' => 'Empréstimos',
                    '66.1.1.9' => 'Outros',
                    '66.1.2' => 'De mora relativos a dívidas de terceiros',
                    '66.1.2.1' => 'Dívidas recebidas a prestações',
                    '66.1.2.2' => 'De empréstimos a terceiros',
                    '66.1.4' => 'Desconto de títulos',
                    '66.1.5' => 'De aplicações de tesouraria',
                    '66.2' => 'Diferenças de câmbio favoráveis',
                    '66.2.1' => 'Realizadas',
                    '66.2.2' => 'Não realizadas',
                    '66.3' => 'Descontos de pronto pagamento obtidos',
                    '66.4' => 'Rendimentos de investimentos em imóveis',
                    '66.5' => 'Rendimento de participações de capital',
                    '66.5.1' => 'Acções, quotas em outras empresas',
                    '66.5.2' => 'Acções, quotas incluídas nos fundos',
                    '66.5.3' => 'Acções, quotas incluídas nos títulos negociáveis',
                    '66.6' => 'Ganhos na alienação de aplicações financeiras',
                    '66.6.1' => 'Investimentos financeiros',
                    '66.6.1.1' => 'Subsidiárias',
                    '66.6.1.2' => 'Associadas',
                    '66.6.1.3' => 'Outras empresas',
                    '66.6.1.4' => 'Imóveis',
                    '66.6.1.5' => 'Fundos',
                    '66.6.1.9' => 'Outros investimentos',
                    '66.6.2' => 'Títulos negociáveis',
                    '66.7' => 'Reposição de provisões',
                    '66.7.1' => 'Investimentos financeiros',
                    '66.7.1.1' => 'Subsidiárias',
                    '66.7.1.2' => 'Associadas',
                    '66.7.1.3' => 'Outras empresas',
                    '66.7.1.4' => 'Fundos',
                    '66.7.1.9' => 'Outros investimentos',
                    '66.7.2' => 'Aplicações de tesouraria',
                    '66.7.2.1' => 'Títulos negociáveis',
                    '66.7.2.2' => 'Depósitos a prazo',
                    '66.7.2.3' => 'Outros depósitos',
                    '66.7.2.9' => 'Outros investimentos',
                    '66.9' => 'Transferência para resultados financeiros',
                ],
            ],
            '67' => [
                'nome' => 'Proveitos e ganhos financeiros em filiais e associadas',
                'subcontas' => [
                    '67.1' => 'Rendimento de participações de capital',
                    '67.1.1' => 'Subsidiárias',
                    '67.1.2' => 'Associadas',
                    '67.9' => 'Transferência para resultados em filiais e associadas',
                ]
            ],
            '68' => [
                'nome' => 'Outros proveitos não operacionais',
                'subcontas' => [
                    '68.1.1' => 'Existências',
                    '68.1.1.1' => 'Matérias-primas subsidiárias e de consumo',
                    '68.1.1.2' => 'Produtos e trabalhos em curso',
                    '68.1.1.3' => 'Produtos acabados e intermédios',
                    '68.1.1.4' => 'Sub-produtos',
                    '68.1.1.5' => 'Mercadorias',
                    '68.1.2' => 'Cobranças duvidosas',
                    '68.1.2.1' => 'Clientes',
                    '68.1.2.2' => 'Clientes – títulos a receber',
                    '68.1.2.3' => 'Clientes – cobrança duvidosa',
                    '68.1.2.4' => 'Saldos devedores de fornecedores',
                    '68.1.2.5' => 'Participantes e participadas',
                    '68.1.2.6' => 'Dívidas do Pessoal',
                    '68.1.2.9' => 'Outros saldos a receber',
                    '68.1.3' => 'Riscos e encargos',
                    '68.1.3.1' => 'Pensões',
                    '68.1.3.2' => 'Processos judiciais em curso',
                    '68.1.3.3' => 'Acidentes de trabalho',
                    '68.1.3.4' => 'Garantias dadas a clientes',
                    '68.1.3.9' => 'Outros riscos e encargos',
                    '68.10' => 'Correcções relativas a exercícios anteriores',
                    '68.10.1' => 'Estimativa impostos',
                    '68.10.2' => 'Restituição de impostos',
                    '68.11' => 'Outros ganhos e perdas não operacionais',
                    '68.11.1' => 'Donativos',
                    '68.19' => 'Transferência para resultados não operacionais',
                    '68.2' => 'Anulação de amortizações extraordinárias',
                    '68.2.1' => 'Imobilizações corpóreas',
                    '68.2.2' => 'Imobilizações incorpóreas',
                    '68.3' => 'Ganhos em imobilizações',
                    '68.3.1' => 'Venda de imobilizações corpóreas',
                    '68.3.2' => 'Venda de imobilizações incorpóreas',
                    '68.4' => 'Ganhos em existências',
                    '68.4.1' => 'Sobras',
                    '68.5' => 'Recuperação de dívidas',
                    '68.6' => 'Benefícios de penalidades contratuais',
                    '68.8' => 'Descontinuidade de operações',
                    '68.9' => 'Alterações de políticas contabilísticas',
                ],
            ],
            '69' => [
                'nome' => 'Proveitos e ganhos extraordinários',
                'subcontas' => [
                    '69.1' => 'Ganhos resultantes de catástrofes naturais',
                    '69.2' => 'Ganhos resultantes de convulsões políticas',
                    '69.3' => 'Ganhos resultantes de expropriações',
                    '69.4' => 'Ganhos resultantes de sinistros',
                    '69.5' => 'Subsídios',
                    '69.6' => 'Anulação de passivos não exigíveis',
                    '69.9' => 'Transferência para resultados extraordinários',
                ],
            ],

            '71' => [
                'nome' => 'Custo das mercadorias vendidas e das matérias consumidas',
                'subcontas' => [
                    '71.1' => 'Matérias-primas',
                    '71.2' => 'Matérias subsidiárias',
                    '71.3' => 'Materiais diversos',
                    '71.4' => 'Embalagens de consumo',
                    '71.5' => 'Outros materiais',
                    '71.6' => 'Custos de Mercadorias Vendidas',
                    '71.9' => 'Transferência para resultados operacionais',
                ],
            ],
            '72' => [
                'nome' => 'Custos com o pessoal',
                'subcontas' => [
                    '72.1' => 'Remunerações – Órgãos sociais',
                    '72.2' => 'Remunerações – Pessoal',
                    '72.3' => 'Pensões',
                    '72.3.1' => 'Órgãos sociais',
                    '72.3.2' => 'Pessoal',
                    '72.4' => 'Prémios para pensões',
                    '72.4.1' => 'Órgãos sociais',
                    '72.4.2' => 'Pessoal',
                    '72.5' => 'Encargos sobre remunerações',
                    '72.5.1' => 'Órgãos sociais',
                    '72.5.2' => 'Pessoal',
                ],
            ],
            '73' => [
                'nome' => 'Amortizações do exercício',
                'subcontas' => [
                    '73.1' => 'Imobilizações corpóreas',
                    '73.1.2' => 'Edifícios e outras construções',
                    '73.1.3' => 'Equipamento básico',
                    '73.1.4' => 'Equipamento de carga e transporte',
                    '73.1.5' => 'Equipamento administrativo',
                    '73.1.6' => 'Taras e vasilhame',
                    '73.1.9' => 'Outras imobilizações corpóreas',
                    '73.2' => 'Imobilizações incorpóreas',
                    '73.2.1' => 'Trespasses',
                    '73.2.2' => 'Despesas de investigação e desenvolvimento',
                    '73.2.3' => 'Propriedade industrial e outros direitos e contratos',
                    '73.2.4' => 'Despesas de constituição',
                    '73.2.9' => 'Outras imobilizações incorpóreas',
                    '73.9' => 'Transferência para resultados operacionais',
                ],
            ],
            '75' => [
                'nome' => 'Outros custos e perdas operacionais',
                'subcontas' => [
                    '75.1' => 'Sub-contratos',
                    '75.2' => 'Fomecimentos e serviços de terceiros',
                    '75.2.11' => 'Água',
                    '75.2.12' => 'Electricidade',
                    '75.2.13' => 'Combustíveis e outros fluídos',
                    '75.2.14' => 'Conservação e reparação',
                    '75.2.15' => 'Material de protecção segurança e conforto',
                    '75.2.16' => 'Ferramentas e utensílios de desgaste rápido',
                    '75.2.17' => 'Material de escritório',
                    '75.2.18' => 'Livros e documentação técnica',
                    '75.2.19' => 'Outros fornecimentos',
                    '75.2.20' => 'Comunicação',
                    '75.2.21' => 'Rendas e alugueres',
                    '75.2.22' => 'Seguros',
                    '75.2.23' => 'Deslocações e estadas',
                    '75.2.24' => 'Despesas de representação',
                    '75.2.26' => 'Conservação e reparação',
                    '75.2.27' => 'Vigilância e segurança',
                    '75.2.28' => 'Limpeza, higiene e conforto',
                    '75.2.29' => 'Publicidade e propaganda',
                    '75.2.30' => 'Contencioso e notariado',
                    '75.2.31' => 'Comissões a intermediários',
                    '75.2.32' => 'Assistência técnica',
                    '75.2.32.1' => 'Estrangeira',
                    '75.2.32.2' => 'Nacional',
                    '75.2.33' => 'Trabalhos executados no exterior',
                    '75.2.34' => 'Honorários e avenças',
                    '75.2.35' => 'Royalties',
                    '75.2.39' => 'Outros serviços',
                    '75.3' => 'Impostos',
                    '75.3.1' => 'Indirectos',
                    '75.3.1.1' => 'Imposto de selo',
                    '75.3.1.2' => 'IVA',
                    '75.3.1.9' => 'Outros impostos',
                    '75.3.2' => 'Directos',
                    '75.3.2.1' => 'Imposto de capitais',
                    '75.3.2.2' => 'Contribuição predial',
                    '75.3.2.9' => 'Outros impostos',
                    '75.4' => 'Despesas confidênciais',
                    '75.5' => 'Quotizações',
                    '75.6' => 'Ofertas e Amostras de existências',
                    '75.8' => 'Outros custos e perdas operacionais',
                    '75.9' => 'Transferências para resultados operacionais',
                ],
            ],
            '76' => [
                'nome' => 'Custos e perdas financeiros gerais',
                'subcontas' => [
                    '76.1' => 'Juros',
                    '76.1.1' => 'De empréstimos',
                    '76.1.1.1' => 'Bancários',
                    '76.1.1.2' => 'Obrigações',
                    '76.1.1.3' => 'Títulos de participação',
                    '76.1.2' => 'De descobertos bancários',
                    '76.1.3' => 'De mora relativos a dívidas a terceiros',
                    '76.1.4' => 'De desconto de títulos',
                    '76.2' => 'Diferenças de câmbio desfavoráveis',
                    '76.2.1' => 'Realizadas',
                    '76.3' => 'Descontos de pronto pagamento concedidos',
                    '76.4' => 'Amortizações de investimentos em imóveis',
                    '76.5' => 'Provisões para aplicações financeiras',
                    '76.5.1' => 'Investimentos financeiros',
                    '76.5.1.1' => 'Subsidiárias',
                    '76.5.1.2' => 'Associadas',
                    '76.5.1.3' => 'Outras empresas',
                    '76.5.1.4' => 'Fundos',
                    '76.5.1.9' => 'Outros investimentos',
                    '76.5.2' => 'Aplicações de tesouraria',
                    '76.5.2.1' => 'Títulos negociáveis',
                    '76.5.2.2' => 'Depósitos a prazo',
                    '76.5.2.3' => 'Outros depósitos',
                    '76.5.2.9' => 'Outros',
                    '76.6' => 'Perdas na alienação de aplicações financeiras',
                    '76.6.1' => 'Investimentos financeiros',
                    '76.6.1.1' => 'Subsidiárias',
                    '76.6.1.2' => 'Associadas',
                    '76.6.1.3' => 'Outras empresas',
                    '76.6.1.9' => 'Outros investimentos',
                    '76.6.2' => 'Aplicações de títulos negociáveis',
                    '76.7' => 'Serviços bancários',
                    '76.9' => 'Transferência para resultados financeiros',
                ],
            ],
            '77' => [
                'nome' => 'Custos e perdas financeiros em filiais e associadas',
                'subcontas' => [
                    '77.9' => 'Transferência para resultados financeiros',
                ],
            ],
            '78' => [
                'nome' => 'Outros custos e perdas não operacionais',
                'subcontas' => [
                    '78.1' => 'Provisões do exercício',
                    '78.1.1' => 'Existências',
                    '78.1.1.1' => 'Matérias-primas subsidiárias e de consumo',
                    '78.1.1.2' => 'Produtos e trabalhos em curso',
                    '78.1.1.3' => 'Produtos acabados e intermédios',
                    '78.1.1.4' => 'Sub-produtos, desperdícios, resíduos e refugos',
                    '78.1.1.5' => 'Mercadorias',
                    '78.1.2' => 'Cobranças Duvidosas',
                    '78.1.2.1' => 'Clientes',
                    '78.1.2.2' => 'Clientes – títulos a receber',
                    '78.1.2.3' => 'Clientes – cobrança duvidosa',
                    '78.1.2.4' => 'Saldos devedores de fornecedores',
                    '78.1.2.5' => 'Participantes e participadas',
                    '78.1.2.6' => 'Dívidas do pessoal',
                    '78.1.2.9' => 'Outros saldos a receber',
                    '78.1.3' => 'Riscos e encargos',
                    '78.1.3.1' => 'Pensões',
                    '78.1.3.2' => 'Processos judiciais em curso',
                    '78.1.3.3' => 'Acidentes de trabalho',
                    '78.1.3.4' => 'Garantias dadas a clientes',
                    '78.1.3.9' => 'Outros riscos e encargos',
                    '78.2' => 'Amortizações extraordinárias',
                    '78.2.1' => 'Imobilizações Corpóreas',
                    '78.2.2' => 'Imobilizações Incorpóreas',
                    '78.3' => 'Perdas em imobilizações',
                    '78.3.1' => 'Venda de imobilizações corpóreas',
                    '78.3.2' => 'Venda de imobilizações incorpóreas',
                    '78.3.3' => 'Abates',
                    '78.3.9' => 'Outras',
                    '78.4' => 'Perdas em existências',
                    '78.4.1' => 'Quebras',
                    '78.5' => 'Dívidas incobráveis',
                    '78.6' => 'Multas e penalidades contratuais',
                    '78.6.1' => 'Fiscais',
                    '78.6.2' => 'Não fiscais',
                    '78.6.3' => 'Penalidades contratuais',
                    '78.7' => 'Custos de reestruturação',
                    '78.8' => 'Descontinuidade de operações',
                    '78.9' => 'Alterações de políticas contabilísticas',
                    '78.10' => 'Correcções relativas a exercícios anteriores',
                    '78.10.1' => 'Estimativa impostos',
                    '78.11' => 'Outros custos e perdas não operacionais',
                    '78.11.1' => 'Donativos',
                    '78.11.2' => 'Reembolso de subsídios à exploração',
                    '78.11.3' => 'Reembolso de subsídios a investimentos',
                    '78.19' => 'Transferência para resultados não operacionais',
                ],
            ],
            '79' => [
                'nome' => 'Custos e perdas extraordinárias',
                'subcontas' => [
                    '79.1' => 'Perdas resultantes de catástrofes naturais',
                    '79.2' => 'Perdas resultantes de convulsões políticas',
                    '79.3' => 'Perdas resultantes de expropriações',
                    '79.4' => 'Perdas resultantes de sinistros',
                    '79.9' => 'Transferência para resultados extraordinários',
                ],
            ],

            '81' => [
                'nome' => 'Resultados transitados',
                'subcontas' => [
                    '81.1' => 'Ano',
                    '81.1.1' => 'Resultado do ano',
                    '81.1.2' => 'Aplicação de resultados',
                    '81.1.3' => 'Correcções de erros fundamentais, no exercício seguinte',
                    '81.1.4' => 'Efeito das alterações de políticas contabilísticas',
                    '81.1.5' => 'Imposto relativo a correcções de erros fundamentais e alterações de políticas contabilísticas',
                    '81.2' => 'Ano',
                    '81.2.1' => 'Resultado do ano',
                    '81.2.2' => 'Aplicação de resultados',
                    '81.2.3' => 'Correcções de erros fundamentais, no exercício seguinte',
                    '81.2.4' => 'Efeito das alterações de políticas contabilísticas',
                    '81.2.5' => 'Imposto relativo a correcções de erros fundamentais e alterações de políticas contabilísticas',
                ],
            ],
            '82' => [
                'nome' => 'Resultados operacionais',
                'subcontas' => [
                    '82.1' => 'Vendas',
                    '82.2' => 'Prestações de serviço',
                    '82.3' => 'Outros proveitos operacionais',
                    '82.4' => 'Variação nos inventários de produtos acabados e produtos em vias de fabrico',
                    '82.5' => 'Trabalhos para a própria empresa',
                    '82.6' => 'Custo das mercadorias vendidas e das matérias consumidas',
                    '82.7' => 'Custos com o pessoal',
                    '82.8' => 'Amortizações do exercício',
                    '82.9' => 'Outros custos operacionais',
                    '82.19' => 'Transferência para resultados líquidos',
                ]
            ],
            '83' => [
                'nome' => 'Resultados financeiros',
                'subcontas' => [
                    '83.1' => 'Proveitos e ganhos financeiros gerais',
                    '83.2' => 'Custos e perdas financeiros gerais',
                    '83.9' => 'Transferência para resultados líquidos',
                ],
            ],
            '84' => [
                'nome' => 'Resultados em filiais e associadas',
                'subcontas' => [
                    '84.1' => 'Proveitos e ganhos em filiais e associadas',
                    '84.2' => 'Custos e perdas em filiais e associadas',
                    '84.9' => 'Transferência para resultados líquidos',
                ],
            ],
            '85' => [
                'nome' => 'Resultados não operacionais',
                'subcontas' => [
                    '85.1' => 'Proveitos e ganhos não operacionais',
                    '85.2' => 'Custos e perdas não operacionais',
                    '85.9' => 'Transferência para resultados líquidos',
                ],
            ],
            '86' => [
                'nome' => 'Resultados extraordinários',
                'subcontas' => [
                    '86.1' => 'Proveitos e ganhos extraordinários',
                    '86.2' => 'Custos e perdas extraordinários',
                    '86.9' => 'Transferência para resultados líquidos',
                ],
            ],
            '87' => [
                'nome' => 'Imposto sobre os lucros',
                'subcontas' => [
                    '87.1' => 'Imposto sobre os resultados correntes',
                    '87.2' => 'Imposto sobre os resultados extraordinários',
                    '87.9' => 'Transferência para resultados líquidos',
                ],
            ],
            '88' => [
                'nome' => 'Resultado líquido do exercício',
                'subcontas' => [
                    '88.1' => 'Resultados operacionais',
                    '88.2' => 'Resultados financeiros gerais',
                    '88.3' => 'Resultados em filiais e associadas',
                    '88.4' => 'Resultados não operacionais',
                    '88.5' => 'Imposto sobre os resultados correntes',
                    '88.6' => 'Resultados extraordinários',
                    '88.7' => 'Imposto sobre os resultados extraordinários',
                ],
            ],
            '89' => [
                'nome' => 'Dividendos antecipados',
                'subcontas' => [
                    '88.9' => 'Transferência para resultados transitados',
                ],
            ],
        ];

        return  $contas;
    }
    
    // public function criar_factura(Array $factura, Array $items)
    // {
        
    //     try {
    //         DB::beginTransaction();
              
    //         $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            
    //         $vendas_produtos = Receita::whereIn("nome", ["Vendas", "Vendas de Produtos"])->where("type", "R")->where("entidade_id", $entidade->empresa->id)->first();
    //         $prestacao_servicos = Receita::whereIn("nome", ["Prestações de Serviços"])->where("type", "R")->where("entidade_id", $entidade->empresa->id)->first();
            
    //         $cliente = Cliente::findOrFail($factura['clienteId']);
            
            
    //         $total_prestacao_servico = 0;
    //         $total_vendas = 0;
            
    //         foreach ($movimentos as $item) {
    //             $produt = Produto::findOrFail($item->produto_id);

    //             if ($produt->tipo == "P") {
    //                 $total_vendas += ($item->valor_pagar - $item->desconto_aplicado_valor);
    //             }

    //             if ($produt->tipo == "S") {
    //                 $total_prestacao_servico += ($item->valor_pagar - $item->desconto_aplicado_valor);
    //             }
    //         }
        
    //         foreach ($items as $item) {
            
    //             $__produto = Produto::findOrFail($item['produto_id']);
                
    //             $DESCONTO_APLICADO = 0;
                
    //             // 1. proço X quantidade
    //             $_VALOR_PAGAR = $__produto->preco_venda * 1;
        
    //             $_DESCONTO = $_VALOR_PAGAR * ($DESCONTO_APLICADO / 100);
        
    //             $_VALOR_BASE = $_VALOR_PAGAR - $_DESCONTO;
           
    //             $_VALOR_IVA = $_VALOR_BASE * ($__produto->taxa / 100);
        
    //             $_VALOR_RETENCAO = 0;
                
    //             if($__produto->tipo == "S") {
    //                 if($__produto->preco_venda_com_iva >= $entidade->empresa->valor_taxa_retencao_fonte) {
    //                     $_VALOR_RETENCAO = $_VALOR_BASE * ($entidade->empresa->taxa_retencao_fonte / 100);
    //                 }
    //             }else {
    //                 $_VALOR_RETENCAO = 0;
    //             }
        
    //             $_VALOR_TOTAL = ($_VALOR_BASE + $_VALOR_IVA) -  $_VALOR_RETENCAO;
        
    //             ItemVenda::create([
    //                 'produto_id' => $__produto->id,
    //                 'movimento_id' => 1,
    //                 'quantidade' => $item['quantidade'],
    //                 'quantidade_devolvida' => 0,
    //                 'user_id' => Auth::user()->id,
                    
    //                 'valor_pagar' => $_VALOR_TOTAL,
    //                 'total' => $_VALOR_TOTAL,
    //                 'retencao_fonte' => $_VALOR_RETENCAO,
    //                 'preco_unitario' => $__produto->preco_venda-$_DESCONTO,
    //                 'custo' => $__produto->preco_custo * $item['quantidade'],
    //                 'lucro' => (($__produto->preco_venda - $__produto->preco_custo) - $_DESCONTO) * $item['quantidade'],
    //                 'lucro_iva' => (($__produto->preco_venda_com_iva - $__produto->preco_custo) - $_DESCONTO) * $item['quantidade'],
    //                 'desconto_aplicado' => $DESCONTO_APLICADO,
    //                 'status' => 'processo',
    //                 'tipo_desconto' => 'P',
    //                 'valor_base' => $_VALOR_BASE,
    //                 'valor_iva' => $_VALOR_IVA,
    //                 'desconto_aplicado_valor' => $_DESCONTO,
                                       
    //                 'iva' => $__produto->imposto,
    //                 'iva_taxa' => $__produto->taxa,
    //                 'texto_opcional' => "",
    //                 'status_uso' => $item['status_uso'],
    //                 'caixa_id' => $item['caixa_id'],
    //                 'mesa_id' => $item['mesa_id'],
    //                 'code' => NULL,
    //                 'numero_serie' => "",
    //                 'entidade_id' => $entidade->empresa->id,
    //             ]);
    //         }
        
    //         if ($factura['tipo_documento'] == "FR") {
    //             if ($factura['tipo_pagamento'] == "NU") {
    //                 $valor_cash = $factura['total'];
    //                 $valor_multicaixa = 0;
    //             } else if ($factura['tipo_pagamento'] == "MB") {
    //                 $valor_cash = 0;
    //                 $valor_multicaixa = $factura['total'];
    //             } else {
    //                 $valor_cash = $factura['total'];
    //                 $valor_multicaixa = 0;
    //             }
    //         } else {
    //             $valor_cash = 0;
    //             $valor_multicaixa = 0;
    //         }
    
            
    //         if ($factura['tipo_documento'] == "FR") {

    //             // ESTOQUE
    //             foreach ($movimentos as $item) {
    //                 $produt = Produto::findOrFail($item->produto_id);

    //                 if ($produt->tipo == "P") {
    //                     $loja = Loja::where("status", "activo")
    //                         // ->whereIn("id", $minhas_lojas)
    //                         ->where("entidade_id", $entidade->empresa->id)
    //                         ->first();

    //                     if (!$loja) {
    //                         return response()->json(["error" => true, "message" => "Não têm nenhuma loja/armazém activa no momento para registrar saída deste produto. Por favor activa uma loja/armazém que tem este produto!"], 404);
    //                     }

    //                     $gestao_quantidade = Estoque::where("loja_id", $loja->id)
    //                         ->where("produto_id", $produt->id)
    //                         ->where("stock", ">=", 0)
    //                         ->where("entidade_id", $entidade->empresa->id)
    //                         ->first();

    //                     $verificar_quantidade = (float) $produt->total_produto_loja_activa();

    //                     if ($verificar_quantidade <= 0) {
    //                         return response()->json(["error" => true, "message" => "A loja ativa não tem este produto em estoque para comercialização."], 404);
    //                     }

    //                     if ($verificar_quantidade <= $produt->total_produto_minimo_loja_activa()) {
    //                         return response()->json(["error" => true, "message" => "A quantidade deste produto em estoque está abaixo do limite crítico, impossibilitando a venda no momento."], 404);
    //                     }

    //                     if ($produt->total_produto_loja_activa() <= $produt->total_produto_minimo_loja_activa()) {
    //                         return response()->json(["error" => true, "message" => "Stock insuficiente para o produto: {$produt->nome}."], 404);
    //                     }

    //                     // SElecinar em que lote este produto pertence para se comercializado ou reduzido naquele stock
    //                     $lote = Lote::where("produto_id", $produt->id)
    //                         ->where("codigo_barra", $produt->codigo_barra)
    //                         ->where("entidade_id", $entidade->empresa->id)
    //                         ->first();

    //                     if ($lote && $lote->status == "expirado" && $lote->data_validade <= date("Y-m-d")) {
    //                         return response()->json(["error" => true, "message" => "O produto: { $produt->nome } parece estar expirado, por isso não é possível finalizar a venda, visando a segurança da população."], 404);
    //                     }

    //                     Registro::create([
    //                         "documento" => $codigo_designacao_factura,
    //                         "registro" => "Saída de Stock",
    //                         "data_registro" => date("Y-m-d"),
    //                         "quantidade" => $item->quantidade,
    //                         "tipo" => "S",
    //                         'status' => 'V',
    //                         "produto_id" => $produt->id,
    //                         "observacao" => "Saída do produto {$produt->nome} para venda",
    //                         "loja_id" => $loja->id,
    //                         "lote_id" => $lote ? $lote->id : NULL,
    //                         "user_id" => Auth::user()->id,
    //                         "entidade_id" => $entidade->empresa->id,
    //                     ]);

    //                     $update_gestao_quantidade = Estoque::find($gestao_quantidade->id);

    //                     if ($update_gestao_quantidade) {
    //                         $update_gestao_quantidade->stock = $update_gestao_quantidade->stock - $item->quantidade;
    //                         $update_gestao_quantidade->update();
    //                     }
    //                 }
    //             }

    //             $contarFactura = Venda::where("factura", $factura['tipo_documento'])
    //                 ->where("ano_factura", $entidade->empresa->ano_factura)
    //                 ->where("entidade_id", $entidade->empresa->id)
    //                 ->count();
    
    //             $numeroFactura = $contarFactura + 1;
    
    //             $codigo_designacao_factura = "{$factura['tipo_documento']} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";


    //             if ($factura['observacao'] == null || $factura['observacao'] == "") {
    //                 $factura['observacao'] = "Pagamento referente a factura: {$codigo_designacao_factura}";
    //             }

    //             if ($factura['tipo_pagamento'] == null) {
    //                 return response()->json(["message" => "Por ser uma factura recibo, precisas escolar a forma de pagamento, isto em pagamentos!"], 404);
    //             }

    //             if ($factura['tipo_pagamento'] == "NU") {

    //                 if ($factura['caixaId'] == "") {
    //                     return response()->json(["message" => "Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!"], 404);
    //                 }

    //                 $caixa = Caixa::findOrFail($factura['caixaId']);

    //                 $valor_cash = $factura['valor_entregue'];
    //                 $valor_multicaixa = 0;
    //                 $total_pagar = $factura['valor_entregue'];

    //                 // contabilidade  DEBITAR CAIXAR
    //                 Movimento::create([
    //                     "user_id" => Auth::user()->id,
    //                     "subconta_id" => $caixa->subconta_id,
    //                     "exercicio_id" => $this->exercicio(),
    //                     "periodo_id" => $this->periodo(),
                        // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     "status" => true,
    //                     "movimento" => "E",
    //                     "observacao" => $factura['observacao'],
    //                     "credito" => 0,
    //                     "debito" => $total_pagar,
    //                     "code" => $factura['code'],
    //                     "data_at" => $factura['data_emissao'],
    //                     "entidade_id" => $entidade->empresa->id,
    //                 ]);

    //                 // CREDITAR CLIENTE
    //                 Movimento::create([
    //                     'user_id' => Auth::user()->id,
    //                     'subconta_id' => $cliente->subconta_id,
    //                     'exercicio_id' => $this->exercicio(),
    //                     'periodo_id' => $this->periodo(),
    //                     'status' => true,
                        // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     'movimento' => 'S',
    //                     'observacao' => $factura['observacao'],
    //                     'credito' => $total_pagar + $totalDesconto,
    //                     'debito' => 0,
    //                     'code' => $factura['code'],
    //                     'data_at' => $factura['data_emissao'],
    //                     'entidade_id' => $entidade->empresa->id,
    //                 ]);

    //                 // finanças
    //                 if ($total_vendas != 0) {
    //                     OperacaoFinanceiro::create([
    //                         'nome' => $vendas_produtos->nome,
    //                         'status' => "pago",
    //                         'motante' => $total_vendas,
    //                         'formas' => 'C',
    //                         'cliente_id' => $cliente->id,
    //                         'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : null,
    //                         'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
    //                         'subconta_id' => $caixa->subconta_id,
    //                         'model_id' => $vendas_produtos->id,
//                             'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                         'type' => 'R',
    //                         'status_pagamento' => "pago",
    //                         'code' => $factura['code'],
    //                         'descricao' => $factura['observacao'],
    //                         'movimento' => 'E',
    //                         'date_at' => $factura['data_emissao'],
    //                         'user_id' => Auth::user()->id,
    //                         'user_open_id' => Auth::user()->id,
    //                         'entidade_id' => $entidade->empresa->id,
    //                         'exercicio_id' => $this->exercicio(),
    //                         'periodo_id' => $this->periodo(),
    //                     ]);
    //                 }

    //                 if ($total_prestacao_servico != 0) {
    //                     OperacaoFinanceiro::create([
    //                         'nome' => $prestacao_servicos->nome,
    //                         'status' => "pago",
    //                         'motante' => $total_prestacao_servico,
                                // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                         'formas' => 'C',
    //                         'cliente_id' => $cliente->id,
    //                         'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
    //                         'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
    //                         'subconta_id' => $caixa->subconta_id,
    //                         'model_id' => $prestacao_servicos->id,
    //                         'type' => 'R',
    //                         'status_pagamento' => "pago",
    //                         'code' => $factura['code'],
    //                         'descricao' => $factura['observacao'],
    //                         'movimento' => 'E',
    //                         'date_at' => $factura['data_emissao'],
    //                         'user_id' => Auth::user()->id,
    //                         'user_open_id' => Auth::user()->id,
    //                         'entidade_id' => $entidade->empresa->id,
    //                         'exercicio_id' => $this->exercicio(),
    //                         'periodo_id' => $this->periodo(),
    //                     ]);
    //                 }
    //             }

    //             if ($factura['tipo_pagamento'] == "MB" || $factura['tipo_pagamento'] == "TE" || $factura['tipo_pagamento'] == "DE") {

    //                 if ($factura['bancoId'] == "") {
    //                     return response()->json(['message' => 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!'], 404);
    //                 }

    //                 $valor_cash = 0;
    //                 $valor_multicaixa = $factura['valor_entregue_multicaixa'];
    //                 $total_pagar = $factura['valor_entregue_multicaixa'];

    //                 $banco = ContaBancaria::findOrFail($factura['bancoId']);

    //                 // contabilidade  DEBITAR BANCO
    //                 Movimento::create([
    //                     'user_id' => Auth::user()->id,
    //                     'subconta_id' => $banco->subconta_id,
    //                     'exercicio_id' => $this->exercicio(),
    //                     'periodo_id' => $this->periodo(),
    //                     'status' => true,
    //                     'movimento' => 'E',
    //                     'observacao' => $factura['observacao'],
                        // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     'credito' => 0,
    //                     'debito' => $total_pagar,
    //                     'code' => $factura['code'],
    //                     'data_at' => $factura['data_emissao'],
    //                     'entidade_id' => $entidade->empresa->id,
    //                 ]);

    //                 // CREDITAR CLIENTE
    //                 Movimento::create([
    //                     'user_id' => Auth::user()->id,
    //                     'subconta_id' => $cliente->subconta_id,
    //                     'exercicio_id' => $this->exercicio(),
    //                     'periodo_id' => $this->periodo(),
    //                     'status' => true,
    //                     'movimento' => 'S',
                        // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     'observacao' => $factura['observacao'],
    //                     'credito' => $total_pagar + $totalDesconto,
    //                     'debito' => 0,
    //                     'code' => $factura['code'],
    //                     'data_at' => $factura['data_emissao'],
    //                     'entidade_id' => $entidade->empresa->id,
    //                 ]);

    //                 // finanças

    //                 if ($total_vendas != 0) {
    //                     OperacaoFinanceiro::create([
    //                         'nome' => $vendas_produtos->nome,
    //                         'status' => "pago",
    //                         'motante' => $total_vendas,
    //                         'formas' => 'B',
    //                         'cliente_id' => $cliente->id,
    //                         'code_caixa' => $caixaActivo->code_caixa ?? NULL,
    //                         'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
    //                         'subconta_id' => $banco->subconta_id,
    //                         'model_id' => $vendas_produtos->id,
    // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                         'type' => 'R',
    //                         'status_pagamento' => "pago",
    //                         'code' => $factura['code'],
    //                         'descricao' => $factura['observacao'],
    //                         'movimento' => 'E',
    //                         'date_at' => $factura['data_emissao'],
    //                         'user_id' => Auth::user()->id,
    //                         'user_open_id' => Auth::user()->id,
    //                         'entidade_id' => $entidade->empresa->id,
    //                         'exercicio_id' => $this->exercicio(),
    //                         'periodo_id' => $this->periodo(),
    //                     ]);
    //                 }
    //                 if ($total_prestacao_servico != 0) {
    //                     OperacaoFinanceiro::create([
    //                         'nome' => $prestacao_servicos->nome,
    //                         'status' => "pago",
    //                         'motante' => $total_prestacao_servico,
    //                         'formas' => 'B',
    //                         'cliente_id' => $cliente->id,
    //                         'code_caixa' => $caixaActivo->code_caixa ?? NULL,
    //                         'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
    //                         'subconta_id' => $banco->subconta_id,
    //                         'model_id' => $prestacao_servicos->id,'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                         'type' => 'R',
    //                         'status_pagamento' => "pago",
    //                         'code' => $factura['code'],
    //                         'descricao' => $factura['observacao'],
    //                         'movimento' => 'E',
    //                         'date_at' => $factura['data_emissao'],
    //                         'user_id' => Auth::user()->id,
    //                         'user_open_id' => Auth::user()->id,
    //                         'entidade_id' => $entidade->empresa->id,
    //                         'exercicio_id' => $this->exercicio(),
    //                         'periodo_id' => $this->periodo(),
    //                     ]);
    //                 }
    //             }

    //             if ($factura['tipo_pagamento'] == "OU") {

    //                 if ($factura['caixaId'] == "") {
    //                     return response()->json(['message' => 'Deves selecionar o caixa onde será retirado o valor para o pagamento da factura!'], 404);
    //                 }

    //                 $valor_cash = $factura['valor_entregue'];
    //                 $valor_multicaixa = $factura['valor_entregue_multicaixa_input'];

    //                 $caixa = Caixa::findOrFail($factura['caixaId']);

    //                 // contabilidade  DEBITAR CAIXAR
    //                 Movimento::create([
    //                     'user_id' => Auth::user()->id,
    //                     'subconta_id' => $caixa->subconta_id,
    //                     'exercicio_id' => $this->exercicio(),
    //                     'periodo_id' => $this->periodo(),
    //                     'status' => true,
    //                     'movimento' => 'E',
    //                     'observacao' => $factura['observacao'],
                        // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     'credito' => 0,
    //                     'debito' => $factura['valor_entregue'],
    //                     'code' => $factura['code'],
    //                     'data_at' => $factura['data_emissao'],
    //                     'entidade_id' => $entidade->empresa->id,
    //                 ]);

    //                 // CREDITAR CLIENTE
    //                 Movimento::create([
    //                     'user_id' => Auth::user()->id,
    //                     'subconta_id' => $cliente->subconta_id,
    //                     'exercicio_id' => $this->exercicio(),
    //                     'periodo_id' => $this->periodo(),
    //                     'status' => true,
    //                     'movimento' => 'S',
    //                     'observacao' => $factura['observacao'],
                        // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     'credito' => $factura['valor_entregue'],
    //                     'debito' => 0,
    //                     'code' => $factura['code'],
    //                     'data_at' => $factura['data_emissao'],
    //                     'entidade_id' => $entidade->empresa->id,
    //                 ]);

    //                 // finanças
    //                 OperacaoFinanceiro::create([
    //                     'nome' => $prestacao_servicos->nome,
    //                     'status' => "pago",
    //                     'motante' => $factura['valor_entregue'],
    //                     'formas' => 'C',
    //                     'cliente_id' => $cliente->id,
    //                     'code_caixa' => $caixaActivo->code_caixa ?? NULL,
    //                     'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
    //                     'subconta_id' => $caixa->subconta_id, 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     'model_id' => $prestacao_servicos->id,
    //                     'type' => 'R',
    //                     'status_pagamento' => "pago",
    //                     'code' => $factura['code'],
    //                     'descricao' => $factura['observacao'],
    //                     'movimento' =>  'E',
    //                     'date_at' => $factura['data_emissao'],
    //                     'user_id' => Auth::user()->id,
    //                     'user_open_id' => Auth::user()->id,
    //                     'entidade_id' => $entidade->empresa->id,
    //                     'exercicio_id' => $this->exercicio(),
    //                     'periodo_id' => $this->periodo(),
    //                 ]);

    //                 if ($factura['bancoId'] == "") {
    //                     // return redirect()->back()->with('danger', 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!');
    //                     return response()->json(['message' => 'Deves selecionar o banco onde será retirado o valor para o pagamento da factura!'], 404);
    //                 }

    //                 $banco = ContaBancaria::findOrFail($factura['bancoId']);

    //                 // contabilidade  DEBITAR BANCO
    //                 Movimento::create([
    //                     'user_id' => Auth::user()->id,
    //                     'subconta_id' => $banco->subconta_id,
    //                     'exercicio_id' => $this->exercicio(),
    //                     'periodo_id' => $this->periodo(),
    //                     'status' => true,
    //                     'movimento' => 'E',
    //                     'observacao' => $factura['observacao'],
    //                     'credito' => 0,
    //                     'debito' => $factura['valor_entregue_multicaixa'],
    //                     'code' => $factura['code'],
                        // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     'data_at' => $factura['data_emissao'],
    //                     'entidade_id' => $entidade->empresa->id,
    //                 ]);

    //                 // CREDITAR CLIENTE
    //                 Movimento::create([
    //                     'user_id' => Auth::user()->id,
    //                     'subconta_id' => $cliente->subconta_id,
    //                     'exercicio_id' => $this->exercicio(),
    //                     'periodo_id' => $this->periodo(),
    //                     'status' => true,
                        // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     'movimento' => 'S',
    //                     'observacao' => $factura['observacao'],
    //                     'credito' => $factura['valor_entregue_multicaixa'],
    //                     'debito' => 0,
    //                     'code' => $factura['code'],
    //                     'data_at' => $factura['data_emissao'],
    //                     'entidade_id' => $entidade->empresa->id,
    //                 ]);

    //                 // CREDITAR CLIENTE - VALOR DO dESCONTO PAR ENCERRAR A CONTA
    //                 Movimento::create([
    //                     'user_id' => Auth::user()->id,
    //                     'subconta_id' => $cliente->subconta_id,
    //                     'exercicio_id' => $this->exercicio(),
    //                     'periodo_id' => $this->periodo(),
    //                     'status' => true,
    //                     'movimento' => 'S',
                        // 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     'observacao' => $factura['observacao'],
    //                     'credito' => $totalDesconto,
    //                     'debito' => 0,
    //                     'code' => $factura['code'],
    //                     'data_at' => $factura['data_emissao'],
    //                     'entidade_id' => $entidade->empresa->id,
    //                 ]);

    //                 OperacaoFinanceiro::create([
    //                     'nome' => $prestacao_servicos->nome,
    //                     'status' => "pago",
    //                     'motante' => $factura['valor_entregue_multicaixa'],
    //                     'formas' => 'B',
    //                     'cliente_id' => $cliente->id,
    //                     'code_caixa' => $caixaActivo->code_caixa ?? NULL,
    //                     'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
    //                     'subconta_id' => $banco->subconta_id, 'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
    //                     'model_id' => $prestacao_servicos->id,
    //                     'type' => 'R',
    //                     'status_pagamento' => "pago",
    //                     'code' => $factura['code'],
    //                     'descricao' => $factura['observacao'],
    //                     'movimento' => 'E',
    //                     'date_at' => $factura['data_emissao'],
    //                     'user_id' => Auth::user()->id,
    //                     'user_open_id' => Auth::user()->id,
    //                     'entidade_id' => $entidade->empresa->id,
    //                     'exercicio_id' => $this->exercicio(),
    //                     'periodo_id' => $this->periodo(),
    //                 ]);
    //             }
    //         } else {
    //             $factura['tipo_pagamento'] =  $factura['tipo_pagamento'];
    //         }
    
    
    //         $movimentos = ItemVenda::where('code', NULL)
    //             ->where('entidade_id', '=', $entidade->empresa->id)
    //             ->where('status', '=', 'processo')
    //             ->where('user_id', '=', Auth::user()->id)
    //         ->get();
    
    //         $totalValorBase = 0;
    //         $totalValorIva = 0;
    //         $totalItems = 0;
    //         $totalDesconto = 0;
    //         $totalRetencao = 0;
    
    //         $lucro_total = 0;
    //         $custo_total = 0;
    
    //         if ($movimentos) {
    //             foreach ($movimentos as $value) {
    //                 $update = ItemVenda::findOrFail($value->id);
    //                 $update->code = $factura['code'];
    //                 $update->status = "realizado";
    //                 $update->update();
    
    //                 $lucro_total += $value->lucro;
    //                 $custo_total += $value->custo;
    
    //                 $totalValorBase += $value->valor_base;
    //                 $totalValorIva += $value->valor_iva;
    //                 $totalItems += $value->quantidade;
    //                 $totalDesconto += $value->desconto_aplicado_valor;
    //                 $totalRetencao += $value->retencao_fonte;
    //             }
    //         }
    
    //         $contarFactura = Venda::where('factura', $factura['tipo_documento'])
    //             ->where('ano_factura', $entidade->empresa->ano_factura)
    //             ->where('entidade_id', $entidade->empresa->id)
    //             ->count();
    
    //         $numeroFactura = $contarFactura + 1;
    
    //         $codigo_designacao_factura = "{$factura['tipo_documento']} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$numeroFactura}";
    
    //         $ultimoRecibo = Venda::where('factura', $factura['tipo_documento'])
    //             ->where('ano_factura', $entidade->empresa->ano_factura)
    //             ->where('entidade_id', $entidade->empresa->id)
    //             ->orderBy('id', 'DESC')
    //             ->first();
    
    
    //         if ($ultimoRecibo && $ultimoRecibo->created_at->gt(Carbon::now())) {
    //             return response()->json([
    //                 'message' => 'Não podemos concluir a criação deste documento porque a data do seu computador não está certa.
    //                 Acerta a data e hora do seu computador para continuar, ou entra em contacto com os administradores do sistema!'
    //             ], 400);
    //         }
    
    //         if (!$ultimoRecibo) {
    //             $hashAnterior = "";
    //         } else {
    //             $hashAnterior = $ultimoRecibo->hash;
    //         }
    
    //         $data_emissao = $factura['data_emissao'] . " " . date('H:i:s');
    //         //Manipulação de datas: data actual
    //         $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $data_emissao);
    
    //         $rsa = new RSA(); //Algoritimo RSA
    
    //         $privatekey = $this->pegarChavePrivada();
    //         $publickey = $this->pegarChavePublica();
    
    //         // Lendo a private key
    //         $rsa->loadKey($privatekey);
    
    //         /**
    //          * Texto que deverá ser assinado com a assinatura RSA::SIGNATURE_PKCS1, e o Texto estará mais ou menos assim após as
    //          * Concatenações com os dados preliminares da factura: 2020-09-14;2020-09-14T20:34:09;FP PAT2020/1;457411.2238438; */
    
    //         $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ";{$codigo_designacao_factura};" . number_format($factura['total'], 2, ".", "") . ';' . $hashAnterior;
    
    //         // HASH
    //         $hash = 'sha1'; // Tipo de Hash
    //         $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima
    
    //         //ASSINATURA
    //         $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
    //         $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)
    
    //         // Lendo a public key
    //         $rsa->loadKey($publickey);
    
    //         $valor_extenso = $this->valor_por_extenso( number_format($factura['total'], 0) );
        
        
    //         if ($factura['tipo_documento'] == "FR") {
    //             $statusFactura = "pago";
    //             $retificado = "N";
    //             $convertido_factura = "N";
    //             $factura_divida = "N";
    //             $anulado = "N";
    //             $valor_divida = 0;
    //         } else {
    //             $statusFactura = "por pagar";
    //             $retificado = "N";
    //             $convertido_factura = "N";
    //             $factura_divida = "Y";
    //             $anulado = "N";
    //             $valor_divida = $factura['total'];
    //         }
                
    //         $create_factura = Venda::create([
    //             'codigo_factura' => $numeroFactura,
    //             'status' => true,
    //             'status_venda' => "realizado",
    //             'status_factura' => $statusFactura,
    //             'user_id' => Auth::user()->id,
    //             'cliente_id' => $cliente->id,
                            
    //             'exame_id' => $factura['exameId'],
    //             'consulta_id' => $factura['consultaId'],
    //             'internamento_id' => $factura['internamentoId'],
    //             'tratamento_id' => $factura['tratamentoId'],
    //             'caixa_id' => $factura['caixaId'],
    //             'banco_id' => $factura['bancoId'],
    //             'quarto_id' => $factura['quartoId'],
    //             'mesa_id' => $factura['mesaId'],
                
    //             'parent_id' => $factura['parent_id'],
    //             'seguradora_id' => $factura['seguradora_id'],
    //             'valor_entregue' => 0,
    //             'valor_total' => $factura['total'],
    //             'lucro_total' => $lucro_total,
    //             'custo_total' => $custo_total,
    //             'valor_divida' => $valor_divida,
    //             'total_retencao_fonte' => $totalRetencao,
    //             'valor_pago' => 0,
    //             'ano_factura' => $entidade->empresa->ano_factura,
    //             'prazo' => 0,
    //             'valor_troco' => $factura['total'] - $factura['total'],
    //             'data_emissao' => $factura['data_emissao'],
    //             'data_documento' => $datactual,
    //             'data_vencimento' => $factura['data_emissao'],
    //             'data_disponivel' => $factura['data_emissao'],
    //             'code' => $factura['code'],
    //             'desconto_percentagem' => 0,
    //             'desconto' => $totalDesconto,
    //             'pagamento' => $factura['tipo_pagamento'],
    //             'factura' => $factura['tipo_documento'],
    //             'factura_next' => $codigo_designacao_factura,
    //             'observacao' => $factura['observacao'],
    //             'referencia' => $factura['observacao'],
    //             'entidade_id' => $entidade->empresa->id,
    
    //             'nome_cliente' => $factura['clienteNome'],
    //             'documento_nif' => $factura['clienteNif'],
    
    //             'retificado' => $retificado,
    //             'convertido_factura' => $convertido_factura,
    //             'factura_divida' => $factura_divida,
    //             'anulado' => $anulado,
    
    //             'moeda' => $entidade->empresa->moeda ?? 'AOA',
    //             'valor_extenso' => $valor_extenso,
    //             'valor_cash' => $valor_cash,
    //             'valor_multicaixa' => $valor_multicaixa,
    //             'texto_hash' => $plaintext,
    //             'hash' => base64_encode($signaturePlaintext),
    //             'nif_cliente' => $factura['clienteNif'],
    
    //             'total_iva' => $totalValorIva,
    //             'total_incidencia' => $totalValorBase,
    //             'quantidade' => $totalItems,
    //         ]);
            
            
    //         if ($statusFactura == "por pagar") {
    //             $cartao = ContaCliente::where('cliente_id', $cliente->id)->firstOrFail();

    //             MovimentoContaCliente::create([
    //                 "user_id" => Auth::user()->id,
    //                 "documento" => $codigo_designacao_factura,
    //                 "conta_id" => $cartao->id,
    //                 "observacao" => "prestação de serviços hospitalares",
    //                 "montante" => $factura['total'],
    //                 "cliente_id" => $cliente->id,
    //                 "data_emissao" => $factura['data_emissao'],
    //                 "tipo_movimento" => -1,
    //                 "entidade_id" => $entidade->empresa->id,
    //             ]);

    //             $cartao->saldo += $factura['total'];
    //             $cartao->divida_corrente += $factura['total'];
    //             $cartao->save();
    //         }
            
            
    //         DB::commit();
        
    //     } catch (\Exception $e) {
    //         // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
    //         DB::rollback();
    //         return response()->json(['error' => $e->getMessage()], 400);
    //     }
        
    //     return $create_factura;
  
    // }
    
}

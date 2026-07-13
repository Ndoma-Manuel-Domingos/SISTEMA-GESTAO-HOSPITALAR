<?php

namespace App\Http\Controllers\pdf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PDF;

class FacturasSemPagamentoController extends Controller
{

    use TraitHelpers;

    public function pdfFacturaSemPagamentos($tipoDocumento = null, $factura = null)
    {
        if($tipoDocumento == "todas" && $factura == ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

             // dividas vencidas
             $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
            ])
            ->sum('valor_total');


        }else if($tipoDocumento == "todas" && $factura != ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['factura_next', 'like' ,"%{$factura}%"],
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

             // dividas vencidas
             $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$factura}%"],
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$factura}%"],
            ])
            ->sum('valor_total');



        }else if($tipoDocumento == "dividas_corrente" && $factura != ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$factura}%"],
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = 0;
            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$factura}%"],
            ])
            ->sum('valor_total');


        }else if($tipoDocumento == "dividas_corrente" && $factura == ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = 0;

            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
            ])
            ->sum('valor_total');


        }else if($tipoDocumento == "dividas_vencidas" && $factura != ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$factura}%"],
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],['data_vencimento', '<', date("Y-m-d")],
                ['factura_next', 'like' ,"%{$factura}%"],
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = 0;


        }else if($tipoDocumento == "dividas_vencidas" && $factura == ""){
            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

            // dividas vencidas
            $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = 0;

        }else{


            ####################### PADRÃO

            $facturas = Venda::where([
                ['status_factura', '=','por pagar'],
            ])
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();        
            
            // dividas vencidas
            $facturasVencidas = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_vencimento', '<', date("Y-m-d")],
            ])
            ->sum('valor_total');

            //dividas corrente
            $facturasVencidasCorrente = Venda::where([
                ['status_factura', '=','por pagar'],
                ['data_emissao', '<', date("Y-m-d")],
                ['data_vencimento', '>', date("Y-m-d")],
            ])
            ->sum('valor_total');
        }
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            'titulo' => "Facturas sem Pagamentos",
            'descricao' => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "facturas" => $facturas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "facturasVencidas" => $facturasVencidas,
            "facturasVencidasCorrente" => $facturasVencidasCorrente,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.factura-sem-pagamentos', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}

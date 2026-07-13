<?php

namespace App\Http\Controllers\pdf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\NotaCredito;
use App\Models\Recibo;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PDF;

class FacturasInformativoController extends Controller
{
    use TraitHelpers;

    public function pdfFacturaInformativo($factura = null)
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $facturas = Venda::when($factura, function($query, $value){
            $query->where('factura_next', 'like' ,"%{$value}%");
        })->where([
            ['factura', '=','OT'],
        ])
        ->orWhere('factura', 'EC')
        ->orWhere('factura', 'PF')
        ->with('cliente')
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->orderby('created_at', 'desc')
        ->get();
             
        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            'titulo' => "Facturas Informativo",
            'descricao' => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "facturas" => $facturas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.factura-informativo', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function pdfNotaCredito()
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $facturas = NotaCredito::with('cliente')
            ->with('facturas')
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderby('created_at', 'desc')
        ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            'titulo' => "Nota Creditos",
            'descricao' => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "facturas" => $facturas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.notas-creditos', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function pdfRecibos()
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $facturas = Recibo::with('cliente')
        ->with('facturas')
        ->where('entidade_id', '=', $entidade->empresa->id)
        ->orderby('created_at', 'desc')
        ->get();
 
        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            'titulo' => "Recibos",
            'descricao' => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "facturas" => $facturas,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.facturas.documentos.recibos', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }




    
}

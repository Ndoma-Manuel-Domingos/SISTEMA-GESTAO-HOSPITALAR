<?php

namespace App\Http\Controllers;

use App\Models\CartaoTemplate;
use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use SimpleSoftwareIO\QrCode\Facades\QrCode; // usando simple-qrcode

class CartaoTemplateController extends Controller
{

    // Listar bancos
    public function index()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $cartao = CartaoTemplate::where('entidade_id', $entidade->empresa->id)->first() ?? CartaoTemplate::create([
            'name' => 'Default PVC',
            'width' => 540, // px
            'height' => 340, // px
            'orientation' => 'horizontal', // horizontal|vertical
            'font_family' => 'Arial',
            'font_size_title' => '14px',
            'font_size_subtitle' => '14px',
            'font_size' => '14px',
            'text_color' => '#000000',
            'background_color' => '#ffffff',
            'photo_position' => 'left', // left|right|top|bottom
            'entidade_id' => $entidade->empresa->id,
            'user_id' => Auth::user()->id,
        ]);

        $head = [
            "titulo" => "Configuração do cartão do Funcionário",
            "descricao" => env('APP_NAME'),
            "template" => $cartao,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.cartao.index', $head);
    }
    
    // rota para gerar QR code do funcionário
    public function create(Funcionario $funcionario)
    {
        $payload = route('employee.scan', ['id' => $funcionario->id]); // ou qualquer payload
        // Opcional: salva no campo qr_code
        $funcionario->qr_code = $payload;
        $funcionario->update();

        $svg = QrCode::format('svg')->size(200)->generate($payload);
        return response($svg)->header('Content-Type', 'image/svg+xml');
    }


    // salvar template
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'nullable|string',
            'width'=>'required|integer',
            'height'=>'required|integer',
            'height_logo'=>'required|integer',
            'line_height'=>'required|string',
            'opacity'=>'required|string',
            'filter'=>'required|string',
            'orientation'=>'required|in:horizontal,vertical',
            'rotacao_fundo'=>'required|integer',
            'border_radius'=>'required|integer',
            'border_top_space'=>'required|integer',
            'border_top_color'=>'required|string',
            'border_bottom_space'=>'required|integer',
            'border_bottom_color'=>'required|string',
            'border_logo'=>'required|integer',
            'border_logo_color'=>'required|string',
            'border_logo_radius'=>'required|integer',
            'font_family'=>'nullable|string',
            'font_size_title'=>'nullable|string',
            'font_size_subtitle'=>'nullable|string',
            'font_size'=>'nullable|string',
            'text_color'=>'nullable|string',
            'background_color'=>'nullable|string',
            'background_color_segunda'=>'nullable|string',
            'background_color_terceira'=>'nullable|string',
            'photo_position'=>'nullable|in:left,right,top,bottom',
            'logo_position'=>'nullable|in:left,right,top,bottom',
        ]);
        
        if ($request->hasFile('background_image') && $request->file('background_image')->isValid()) {
            $image = $request->file('background_image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/empresa'), $imageName);
        } else {
            $imageName = null;
        }
       
        try {
            DB::beginTransaction();

            $template = CartaoTemplate::first() ?? new CartaoTemplate();
            $template->fill($data);
            $template->background_image = $imageName ?? $template->background_image;
            $template->save();
        
           // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['status'=>'ok','template'=>$template]);
    }
    
    
    // Listar bancos
    public function show($id)
    {
        $funcionario = Funcionario::with(['contrato.cargo', 'estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito'])->findOrFail($id);
    
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        $cartao = CartaoTemplate::where('entidade_id', $entidade->empresa->id)->first() ?? CartaoTemplate::create([
            'name' => 'Default PVC',
            'width' => 540, // px
            'height' => 340, // px
            'orientation' => 'horizontal', // horizontal|vertical
            'font_family' => 'Arial',
            'font_size_title' => '14px',
            'font_size_subtitle' => '14px',
            'font_size' => '14px',
            'text_color' => '#000000',
            'background_color' => '#ffffff',
            'photo_position' => 'left', // left|right|top|bottom
            'entidade_id' => $entidade->empresa->id,
            'user_id' => Auth::user()->id,
        ]);

        $head = [
            "titulo" => "Configuração do cartão do Funcionário",
            "descricao" => env('APP_NAME'),
            "template" => $cartao,
            "funcionario" => $funcionario,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.funcionarios.cartao.index', $head);
    }

    
}

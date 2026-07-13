@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $titulo }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.controle') }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5 col-12">
                    <form id="templateForm" action="{{ route('configuracao-carto-funcionario.store') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nome do template</label>
                                    <input type="text" name="name" class="form-control" value="{{ $template->name }}">
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-12 col-md-4">
                                        <label>Height (px) Logo</label>
                                        <input type="number" name="height_logo" class="form-control" value="{{ $template->height_logo }}">
                                    </div>
                                    <div class="form-group col-12 col-md-4">
                                        <label>Width (px)</label>
                                        <input type="number" name="width" class="form-control" value="{{ $template->width }}">
                                    </div>
                                    <div class="form-group col-12 col-md-4">
                                        <label>Height (px)</label>
                                        <input type="number" name="height" class="form-control" value="{{ $template->height }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-12 col-md-4">
                                        <label>Orientação</label>
                                        <select name="orientation" class="form-control">
                                            <option value="horizontal" {{ $template->orientation=='horizontal'?'selected':'' }}>Horizontal</option>
                                            <option value="vertical" {{ $template->orientation=='vertical'?'selected':'' }}>Vertical</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-12 col-md-4">
                                        <label>Rotação do Fundo</label>
                                        <input type="text" name="rotacao_fundo" class="form-control" value="{{ $template->rotacao_fundo }}">
                                    </div>

                                    <div class="form-group col-12 col-md-4">
                                        <label>Borda Redonda</label>
                                        <input type="text" name="border_radius" class="form-control" value="{{ $template->border_radius }}">
                                    </div>
                                </div>

                                <div class="form-row">

                                    <div class="form-group col-12 col-md-4">
                                        <label>Borda Foto</label>
                                        <input type="number" name="border_logo" class="form-control" value="{{ $template->border_logo }}">
                                    </div>
                                    <div class="form-group col-12 col-md-4">
                                        <label>Cor Borda Foto</label>
                                        <input type="color" name="border_logo_color" class="form-control" value="{{ $template->border_logo_color }}">
                                    </div>
                                    <div class="form-group col-12 col-md-4">
                                        <label>Borda Redonda Foto</label>
                                        <input type="number" name="border_logo_radius" class="form-control" value="{{ $template->border_logo_radius }}">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Foto posição</label>
                                        <select name="photo_position" class="form-control">
                                            <option value="left" {{ $template->photo_position=='left'?'selected':'' }}>Esquerda</option>
                                            <option value="right" {{ $template->photo_position=='right'?'selected':'' }}>Direita</option>
                                            <option value="top" {{ $template->photo_position=='top'?'selected':'' }}>Topo</option>
                                            <option value="bottom" {{ $template->photo_position=='bottom'?'selected':'' }}>Rodapé</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Logotipo posição</label>
                                        <select name="logo_position" class="form-control">
                                            <option value="left" {{ $template->logo_position=='left'?'selected':'' }}>Esquerda</option>
                                            <option value="right" {{ $template->logo_position=='right'?'selected':'' }}>Direita</option>
                                            <option value="top" {{ $template->logo_position=='top'?'selected':'' }}>Topo</option>
                                            <option value="bottom" {{ $template->logo_position=='bottom'?'selected':'' }}>Rodapé</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-12 col-md-6">
                                        <label>Fonte</label>
                                        <input type="text" name="font_family" class="form-control" value="{{ $template->font_family }}">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Espaçamento Entre Linha</label>
                                        <input type="text" name="line_height" class="form-control" value="{{ $template->line_height }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-12 col-md-6">
                                        <label>Tamanho da fonte (Titulo)</label>
                                        <input type="text" name="font_size_title" class="form-control" value="{{ $template->font_size_title }}">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Tamanho da fonte (Subitulo)</label>
                                        <input type="text" name="font_size_subtitle" class="form-control" value="{{ $template->font_size_subtitle }}">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Tamanho da fonte</label>
                                        <input type="text" name="font_size" class="form-control" value="{{ $template->font_size }}">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Cor do texto</label>
                                        <input type="color" name="text_color" class="form-control" value="{{ $template->text_color }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-12 col-md-6">
                                        <label>Cor de fundo</label>
                                        <input type="color" name="background_color" class="form-control" value="{{ $template->background_color }}">
                                    </div>

                                    <div class="form-group col-12 col-md-6">
                                        <label>Cor de fundo gradiente 01</label>
                                        <input type="color" name="background_color_segunda" class="form-control" value="{{ $template->background_color_segunda }}">
                                    </div>

                                    <div class="form-group col-12 col-md-6">
                                        <label>Cor de fundo gradiente 02</label>
                                        <input type="color" name="background_color_terceira" class="form-control" value="{{ $template->background_color_terceira }}">
                                    </div>

                                    <div id="background_image" class="content col-12 col-md-6" role="tabpanel" aria-labelledby="logotipo-trigger">
                                        <div class="form-group">
                                            <label for="logotipo2">Imagem de Fundo</label>
                                            <input type="file" class="form-control" accept="image/*" name="background_image" id="logotipo2" placeholder="background image">
                                        </div>
                                    </div>

                                    <div class="form-group col-12 col-md-6">
                                        <label for="opacity">Opacidade</label>
                                        <input type="range" name="opacity" id="opacity" min="0" max="1" step="0.1" class="form-control-range" value="{{ $template->opacity }}">
                                    </div>

                                    <div class="form-group col-12 col-md-6">
                                        <label for="filter">Filtro</label>
                                        <input type="range" id="filter" min="0" max="10" step="1" name="filter" class="form-control-range" value="{{ $template->filter }}">
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group col-12 col-md-6">
                                        <label>Espaçamento da Borda cima</label>
                                        <input type="number" name="border_top_space" class="form-control" value="{{ $template->border_top_space }}">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Cor da Borda cima</label>
                                        <input type="color" name="border_top_color" class="form-control" value="{{ $template->border_top_color }}">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Espaçamento da Borda Baixo</label>
                                        <input type="number" name="border_bottom_space" class="form-control" value="{{ $template->border_bottom_space }}">
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label>Cor da Borda Baixo</label>
                                        <input type="color" name="border_bottom_color" class="form-control" value="{{ $template->border_bottom_color }}">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-7 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Pré-visualização</h5>
                        </div>

                        <div class="card-body bg-light" style="flex-direction: column;display: flex;align-items: center;padding: 30px;gap: 4px;">
                            <div style="gap: 0px;display: flex" style="padding:20px;">
                                <!-- Frente Horizontal -->
                                <div id="frente-horizontal" style="
                                        width: {{ $template->width}}px;
                                        height: {{ $template->height + 70 }}px;
                                        position: relative;
                                        overflow: hidden;
                                        border-top: {{ $template->border_top_space }}px solid {{ $template->border_top_color }};
                                        border-bottom: {{ $template->border_bottom_space }}px solid {{ $template->border_bottom_color }};
                                        padding: 15px;border-radius: {{ $template->border_radius }}px;align-items: center;box-shadow: 0 0 8px rgba(0,0,0,0.1);">
                                    <div style="
                                        position: absolute;
                                        inset: 0;
                                        background: linear-gradient(120deg, #0056b3, #0099ff, #00ccff);
                                        /* background: linear-gradient({{ $template->rotacao_fundo }}deg, {{ $template->background_color }}, {{ $template->background_color_segunda }}, {{ $template->background_color_terceira }});  */
                                        /* background-image: url('{{ $template->background_image ? asset("images/empresa/$template->background_image") : "" }}'); */
                                        background-size: 15% 20%;
                                        background-position: 40% 5%;
                                        background-repeat: no-repeat;
                                        background-attachment: fixed;
                                        z-index: -1;
                                        opacity: {{ $template->opacity }};
                                        filter: blur({{ $template->filter }}px);
                                    "></div>
                                    <div style="position: relative; z-index: 99;color: {{ $template->text_color }};font-family: {{ $template->font_family }};">
                                        <div style="display: flex;align-items: center;width: 100%;margin-bottom: 10px;
                                        {{
                                            $template->logo_position == 'left' ? 'flex-direction: row' :
                                            ($template->logo_position == 'right' ? 'flex-direction: row-reverse' :
                                            ($template->logo_position == 'top' ? 'flex-direction: column' : 'flex-direction: column-reverse'));
                                        }}">
                                            <img src="{{ asset('images/empresa/'.$empresa_logada->empresa->logotipo) }}" alt="Logo" style="height: {{ $template->height_logo }}px; width: auto;margin: 2px;">
                                            <h3 style="font-size: {{ $template->font_size_title }};margin-top: 10px;">{{ $LOJAACTIVAOPERADOR->nome }}</h3>
                                        </div>

                                        <div style="
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                {{ $template->photo_position == 'left' ? 'flex-direction: row;' : '' }}
                                                {{ $template->photo_position == 'right' ? 'flex-direction: row-reverse;' : '' }}
                                                {{ $template->photo_position == 'top' ? 'flex-direction: column;' : '' }}
                                                {{ $template->photo_position == 'bottom' ? 'flex-direction: column-reverse;' : '' }}
                                                {{ $template->orientation == 'horizontal' ? 'flex-direction: row;' : 'flex-direction: column;' }}
                                            ">
                                            <div style="{{ $template->orientation == "horizontal"
                                                ? 'width: 80px;height: 100px;margin-right: 15px;flex-shrink: 0;' :
                                                'width: 100px;height: 120px;margin: 0 auto 10px auto;'  }}">
                                                <img src="{{ asset('images/funcionarios/user.png') }}" style="width: 100%; height: 100%; object-fit: cover;border: {{ $template->border_logo }}px solid {{ $template->border_logo_color }};border-radius: {{ $template->border_logo_radius }}px;">
                                            </div>
                                            <div style="line-height: {{ $template->line_height }};padding: 10px">
                                                <h2 style="font-size: {{ $template->font_size_subtitle }};{{ $template->orientation == "horizontal" ? 'text-align: left;' : 'text-align: center;' }}">Ndoma Manuel Domingos</h2>
                                                <p style="font-size: {{ $template->font_size }};{{ $template->orientation == "horizontal" ? 'text-align: left;' : 'text-align: center;' }}"><b>Função:</b> Programador Junior</p>
                                                <p style="font-size: {{ $template->font_size }};{{ $template->orientation == "horizontal" ? 'text-align: left;' : 'text-align: center;' }}"><b>Nº Mec:</b> NM004</p>
                                                <p style="font-size: {{ $template->font_size }};{{ $template->orientation == "horizontal" ? 'text-align: left;' : 'text-align: center;' }}"><b>Validade:</b> {{ date('d/m/Y') }}</p>
                                            </div>
                                        </div>

                                        <div style="display: flex;align-items: center;width: 100%;justify-content: left;flex-direction: column;line-height: 5px;">
                                            <p style="font-size: {{ $template->font_size }};margin-top: 0px;line-height: 5px;">Director(a)</p>
                                            <p style="line-height: 5px">_______________________</p>
                                            <p style="font-size: {{ $template->font_size }};margin-top: 0px;line-height: 5px;">Ndoma Manuel Domingos</p>
                                        </div>

                                    </div>

                                </div>

                                <!-- Verso Horizontal -->
                                <div id="verso-horizontal" style="width: {{ $template->width }}px;
                                    height: {{ $template->height + 70}}px;
                                    position: relative;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    padding: 15px;
                                    overflow: hidden;
                                    border-top: {{ $template->border_top_space }}px solid {{ $template->border_top_color }};
                                    border-bottom: {{ $template->border_bottom_space }}px solid {{ $template->border_bottom_color }};
                                    border-radius: {{ $template->border_radius }}px;
                                    box-shadow: 0 0 8px rgba(0,0,0,0.3);
                                    ">
                                    {!! QrCode::size(180)->generate("test") !!}
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button id="exportCard" class="btn btn-light-success mt-3">Exportar / Salvar imagem</button>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>
@endsection

@section('scripts')

<script>
    // salvar template
    $('#templateForm').on('submit', function(e) {
        e.preventDefault(); // Impede o envio tradicional do formulário

        let form = $(this)[0]; // Pega o elemento DOM puro
        let formData = new FormData(form); // Cria um FormData com os dados do form

        let fileInput2 = $('#logotipo2')[0].files[0];
        if (fileInput2) {
            formData.append('background_image', fileInput2); // Adiciona o arquivo
        }

        $.ajax({
            url: $(this).attr('action')
            , method: $(this).attr('method')
            , data: formData
            , processData: false, // ❗ obrigatório para envio de FormData
            contentType: false, // ❗ obrigatório para envio de FormData
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
            , beforeSend: function() {
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                window.location.reload();
            }
            , error: function(xhr) {
                Swal.close();

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let messages = '';
                    $.each(errors, function(key, value) {
                        messages += `${value}\n *`;
                    });

                    showMessage('Erro de Validação!', messages, 'error');
                } else {
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            }
        });
    });

</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const bg = document.getElementById('preview-bg');
        const frente = document.getElementById('frente-horizontal');

        function updatePreview() {

            const bg1 = document.querySelector('[name="background_color"]') ? .value || '#ffffff';

            const bg2 = document.querySelector('[name="background_color_segunda"]') ? .value || '#cccccc';
            const bg3 = document.querySelector('[name="background_color_terceira"]') ? .value || '#999999';
            const rotacao = document.querySelector('[name="rotacao_fundo"]') ? .value || 90;
            const opacity = document.querySelector('[name="opacity"]') ? .value || 1;
            const blur = document.querySelector('[name="filter"]') ? .value || 0;
            const borderRadius = document.querySelector('[name="border_radius"]') ? .value || 0;


            // Atualiza fundo
            bg.style.background = `linear-gradient(${rotacao}deg, ${bg1}, ${bg2}, ${bg3})`;

            bg.style.opacity = opacity;
            bg.style.filter = `blur(${blur}px)`;
            frente.style.borderRadius = `${borderRadius}px`;


            // Atualiza imagem de fundo (se foi alterada)
            const fileInput = document.querySelector('#background_image');
            if (fileInput && fileInput.files.length > 0) {
                const reader = new FileReader();
                reader.onload = e => bg.style.backgroundImage = `url('${e.target.result}')`;
                reader.readAsDataURL(fileInput.files[0]);
            }
        }

        // Monitora todos os campos do formulário
        const inputs = document.querySelectorAll(`
        input,
        select,
        textarea
    `);

        inputs.forEach(el => {
            el.addEventListener('input', updatePreview()); // enquanto digita
            el.addEventListener('change', updatePreview()); // ao mudar o valor
            el.addEventListener('blur', updatePreview()); // ao perder o foco
        });
    });

</script>


@endsection

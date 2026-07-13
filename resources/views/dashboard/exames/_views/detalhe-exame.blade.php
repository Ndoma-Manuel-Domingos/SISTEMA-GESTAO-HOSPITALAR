<div class="row">
    <div class="col-12 col-md-12 table-responsive">
        <table class="table text-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th> {{ __('messages.designacao') }} </th>
                    <th>{{ __('messages.categoria') }}</th>
                    <th class="text-right">-----------</th>
                    <th class="text-right">{{ __('messages.accoes') }} </th>
                </tr>
            </thead>
            <tbody>

                @foreach ($dados->items as $item)
                <tr class="linha-principal" data-id="{{ $item->id ?? "" }}" style="cursor:pointer;">
                    <td>{{ $item->id ?? "" }}</td>
                    <td>{{ $item->produto->nome ?? "" }}</td>
                    <td>{{ $item->produto ? $item->produto->categoria->categoria : "" }}</td>
                    <td class="text-right">______________</td>
                    <td class="text-right">
                        @if ($dados->status != "CONCLUIDO" && $editar == true)
                        @if (Auth::user()->can('laboratorio'))
                        <button class="btn btn-light-success btn-sm">Lançar Resultado</button>
                        @endif
                        @else
                        @if (Auth::user()->can('laboratorio'))
                        <button class="btn btn-light-primary btn-sm">Ver dados</button>
                        @endif
                        @endif
                    </td>
                </tr>
                {{-- DROPDOWN (OCULTO) --}}
                <tr class="dropdown-parametros bg-light" id="drop-{{ $item->id ?? "" }}" style="display:none;">
                    <td colspan="11">
                        <table class="table table-sm">
                            <tbody>
                                @foreach ($item->resultado_parametro_exame as $r_parametro_exame)
                                <tr>
                                    <th colspan="5">{{ $r_parametro_exame->nome }}</th>
                                </tr>
                                <tr>
                                    <th>Código</th>
                                    <th>Parâmetro</th>
                                    <th>Resultado</th>
                                    <th>Unidade</th>
                                    <th>Referência</th>
                                </tr>

                                @foreach ($r_parametro_exame->resultadosubparamentros as $r_sub_parametro)
                                @if ($r_sub_parametro->subparametroexame->tipo === "lista" || $r_sub_parametro->subparametroexame->tipo === "data" || $r_sub_parametro->subparametroexame->tipo === "numero" || $r_sub_parametro->subparametroexame->tipo === "booleano" || $r_sub_parametro->subparametroexame->tipo === "textarea" || $r_sub_parametro->subparametroexame->tipo === "texto")
                                <tr>
                                    <td class="align-middle">{{ $r_sub_parametro->subparametroexame->codigo ?? '----------' }}</td>
                                    <td class="align-middle">{{ $r_sub_parametro->subparametroexame->nome ?? '----------' }}</td>

                                    @if ($r_sub_parametro->subparametroexame->tipo === "textarea" || $r_sub_parametro->subparametroexame->tipo === "texto")
                                    <td class="align-middle">
                                        @if ($dados->status != "CONCLUIDO" && $editar == true)
                                        <textarea rows="2" data-id="{{ $r_sub_parametro->id }}" class="form-control form-control-sm resultado-input">{{ $r_sub_parametro->valor ?? "N/A" }}</textarea>
                                        @else
                                        {{ $r_sub_parametro->valor ?? "N/A" }}
                                        @endif
                                    </td>
                                    @endif

                                    @if ($r_sub_parametro->subparametroexame->tipo === "numero" || $r_sub_parametro->subparametroexame->tipo === "booleano")
                                    <td class="align-middle">
                                        @if ($dados->status != "CONCLUIDO" && $editar == true)
                                        <input type="text" class="form-control form-control-sm resultado-input" data-id="{{ $r_sub_parametro->id }}" value="{{ $r_sub_parametro->valor ?? "N/A"  }}">
                                        @else
                                        {{ $r_sub_parametro->valor ?? "N/A" }}
                                        @endif
                                    </td>
                                    @endif

                                    @if ($r_sub_parametro->subparametroexame->tipo == "lista")
                                    <td class="align-middle">
                                        @if ($dados->status != "CONCLUIDO" && $editar == true)
                                        <select class="form-control form-control-sm resultado-input" data-id="{{ $r_sub_parametro->id }}">
                                            <option value="">N/A</option>
                                            @php $opcoes = array_filter(explode(';', $r_sub_parametro->subparametroexame->opcoes)); @endphp
                                            @foreach ($opcoes as $opcao)
                                            <option value="{{ trim($opcao) }}" {{ $r_sub_parametro->valor == trim($opcao) ? 'selected' : ''  }}>{{ trim($opcao) }}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        {{ $r_sub_parametro->valor ?? "N/A" }}
                                        @endif
                                    </td>
                                    @endif

                                    @if ($r_sub_parametro->subparametroexame->tipo === "data")
                                    <td class="align-middle">
                                        @if ($dados->status != "CONCLUIDO" && $editar == true)
                                        <input type="date" class="form-control form-control-sm resultado-input" data-id="{{ $r_sub_parametro->id }}" value="{{ $r_sub_parametro->valor }}">
                                        @else
                                        {{ $r_sub_parametro->valor }}
                                        @endif
                                    </td>
                                    @endif

                                    @if ($r_sub_parametro->subparametroexame->tipo == "lista")
                                    @php
                                    $opcoes = array_filter(explode(';', $r_sub_parametro->subparametroexame->opcoes));
                                    @endphp
                                    <td>
                                        <ul>
                                            @foreach ($opcoes as $opcao)
                                            <li>{{ trim($opcao) }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    @else
                                    @if ($r_sub_parametro->subparametroexame->tipo == "booleano")
                                    <td class="align-middle">{{ $r_sub_parametro->subparametroexame->texto_sim ?? '----------' }} / {{ $r_sub_parametro->subparametroexame->texto_nao ?? '----------' }}</td>
                                    @else
                                    <td class="align-middle">{{ $r_sub_parametro->subparametroexame->unidade ?? '----------' }}</td>
                                    @endif
                                    @endif
                                    <td>{{ $r_sub_parametro->subparametroexame->valor_referencia ?? '----------' }}</td>

                                </tr>
                                @endif
                                @endforeach

                                @foreach ($r_parametro_exame->resultadosubparamentrosImagem as $r_sub_parametro_imagem)
                                @if ($r_sub_parametro_imagem->subparametroexame->tipo === "imagem")
                                <tr>
                                    <td class="align-middle">{{ $r_sub_parametro_imagem->subparametroexame->codigo ?? '----------' }}</td>
                                    <td class="align-middle">{{ $r_sub_parametro_imagem->subparametroexame->nome ?? '----------' }}</td>

                                    <td class="align-middle">
                                        @if ($dados->status != "CONCLUIDO" && $editar == true)
                                        <textarea data-id="{{ $r_sub_parametro_imagem->id }}" rows="2" class="form-control form-control-sm resultado-descricao mb-2" placeholder="Descrição Basica">{{ $r_sub_parametro_imagem->descricao }}</textarea>
                                        <input type="file" class="form-control form-control-sm imagem-input" data-id="{{ $r_sub_parametro_imagem->id }}" multiple>
                                        @else
                                        {{ $r_sub_parametro_imagem->descricao }}
                                        @endif
                                    </td>
                                    @if ($r_sub_parametro_imagem->subparametroexame->tipo == "lista")
                                    <td class="align-middle">{{ $r_sub_parametro_imagem->subparametroexame->opcoes ?? '----------' }}</td>
                                    @else
                                    @if ($r_sub_parametro_imagem->subparametroexame->tipo == "booleano")
                                    <td class="align-middle">{{ $r_sub_parametro_imagem->subparametroexame->texto_sim ?? '----------' }}/{{ $r_sub_parametro_imagem->subparametroexame->texto_nao ?? '----------' }}</td>
                                    @else
                                    <td class="align-middle">{{ $r_sub_parametro_imagem->subparametroexame->unidade ?? '----------' }}</td>
                                    @endif
                                    @endif
                                    <td class="align-middle">{{ $r_sub_parametro_imagem->subparametroexame->valor_referencia ?? '----------' }}</td>
                                    <td class="text-right align-middle">
                                        <button class="btn btn-sm btn-info btn-ver-imagens" data-paramento="{{ $r_sub_parametro_imagem->id }}" data-imagens='@json($r_sub_parametro_imagem->ficheiro ?? [])'>
                                            Ver Imagens
                                        </button>
                                    </td>
                                </tr>
                                @endif
                                @endforeach

                                @endforeach
                            </tbody>
                        </table>
                    </td>

                </tr>
                <tr>
                    <td colspan="6" class="text-center">
                        <a target="_blink" href="{{ route('exames-imprimir', $dados->id) }}" class="btn btn-light-primary"><i class="fas fa-file-pdf"></i> {{ __('messages.imprimir') }} </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

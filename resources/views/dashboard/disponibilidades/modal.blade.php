<div class="modal fade" id="modalDisponibilidade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Disponibilidade</h4>
            </div>
            <div class="modal-body">
                <form id="formDisponibilidade">
                    @csrf
                    <input type="hidden" id="id">
                    <input type="hidden" id="inicio">
                    <input type="hidden" id="fim">
                    <div class="form-group">
                        <label for="medico_id">Médico</label>
                        <select class="form-control select2" id="medico_id" name="medico_id">
                            <option value="">Escolher</option>
                            @foreach($medicos as $m)
                            <option value="{{$m->id}}">{{$m->funcionario->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select class="form-control" name="estado" id="estado">
                            <option value="Disponivel">Disponível</option>
                            <option value="Ferias">Férias</option>
                            <option value="Licenca">Licença</option>
                            <option value="Indisponivel">Indisponível</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="obs">Observação</label>
                        <textarea class="form-control" name="obs" id="obs"></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-danger" id="btnExcluir">
                    Excluir
                </button>

                <button class="btn btn-primary" id="salvar">
                    Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- equipes/_modal_escala.blade.php -->
<div class="modal fade" id="modalEscala{{ $medico->profissional->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="{{ url('/equipes/'.$equipe->id.'/definir-horario') }}">
            @csrf
            <input type="hidden" name="medico_id" value="{{ $medico->profissional->id }}">
            <div class="modal-content py-5">
                <div class="modal-header">
                    <h5 class="modal-title">Definir Escala - {{ $medico->profissional->nome }}</h5>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    @foreach (['segunda','terca','quarta','quinta','sexta','sabado','domingo'] as $dia)
                    <div class="col-md-6">
                        <label class="form-label">{{ ucfirst($dia) }}</label>
                        <div class="input-group">
                            <input type="time" name="horarios[{{ $dia }}][inicio]" class="form-control">
                            <input type="time" name="horarios[{{ $dia }}][fim]" class="form-control">
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('messages.cancelar') }} </button>
                </div>
            </div>
        </form>
    </div>
</div>

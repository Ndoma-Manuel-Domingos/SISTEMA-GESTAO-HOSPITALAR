<div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Novo Estudante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="studentForm">
                    <input type="hidden" name="id" id="student_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nome</label>
                            <input type="text" name="first_name" id="first_name" class="form-control">
                            <div class="invalid-feedback" id="error_first_name"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Sobrenome</label>
                            <input type="text" name="last_name" id="last_name" class="form-control">
                            <div class="invalid-feedback" id="error_last_name"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                            <div class="invalid-feedback" id="error_email"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Telefone</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                            <div class="invalid-feedback" id="error_phone"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Curso</label>
                            <input type="text" name="course" id="course" class="form-control">
                            <div class="invalid-feedback" id="error_course"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Data de inscrição</label>
                            <input type="date" name="enrolled_at" id="enrolled_at" class="form-control">
                            <div class="invalid-feedback" id="error_enrolled_at"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">{{ __('messages.fechar') }}</button>
                <button type="button" id="saveStudent" class="btn btn-light-primary">{{ __('messages.salvar') }}</button>
            </div>
        </div>
    </div>
</div>

// public/js/students.js
$(function () {
    const $table = $('#studentsTable');
    const $filterForm = $('#filterForm');

    function loadStudents(url, data = {}) {
        url = url || '/students/list';
        $.ajax({
            url: url,
            method: 'GET',
            data: data,
            dataType: 'json',
            beforeSend() {
                $table.html('<div class="text-center py-5">Carregando...</div>');
            },
            success(res) {
                $table.html(res.table + res.pagination);
                $('#paginationLinks a').on('click', function (e) {
                    e.preventDefault();
                    const pageUrl = $(this).attr('href');
                    loadStudents(pageUrl, $filterForm.serialize());
                });
            },
            error(err) {
                $table.html('<div class="alert alert-danger">Erro ao carregar estudantes.</div>');
            }
        });
    }

    // initial load
    loadStudents('/students/list');

    // filters
    $filterForm.on('submit', function (e) {
        e.preventDefault();
        loadStudents('/students/list', $(this).serialize());
    });
    $('#clearFilters').on('click', function () {
        $filterForm[0].reset();
        loadStudents('/students/list');
    });

    // new
    $('#btnNew').on('click', function () {
        $('#studentForm')[0].reset();
        $('#student_id').val('');
        $('#modalTitle').text('Novo Estudante');
        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');
        new bootstrap.Modal($('#studentModal')).show();
    });

    // save (create/update)
    $('#saveStudent').on('click', function () {
        const id = $('#student_id').val();
        const url = id ? `/students/${id}` : '/students';
        const method = id ? 'PUT' : 'POST';
        const data = $('#studentForm').serialize();

        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');

        $.ajax({
            url: url,
            method: method,
            data: data,
            success(res) {
                new bootstrap.Modal($('#studentModal')).hide();
                loadStudents('/students/list', $filterForm.serialize());
            },
            error(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for (const key in errors) {
                        $(`#error_${key}`).text(errors[key][0]);
                        $(`[name="${key}"]`).addClass('is-invalid');
                    }
                } else {
                    alert('Erro inesperado.');
                }
            }
        });
    });

    // edit
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(`/students/${id}`, function (data) {
            $('#student_id').val(data.id);
            $('#first_name').val(data.first_name);
            $('#last_name').val(data.last_name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#course').val(data.course);
            $('#enrolled_at').val(data.enrolled_at ? data.enrolled_at.substr(0, 10) : '');
            $('#modalTitle').text('Editar Estudante');
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');
            new bootstrap.Modal($('#studentModal')).show();
        }).fail(function () {
            alert('Não foi possível obter os dados do estudante.');
        });
    });

    // delete
    $(document).on('click', '.btn-delete', function () {
        if (!confirm('Tem a certeza que deseja eliminar este estudante?')) return;
        const id = $(this).data('id');
        $.ajax({
            url: `/students/${id}`,
            method: 'DELETE',
            success() {
                loadStudents('/students/list', $filterForm.serialize());
            },
            error() {
                alert('Erro ao eliminar.');
            }
        });
    });
});

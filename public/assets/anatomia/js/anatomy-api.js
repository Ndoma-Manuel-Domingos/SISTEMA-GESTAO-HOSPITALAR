class AnatomyApi {
    static buscar(codigo) {
        $.ajax({
            url: '/anatomia/detalhes',
            type: 'GET',
            data: {
                codigo: codigo
            },
            success: function (resp) {
                AnatomyModal.show(
                    resp
                );
            }
        });
    }
}

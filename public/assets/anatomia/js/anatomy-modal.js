class AnatomyModal {

    static show(response) {
        if (!response.success) {
            Swal.fire({
                icon: 'warning',
                title: 'Aviso',
                text: 'Informação não encontrada.'
            });

            return;
        }

        let parte = response.parte;
        let exames = response.exames;
        let doencas = response.doencas;

        let listaExames = '';
        let listaDoencas = '';

        exames.forEach(function (item) {
            listaExames += `
                <li>${item.nome}</li>
            `;
        });

        doencas.forEach(function (item) {
            listaDoencas += `
                <li>${item.nome}</li>
            `;
        });

        Swal.fire({
            width: '900px',
            title: parte.nome,
            html: `
                <div class="text-left">
                    <h5><b>Sistema</b></h5>
                    <p>${parte.sistema ?? '-'}</p>
                    <hr>
                    <h5><b>Descrição</b></h5>
                    <p>${parte.descricao ?? '-'}</p>
                    <hr>
                    <h5><b>Doenças Relacionadas</b></h5>
                    <ul>
                        ${listaDoencas}
                    </ul>
                    <hr>
                    <h5><b>Exames Recomendados</b></h5>
                    <ul>
                        ${listaExames}
                    </ul>
                </div>
            `,
            confirmButtonText: 'Fechar'
        });
    }

}

<?php

/** Obstétrica - Ginecológicas - Cardiovascular - Abdominal e Pélvica - Urológica - Músculo-esqueléticas*/

$dadosExame = [
    'codigo' => 'OBS001',
    'nome' => 'Ultrassonografia Obstétrica',
    'categoria' => 'Obstétrica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Trimestre Gestacional',
                    'opcoes' => 'Primeiro;Segundo;Terceiro'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Idade Gestacional',
                    'unidade' => 'semanas',
                    'valor_referencia' => '0 - 42',
                    'valor_minimo' => 0,
                    'valor_maximo' => 42
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Solicitante',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO FETAL
        //==========================================

        [
            'nome' => 'Avaliação Fetal',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Frequência Cardíaca Fetal',
                    'unidade' => 'bpm',
                    'valor_referencia' => '110 - 160',
                    'valor_minimo' => 110,
                    'valor_maximo' => 160
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Apresentação Fetal',
                    'opcoes' => 'Cefálica;Pélvica;Transversa;Oblíqua'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Movimentos Fetais',
                    'opcoes' => 'Presentes;Diminuídos;Ausentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Batimentos Cardíacos',
                    'opcoes' => 'Presentes;Ausentes'
                ],

            ]

        ],

        //==========================================
        // BIOMETRIA FETAL
        //==========================================

        [
            'nome' => 'Biometria Fetal',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro Biparietal (DBP)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Circunferência Cefálica (CC)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 400',
                    'valor_minimo' => 0,
                    'valor_maximo' => 400
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Circunferência Abdominal (CA)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 400',
                    'valor_minimo' => 0,
                    'valor_maximo' => 400
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Fêmur (CF)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Peso Fetal Estimado',
                    'unidade' => 'g',
                    'valor_referencia' => '0 - 6000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 6000
                ],

            ]

        ],

        //==========================================
        // PLACENTA E LÍQUIDO
        //==========================================

        [
            'nome' => 'Placenta e Líquido Amniótico',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Localização da Placenta',
                    'opcoes' => 'Anterior;Posterior;Fúndica;Lateral Direita;Lateral Esquerda;Prévia'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau da Placenta',
                    'opcoes' => '0;I;II;III'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Líquido Amniótico (ILA)',
                    'unidade' => 'cm',
                    'valor_referencia' => '5 - 25',
                    'valor_minimo' => 0,
                    'valor_maximo' => 40
                ],

            ]

        ],

        //==========================================
        // ACHADOS
        //==========================================

        [
            'nome' => 'Achados',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ]

            ]

        ]

    ]
];

$dadosExame = [
    'codigo' => 'OBS002',
    'nome' => 'Ultrassonografia Morfológica Fetal',
    'categoria' => 'Obstétrica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Período da Avaliação',
                    'opcoes' => 'Primeiro Trimestre;Segundo Trimestre;Terceiro Trimestre'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Idade Gestacional',
                    'unidade' => 'semanas',
                    'valor_referencia' => '11 - 42',
                    'valor_minimo' => 11,
                    'valor_maximo' => 42
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // BIOMETRIA FETAL
        //==========================================

        [
            'nome' => 'Biometria Fetal',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro Biparietal (DBP)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Circunferência Cefálica (CC)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 400',
                    'valor_minimo' => 0,
                    'valor_maximo' => 400
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Circunferência Abdominal (CA)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 400',
                    'valor_minimo' => 0,
                    'valor_maximo' => 400
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Fêmur (CF)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Peso Fetal Estimado',
                    'unidade' => 'g',
                    'valor_referencia' => '0 - 6000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 6000
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO ANATÔMICA FETAL
        //==========================================

        [
            'nome' => 'Avaliação Anatômica Fetal',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Crânio',
                    'opcoes' => 'Normal;Alterado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Face',
                    'opcoes' => 'Normal;Alterado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Coluna Vertebral',
                    'opcoes' => 'Normal;Alterada;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tórax',
                    'opcoes' => 'Normal;Alterado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Coração',
                    'opcoes' => 'Normal;Alterado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Abdómen',
                    'opcoes' => 'Normal;Alterado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Rins',
                    'opcoes' => 'Normais;Alterados;Não Avaliados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Bexiga',
                    'opcoes' => 'Normal;Alterada;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Membros Superiores',
                    'opcoes' => 'Normais;Alterados;Não Avaliados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Membros Inferiores',
                    'opcoes' => 'Normais;Alterados;Não Avaliados'
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO FETAL
        //==========================================

        [
            'nome' => 'Avaliação Fetal',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Frequência Cardíaca Fetal',
                    'unidade' => 'bpm',
                    'valor_referencia' => '110 - 160',
                    'valor_minimo' => 110,
                    'valor_maximo' => 160
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Movimentos Fetais',
                    'opcoes' => 'Presentes;Diminuídos;Ausentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Apresentação Fetal',
                    'opcoes' => 'Cefálica;Pélvica;Transversa;Oblíqua'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sexo Fetal',
                    'opcoes' => 'Masculino;Feminino;Indeterminado;Não Informado'
                ],

            ]

        ],

        //==========================================
        // PLACENTA E LÍQUIDO AMNIÓTICO
        //==========================================

        [
            'nome' => 'Placenta e Líquido Amniótico',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Localização da Placenta',
                    'opcoes' => 'Anterior;Posterior;Fúndica;Lateral Direita;Lateral Esquerda;Prévia'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Maturidade Placentária',
                    'opcoes' => '0;I;II;III'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Líquido Amniótico (ILA)',
                    'unidade' => 'cm',
                    'valor_referencia' => '5 - 25',
                    'valor_minimo' => 0,
                    'valor_maximo' => 40
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cordão Umbilical',
                    'opcoes' => 'Normal;Artéria Umbilical Única;Circular Cervical;Alterado'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Achados Morfológicos',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ]

            ]

        ],

    ]

];

$dadosExame = [
    'codigo' => 'OBS003',
    'nome' => 'Ultrassonografia Obstétrica com Doppler',
    'categoria' => 'Obstétrica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Idade Gestacional',
                    'unidade' => 'semanas',
                    'valor_referencia' => '20 - 42',
                    'valor_minimo' => 20,
                    'valor_maximo' => 42
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Gestação',
                    'opcoes' => 'Única;Gemelar;Trigemelar;Múltipla'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO FETAL
        //==========================================

        [
            'nome' => 'Avaliação Fetal',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Frequência Cardíaca Fetal',
                    'unidade' => 'bpm',
                    'valor_referencia' => '110 - 160',
                    'valor_minimo' => 110,
                    'valor_maximo' => 160
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Apresentação Fetal',
                    'opcoes' => 'Cefálica;Pélvica;Transversa;Oblíqua'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Movimentos Fetais',
                    'opcoes' => 'Presentes;Diminuídos;Ausentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vitalidade Fetal',
                    'opcoes' => 'Preservada;Comprometida'
                ],

            ]

        ],

        //==========================================
        // BIOMETRIA FETAL
        //==========================================

        [
            'nome' => 'Biometria Fetal',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro Biparietal (DBP)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Circunferência Cefálica (CC)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 400',
                    'valor_minimo' => 0,
                    'valor_maximo' => 400
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Circunferência Abdominal (CA)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 400',
                    'valor_minimo' => 0,
                    'valor_maximo' => 400
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Fêmur (CF)',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Peso Fetal Estimado',
                    'unidade' => 'g',
                    'valor_referencia' => '0 - 6000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 6000
                ],

            ]

        ],

        //==========================================
        // PLACENTA E LÍQUIDO AMNIÓTICO
        //==========================================

        [
            'nome' => 'Placenta e Líquido Amniótico',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Localização da Placenta',
                    'opcoes' => 'Anterior;Posterior;Fúndica;Lateral Direita;Lateral Esquerda;Prévia'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau Placentário',
                    'opcoes' => '0;I;II;III'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Líquido Amniótico (ILA)',
                    'unidade' => 'cm',
                    'valor_referencia' => '5 - 25',
                    'valor_minimo' => 0,
                    'valor_maximo' => 40
                ],

            ]

        ],

        //==========================================
        // DOPPLERFUXOMETRIA
        //==========================================

        [
            'nome' => 'Dopplerfluxometria',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Artéria Umbilical - Índice de Resistência (IR)',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Artéria Umbilical - Índice de Pulsatilidade (IP)',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 3
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Artéria Cerebral Média - Índice de Pulsatilidade (IP)',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 3
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Artérias Uterinas - Índice de Resistência (IR)',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Diastólico da Artéria Umbilical',
                    'opcoes' => 'Normal;Reduzido;Ausente;Reverso'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Centralização Fetal',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Achados do Exame',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ]

            ]

        ],

    ]

];

$dadosExame = [
    'codigo' => 'OBS004',
    'nome' => 'Ultrassonografia Obstétrica Transvaginal',
    'categoria' => 'Obstétrica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Idade Gestacional',
                    'unidade' => 'semanas',
                    'valor_referencia' => '4 - 16',
                    'valor_minimo' => 4,
                    'valor_maximo' => 16
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Gestação',
                    'opcoes' => 'Única;Gemelar;Trigemelar;Múltipla'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO GESTACIONAL
        //==========================================

        [
            'nome' => 'Avaliação Gestacional',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Saco Gestacional',
                    'opcoes' => 'Visualizado;Não Visualizado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro Médio do Saco Gestacional',
                    'unidade' => 'mm',
                    'valor_referencia' => '2 - 60',
                    'valor_minimo' => 2,
                    'valor_maximo' => 60
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vesícula Vitelínica',
                    'opcoes' => 'Visualizada;Não Visualizada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Embrião',
                    'opcoes' => 'Visualizado;Não Visualizado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento Cabeça-Nádega (CCN)',
                    'unidade' => 'mm',
                    'valor_referencia' => '2 - 90',
                    'valor_minimo' => 2,
                    'valor_maximo' => 90
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Frequência Cardíaca Embrionária',
                    'unidade' => 'bpm',
                    'valor_referencia' => '110 - 180',
                    'valor_minimo' => 110,
                    'valor_maximo' => 180
                ],

            ]

        ],

        //==========================================
        // ÚTERO E COLO UTERINO
        //==========================================

        [
            'nome' => 'Útero e Colo Uterino',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Colo Uterino',
                    'unidade' => 'mm',
                    'valor_referencia' => '25 - 50',
                    'valor_minimo' => 0,
                    'valor_maximo' => 60
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Canal Cervical',
                    'opcoes' => 'Fechado;Entreaberto;Aberto'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Miométrio',
                    'opcoes' => 'Normal;Alterado'
                ],

            ]

        ],

        //==========================================
        // ANEXOS UTERINOS
        //==========================================

        [
            'nome' => 'Anexos Uterinos',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Ovário Direito',
                    'opcoes' => 'Normal;Cisto;Massa;Não Visualizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ovário Esquerdo',
                    'opcoes' => 'Normal;Cisto;Massa;Não Visualizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Corpo Lúteo',
                    'opcoes' => 'Presente;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Líquido Livre em Fundo de Saco',
                    'opcoes' => 'Ausente;Pequena Quantidade;Moderado;Importante'
                ],

            ]

        ],

        //==========================================
        // ACHADOS
        //==========================================

        [
            'nome' => 'Achados',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ]

            ]

        ],

    ]

];


$dadosExame = [
    'codigo' => 'OBS005',
    'nome' => 'Dopplerfluxometria Fetal',
    'categoria' => 'Obstétrica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Idade Gestacional',
                    'unidade' => 'semanas',
                    'valor_referencia' => '20 - 42',
                    'valor_minimo' => 20,
                    'valor_maximo' => 42
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Gestação',
                    'opcoes' => 'Única;Gemelar;Trigemelar;Múltipla'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO FETAL
        //==========================================

        [
            'nome' => 'Avaliação Fetal',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Frequência Cardíaca Fetal',
                    'unidade' => 'bpm',
                    'valor_referencia' => '110 - 160',
                    'valor_minimo' => 110,
                    'valor_maximo' => 160
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Movimentos Fetais',
                    'opcoes' => 'Presentes;Diminuídos;Ausentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vitalidade Fetal',
                    'opcoes' => 'Preservada;Comprometida'
                ],

            ]

        ],

        //==========================================
        // ARTÉRIA UMBILICAL
        //==========================================

        [
            'nome' => 'Artéria Umbilical',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Pulsatilidade (IP)',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência (IR)',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Relação Sístole/Diástole (S/D)',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Diastólico',
                    'opcoes' => 'Normal;Reduzido;Ausente;Reverso'
                ],

            ]

        ],

        //==========================================
        // ARTÉRIA CEREBRAL MÉDIA
        //==========================================

        [
            'nome' => 'Artéria Cerebral Média',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Pulsatilidade (IP)',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência (IR)',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima',
                    'unidade' => 'cm/s',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 200
                ],

            ]

        ],

        //==========================================
        // ARTÉRIAS UTERINAS
        //==========================================

        [
            'nome' => 'Artérias Uterinas',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Pulsatilidade Médio',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Médio',
                    'unidade' => '',
                    'valor_referencia' => 'Conforme idade gestacional',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Incisura Protodiastólica',
                    'opcoes' => 'Ausente;Unilateral;Bilateral'
                ],

            ]

        ],

        //==========================================
        // DUCTO VENOSO
        //==========================================

        [
            'nome' => 'Ducto Venoso',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo do Ducto Venoso',
                    'opcoes' => 'Normal;Alterado;Onda A Ausente;Onda A Reversa'
                ],

            ]

        ],

        //==========================================
        // INTERPRETAÇÃO
        //==========================================

        [
            'nome' => 'Interpretação',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Centralização Fetal',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Insuficiência Placentária',
                    'opcoes' => 'Não Evidenciada;Leve;Moderada;Grave'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão',
                    'tamanho_maximo' => 2500
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ]

            ]

        ],

    ]

];


$dadosExame = [
    'codigo' => 'GIN001',
    'nome' => 'Ultrassonografia Pélvica',
    'categoria' => 'Ginecológica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Via de Exame',
                    'opcoes' => 'Suprapúbica;Transabdominal'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // ÚTERO
        //==========================================

        [
            'nome' => 'Útero',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento',
                    'unidade' => 'mm',
                    'valor_referencia' => '60 - 90',
                    'valor_minimo' => 40,
                    'valor_maximo' => 120
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura',
                    'unidade' => 'mm',
                    'valor_referencia' => '40 - 60',
                    'valor_minimo' => 20,
                    'valor_maximo' => 80
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura',
                    'unidade' => 'mm',
                    'valor_referencia' => '30 - 50',
                    'valor_minimo' => 20,
                    'valor_maximo' => 70
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Posição do Útero',
                    'opcoes' => 'Anteversoflexão;Retroversoflexão;Mediano'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Contornos',
                    'opcoes' => 'Regulares;Irregulares'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Miométrio',
                    'opcoes' => 'Homogêneo;Heterogêneo'
                ],

            ]

        ],

        //==========================================
        // ENDOMÉTRIO
        //==========================================

        [
            'nome' => 'Endométrio',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura Endometrial',
                    'unidade' => 'mm',
                    'valor_referencia' => '1 - 16',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto Endometrial',
                    'opcoes' => 'Homogêneo;Heterogêneo'
                ],

            ]

        ],

        //==========================================
        // OVÁRIO DIREITO
        //==========================================

        [
            'nome' => 'Ovário Direito',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume',
                    'unidade' => 'cm³',
                    'valor_referencia' => '3 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto',
                    'opcoes' => 'Normal;Cisto;Massa;Policístico;Não Visualizado'
                ],

            ]

        ],

        //==========================================
        // OVÁRIO ESQUERDO
        //==========================================

        [
            'nome' => 'Ovário Esquerdo',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume',
                    'unidade' => 'cm³',
                    'valor_referencia' => '3 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto',
                    'opcoes' => 'Normal;Cisto;Massa;Policístico;Não Visualizado'
                ],

            ]

        ],

        //==========================================
        // CAVIDADE PÉLVICA
        //==========================================

        [
            'nome' => 'Cavidade Pélvica',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Líquido Livre',
                    'opcoes' => 'Ausente;Pequena Quantidade;Moderado;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Massas Pélvicas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];

$dadosExame = [
    'codigo' => 'GIN002',
    'nome' => 'Ultrassonografia Transvaginal',
    'categoria' => 'Ginecológica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // ÚTERO
        //==========================================

        [
            'nome' => 'Útero',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento',
                    'unidade' => 'mm',
                    'valor_referencia' => '60 - 90',
                    'valor_minimo' => 40,
                    'valor_maximo' => 120
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura',
                    'unidade' => 'mm',
                    'valor_referencia' => '40 - 60',
                    'valor_minimo' => 20,
                    'valor_maximo' => 80
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura',
                    'unidade' => 'mm',
                    'valor_referencia' => '30 - 50',
                    'valor_minimo' => 20,
                    'valor_maximo' => 70
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Posição Uterina',
                    'opcoes' => 'Anteversoflexão;Retroversoflexão;Mediano'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Contornos',
                    'opcoes' => 'Regulares;Irregulares'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Miométrio',
                    'opcoes' => 'Homogêneo;Heterogêneo;Miomatoso'
                ],

            ]

        ],

        //==========================================
        // ENDOMÉTRIO
        //==========================================

        [
            'nome' => 'Endométrio',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura Endometrial',
                    'unidade' => 'mm',
                    'valor_referencia' => '1 - 16',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto',
                    'opcoes' => 'Homogêneo;Heterogêneo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cavidade Endometrial',
                    'opcoes' => 'Normal;Conteúdo Líquido;Pólipo;Alterada'
                ],

            ]

        ],

        //==========================================
        // OVÁRIO DIREITO
        //==========================================

        [
            'nome' => 'Ovário Direito',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume',
                    'unidade' => 'cm³',
                    'valor_referencia' => '3 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto',
                    'opcoes' => 'Normal;Folículos;Cisto Simples;Cisto Complexo;Policístico;Massa;Não Visualizado'
                ],

            ]

        ],

        //==========================================
        // OVÁRIO ESQUERDO
        //==========================================

        [
            'nome' => 'Ovário Esquerdo',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume',
                    'unidade' => 'cm³',
                    'valor_referencia' => '3 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto',
                    'opcoes' => 'Normal;Folículos;Cisto Simples;Cisto Complexo;Policístico;Massa;Não Visualizado'
                ],

            ]

        ],

        //==========================================
        // ANEXOS E CAVIDADE PÉLVICA
        //==========================================

        [
            'nome' => 'Anexos e Cavidade Pélvica',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Líquido Livre no Fundo de Saco',
                    'opcoes' => 'Ausente;Pequena Quantidade;Moderado;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Massas Anexiais',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidrossalpinge',
                    'opcoes' => 'Ausente;Direita;Esquerda;Bilateral'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Achados Ultrassonográficos',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];

$dadosExame = [
    'codigo' => 'GIN003',
    'nome' => 'Ultrassonografia das Mamas',
    'categoria' => 'Ginecológica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Mama Avaliada',
                    'opcoes' => 'Direita;Esquerda;Bilateral'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO DAS MAMAS
        //==========================================

        [
            'nome' => 'Avaliação das Mamas',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão Ecográfico do Parênquima Mamário',
                    'opcoes' => 'Homogêneo;Heterogêneo;Predomínio Adiposo;Predomínio Fibroglandular'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Assimetria Mamária',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alterações Cutâneas',
                    'opcoes' => 'Ausentes;Espessamento Cutâneo;Retração;Outras Alterações'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alterações Subcutâneas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // LESÕES NODULARES
        //==========================================

        [
            'nome' => 'Lesões Nodulares',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Nódulo',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Localização do Nódulo',
                    'opcoes' => 'Quadrante Superior Externo;Quadrante Superior Interno;Quadrante Inferior Externo;Quadrante Inferior Interno;Região Retroareolar;Outro'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro do Nódulo',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 50',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Formato do Nódulo',
                    'opcoes' => 'Oval;Redondo;Irregular'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Margens do Nódulo',
                    'opcoes' => 'Circunscritas;Microlobuladas;Indistintas;Anguladas;Espiculadas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Orientação do Nódulo',
                    'opcoes' => 'Paralela à Pele;Não Paralela à Pele'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade do Nódulo',
                    'opcoes' => 'Anecoico;Hipoecogênico;Isoecogênico;Hiperecogênico;Complexo'
                ],

            ]

        ],

        //==========================================
        // CISTOS MAMÁRIOS
        //==========================================

        [
            'nome' => 'Cistos Mamários',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Cistos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Cisto',
                    'opcoes' => 'Simples;Complicado;Complexo'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Dimensão do Cisto',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 50',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

            ]

        ],

        //==========================================
        // LINFONODOS AXILARES
        //==========================================

        [
            'nome' => 'Linfonodos Axilares',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação dos Linfonodos Axilares',
                    'opcoes' => 'Normais;Alterados;Não Avaliados'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro Linfonodal',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 20',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto Cortical',
                    'opcoes' => 'Preservado;Espessado;Alterado'
                ],

            ]

        ],

        //==========================================
        // CLASSIFICAÇÃO BI-RADS
        //==========================================

        [
            'nome' => 'Classificação BI-RADS',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Categoria BI-RADS',
                    'opcoes' => 'BI-RADS 0;BI-RADS 1;BI-RADS 2;BI-RADS 3;BI-RADS 4;BI-RADS 5;BI-RADS 6'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];

$dadosExame = [
    'codigo' => 'GIN004',
    'nome' => 'Ultrassonografia Pélvica com Doppler',
    'categoria' => 'Ginecológica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Via de Exame',
                    'opcoes' => 'Suprapúbica;Transabdominal'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // ÚTERO
        //==========================================

        [
            'nome' => 'Útero',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento Uterino',
                    'unidade' => 'mm',
                    'valor_referencia' => '60 - 90',
                    'valor_minimo' => 40,
                    'valor_maximo' => 120
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura Uterina',
                    'unidade' => 'mm',
                    'valor_referencia' => '40 - 60',
                    'valor_minimo' => 20,
                    'valor_maximo' => 80
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura Uterina',
                    'unidade' => 'mm',
                    'valor_referencia' => '30 - 50',
                    'valor_minimo' => 20,
                    'valor_maximo' => 70
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Posição do Útero',
                    'opcoes' => 'Anteversoflexão;Retroversoflexão;Mediano'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Miométrio',
                    'opcoes' => 'Homogêneo;Heterogêneo;Miomatoso'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização Miometrial ao Doppler',
                    'opcoes' => 'Normal;Aumentada;Reduzida;Ausente'
                ],

            ]

        ],

        //==========================================
        // ENDOMÉTRIO
        //==========================================

        [
            'nome' => 'Endométrio',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura Endometrial',
                    'unidade' => 'mm',
                    'valor_referencia' => '1 - 16',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto Endometrial',
                    'opcoes' => 'Homogêneo;Heterogêneo;Cístico;Irregular'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização Endometrial ao Doppler',
                    'opcoes' => 'Ausente;Periférica;Central;Aumentada'
                ],

            ]

        ],

        //==========================================
        // OVÁRIO DIREITO
        //==========================================

        [
            'nome' => 'Ovário Direito',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Ovário Direito',
                    'unidade' => 'cm³',
                    'valor_referencia' => '3 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto do Ovário Direito',
                    'opcoes' => 'Normal;Folículos;Cisto Simples;Cisto Complexo;Policístico;Massa;Não Visualizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular do Ovário Direito',
                    'opcoes' => 'Normal;Aumentado;Reduzido;Ausente'
                ],

            ]

        ],

        //==========================================
        // OVÁRIO ESQUERDO
        //==========================================

        [
            'nome' => 'Ovário Esquerdo',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Ovário Esquerdo',
                    'unidade' => 'cm³',
                    'valor_referencia' => '3 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto do Ovário Esquerdo',
                    'opcoes' => 'Normal;Folículos;Cisto Simples;Cisto Complexo;Policístico;Massa;Não Visualizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular do Ovário Esquerdo',
                    'opcoes' => 'Normal;Aumentado;Reduzido;Ausente'
                ],

            ]

        ],

        //==========================================
        // DOPPLER DOS OVÁRIOS
        //==========================================

        [
            'nome' => 'Doppler Ovariano',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Ovário Direito',
                    'unidade' => '',
                    'valor_referencia' => '0.4 - 0.8',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Ovário Esquerdo',
                    'unidade' => '',
                    'valor_referencia' => '0.4 - 0.8',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Pulsatilidade Ovário Direito',
                    'unidade' => '',
                    'valor_referencia' => '0.8 - 2.0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Pulsatilidade Ovário Esquerdo',
                    'unidade' => '',
                    'valor_referencia' => '0.8 - 2.0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

            ]

        ],

        //==========================================
        // MASSAS PÉLVICAS
        //==========================================

        [
            'nome' => 'Massas e Lesões Pélvicas',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Massa Pélvica',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro da Massa',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização da Massa ao Doppler',
                    'opcoes' => 'Ausente;Periférica;Central;Mista;Aumentada'
                ],

            ]

        ],

        //==========================================
        // CAVIDADE PÉLVICA
        //==========================================

        [
            'nome' => 'Cavidade Pélvica',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Líquido Livre em Fundo de Saco',
                    'opcoes' => 'Ausente;Pequena Quantidade;Moderado;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular Pélvico',
                    'opcoes' => 'Normal;Aumentado;Alterado'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];

$dadosExame = [
    'codigo' => 'GIN005',
    'nome' => 'Ultrassonografia Transvaginal com Doppler',
    'categoria' => 'Ginecológica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // ÚTERO
        //==========================================

        [
            'nome' => 'Útero',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento Uterino',
                    'unidade' => 'mm',
                    'valor_referencia' => '60 - 90',
                    'valor_minimo' => 40,
                    'valor_maximo' => 120
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura Uterina',
                    'unidade' => 'mm',
                    'valor_referencia' => '40 - 60',
                    'valor_minimo' => 20,
                    'valor_maximo' => 80
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura Uterina',
                    'unidade' => 'mm',
                    'valor_referencia' => '30 - 50',
                    'valor_minimo' => 20,
                    'valor_maximo' => 70
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Posição do Útero',
                    'opcoes' => 'Anteversoflexão;Retroversoflexão;Mediano'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Miométrio',
                    'opcoes' => 'Homogêneo;Heterogêneo;Miomatoso;Com Lesões Focais'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização Miometrial ao Doppler',
                    'opcoes' => 'Normal;Aumentada;Reduzida;Ausente'
                ],

            ]

        ],

        //==========================================
        // ENDOMÉTRIO
        //==========================================

        [
            'nome' => 'Endométrio',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura Endometrial',
                    'unidade' => 'mm',
                    'valor_referencia' => '1 - 16',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão Endometrial',
                    'opcoes' => 'Proliferativo;Secretor;Atrófico;Heterogêneo;Irregular'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização Endometrial ao Doppler',
                    'opcoes' => 'Ausente;Periférica;Central;Aumentada'
                ],

            ]

        ],

        //==========================================
        // OVÁRIO DIREITO
        //==========================================

        [
            'nome' => 'Ovário Direito',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Ovário Direito',
                    'unidade' => 'cm³',
                    'valor_referencia' => '3 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto do Ovário Direito',
                    'opcoes' => 'Normal;Folículos;Cisto Simples;Cisto Complexo;Policístico;Massa;Não Visualizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular do Ovário Direito',
                    'opcoes' => 'Presente;Aumentado;Reduzido;Ausente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência do Ovário Direito',
                    'unidade' => '',
                    'valor_referencia' => '0.4 - 0.8',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Pulsatilidade do Ovário Direito',
                    'unidade' => '',
                    'valor_referencia' => '0.8 - 2.0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

            ]

        ],

        //==========================================
        // OVÁRIO ESQUERDO
        //==========================================

        [
            'nome' => 'Ovário Esquerdo',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Ovário Esquerdo',
                    'unidade' => 'cm³',
                    'valor_referencia' => '3 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto do Ovário Esquerdo',
                    'opcoes' => 'Normal;Folículos;Cisto Simples;Cisto Complexo;Policístico;Massa;Não Visualizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular do Ovário Esquerdo',
                    'opcoes' => 'Presente;Aumentado;Reduzido;Ausente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência do Ovário Esquerdo',
                    'unidade' => '',
                    'valor_referencia' => '0.4 - 0.8',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Pulsatilidade do Ovário Esquerdo',
                    'unidade' => '',
                    'valor_referencia' => '0.8 - 2.0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

            ]

        ],

        //==========================================
        // LESÕES E MASSAS ANEXIAIS
        //==========================================

        [
            'nome' => 'Lesões e Massas Anexiais',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Massa Anexial',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro da Massa',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização da Massa ao Doppler',
                    'opcoes' => 'Ausente;Periférica;Central;Mista;Aumentada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão Vascular da Lesão',
                    'opcoes' => 'Benigno;Indeterminado;Suspeito'
                ],

            ]

        ],

        //==========================================
        // DOPPLER PÉLVICO
        //==========================================

        [
            'nome' => 'Doppler Pélvico',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência das Artérias Uterinas',
                    'unidade' => '',
                    'valor_referencia' => '0.4 - 0.8',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Pulsatilidade das Artérias Uterinas',
                    'unidade' => '',
                    'valor_referencia' => '0.8 - 2.0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular Pélvico',
                    'opcoes' => 'Normal;Aumentado;Reduzido;Alterado'
                ],

            ]

        ],

        //==========================================
        // CAVIDADE PÉLVICA
        //==========================================

        [
            'nome' => 'Cavidade Pélvica',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Líquido Livre no Fundo de Saco',
                    'opcoes' => 'Ausente;Pequena Quantidade;Moderado;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Massas Pélvicas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];

$dadosExame = [
    'codigo' => 'CAR001',
    'nome' => 'Ecocardiograma Transtorácico',
    'categoria' => 'Cardiovascular',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Ecocardiograma',
                    'opcoes' => 'Repouso;Avaliação Pré-Operatória;Controle Clínico;Urgência'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // CÂMARAS CARDÍACAS
        //==========================================

        [
            'nome' => 'Câmaras Cardíacas',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Átrio Esquerdo',
                    'unidade' => 'mm',
                    'valor_referencia' => '18 - 40',
                    'valor_minimo' => 18,
                    'valor_maximo' => 40
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Ventrículo Esquerdo - Diâmetro Diastólico',
                    'unidade' => 'mm',
                    'valor_referencia' => '35 - 56',
                    'valor_minimo' => 35,
                    'valor_maximo' => 56
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Ventrículo Esquerdo - Diâmetro Sistólico',
                    'unidade' => 'mm',
                    'valor_referencia' => '20 - 40',
                    'valor_minimo' => 20,
                    'valor_maximo' => 40
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura do Septo Interventricular',
                    'unidade' => 'mm',
                    'valor_referencia' => '6 - 12',
                    'valor_minimo' => 6,
                    'valor_maximo' => 12
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Parede Posterior do Ventrículo Esquerdo',
                    'unidade' => 'mm',
                    'valor_referencia' => '6 - 12',
                    'valor_minimo' => 6,
                    'valor_maximo' => 12
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Ventrículo Direito',
                    'unidade' => 'mm',
                    'valor_referencia' => '17 - 42',
                    'valor_minimo' => 17,
                    'valor_maximo' => 42
                ],

            ]

        ],

        //==========================================
        // FUNÇÃO VENTRICULAR
        //==========================================

        [
            'nome' => 'Função Ventricular',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Fração de Ejeção do Ventrículo Esquerdo',
                    'unidade' => '%',
                    'valor_referencia' => '55 - 70',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Encurtamento Fracional',
                    'unidade' => '%',
                    'valor_referencia' => '25 - 45',
                    'valor_minimo' => 0,
                    'valor_maximo' => 60
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Sistólica Global',
                    'opcoes' => 'Normal;Levemente Reduzida;Moderadamente Reduzida;Gravemente Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Diastólica',
                    'opcoes' => 'Normal;Disfunção Grau I;Disfunção Grau II;Disfunção Grau III'
                ],

            ]

        ],

        //==========================================
        // VÁLVULAS CARDÍACAS
        //==========================================

        [
            'nome' => 'Avaliação das Válvulas',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Válvula Mitral',
                    'opcoes' => 'Normal;Estenose;Insuficiência;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Válvula Aórtica',
                    'opcoes' => 'Normal;Estenose;Insuficiência;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Válvula Tricúspide',
                    'opcoes' => 'Normal;Estenose;Insuficiência;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Válvula Pulmonar',
                    'opcoes' => 'Normal;Estenose;Insuficiência;Alterada'
                ],

            ]

        ],

        //==========================================
        // DOPPLER CARDÍACO
        //==========================================

        [
            'nome' => 'Doppler Cardíaco',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Máxima Mitral',
                    'unidade' => 'm/s',
                    'valor_referencia' => '0.6 - 1.3',
                    'valor_minimo' => 0,
                    'valor_maximo' => 3
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Máxima Aórtica',
                    'unidade' => 'm/s',
                    'valor_referencia' => '1.0 - 1.7',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Pressão Sistólica da Artéria Pulmonar',
                    'unidade' => 'mmHg',
                    'valor_referencia' => '15 - 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Refluxo Valvar',
                    'opcoes' => 'Ausente;Leve;Moderado;Importante'
                ],

            ]

        ],

        //==========================================
        // PERICÁRDIO E GRANDES VASOS
        //==========================================

        [
            'nome' => 'Pericárdio e Grandes Vasos',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Derrame Pericárdico',
                    'opcoes' => 'Ausente;Pequeno;Moderado;Grande'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Aorta Ascendente',
                    'unidade' => 'mm',
                    'valor_referencia' => '20 - 37',
                    'valor_minimo' => 15,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Veia Cava Inferior',
                    'unidade' => 'mm',
                    'valor_referencia' => '≤ 21',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];

$dadosExame = [
    'codigo' => 'CAR002',
    'nome' => 'Ecocardiograma Transesofágico',
    'categoria' => 'Cardiovascular',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Indicação do Exame',
                    'opcoes' => 'Pesquisa de Trombos;Avaliação Valvar;Endocardite;Avaliação Pré-Cirúrgica;Pesquisa de Comunicação Interatrial;Outro'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Sedação Utilizada',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // PROCEDIMENTO E QUALIDADE DO EXAME
        //==========================================

        [
            'nome' => 'Procedimento',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Qualidade da Imagem',
                    'opcoes' => 'Adequada;Limitada;Inadequada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Introdução do Transdutor',
                    'opcoes' => 'Sem Dificuldade;Dificuldade Técnica;Não Realizada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Contraste Utilizado',
                    'opcoes' => 'Não Utilizado;Utilizado'
                ],

            ]

        ],

        //==========================================
        // ÁTRIOS
        //==========================================

        [
            'nome' => 'Avaliação dos Átrios',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Átrio Esquerdo',
                    'unidade' => 'ml',
                    'valor_referencia' => '18 - 58',
                    'valor_minimo' => 18,
                    'valor_maximo' => 58
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Átrio Esquerdo',
                    'opcoes' => 'Normal;Dilatação Leve;Dilatação Moderada;Dilatação Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Átrio Direito',
                    'opcoes' => 'Normal;Dilatação Leve;Dilatação Moderada;Dilatação Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Apêndice Atrial Esquerdo',
                    'opcoes' => 'Sem Trombo;Trombo Presente;Fluxo Reduzido'
                ],

            ]

        ],

        //==========================================
        // VENTRÍCULOS
        //==========================================

        [
            'nome' => 'Avaliação dos Ventrículos',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Fração de Ejeção do Ventrículo Esquerdo',
                    'unidade' => '%',
                    'valor_referencia' => '55 - 70',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Sistólica do Ventrículo Esquerdo',
                    'opcoes' => 'Normal;Levemente Reduzida;Moderadamente Reduzida;Gravemente Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função do Ventrículo Direito',
                    'opcoes' => 'Normal;Reduzida'
                ],

            ]

        ],

        //==========================================
        // VÁLVULAS CARDÍACAS
        //==========================================

        [
            'nome' => 'Avaliação Valvar',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Válvula Mitral',
                    'opcoes' => 'Normal;Prolapso;Estenose;Insuficiência Leve;Insuficiência Moderada;Insuficiência Grave'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Válvula Aórtica',
                    'opcoes' => 'Normal;Estenose;Insuficiência;Vegetação;Prótese Valvar'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Válvula Tricúspide',
                    'opcoes' => 'Normal;Insuficiência;Estenose;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Válvula Pulmonar',
                    'opcoes' => 'Normal;Insuficiência;Estenose;Alterada'
                ],

            ]

        ],

        //==========================================
        // ENDOCARDITE E VEGETAÇÕES
        //==========================================

        [
            'nome' => 'Pesquisa de Endocardite',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Vegetações Valvares',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Dimensão da Vegetação',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 20',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Abscesso Perivalvar',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // COMUNICAÇÕES CARDÍACAS
        //==========================================

        [
            'nome' => 'Comunicações Cardíacas',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Comunicação Interatrial (CIA)',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Forame Oval Patente (FOP)',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Shunt Intracardíaco',
                    'opcoes' => 'Ausente;Direita-Esquerda;Esquerda-Direita;Bidirecional'
                ],

            ]

        ],

        //==========================================
        // AORTA E GRANDES VASOS
        //==========================================

        [
            'nome' => 'Grandes Vasos',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Aorta Ascendente',
                    'unidade' => 'mm',
                    'valor_referencia' => '20 - 37',
                    'valor_minimo' => 15,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aorta Torácica',
                    'opcoes' => 'Normal;Dilatada;Dissecção;Aneurisma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Veia Cava Superior',
                    'opcoes' => 'Normal;Alterada'
                ],

            ]

        ],

        //==========================================
        // DOPPLER
        //==========================================

        [
            'nome' => 'Doppler Cardíaco',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Pressão Sistólica da Artéria Pulmonar',
                    'unidade' => 'mmHg',
                    'valor_referencia' => '15 - 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxos Valvares',
                    'opcoes' => 'Normais;Alterados'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 11,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'VASC001',
    'nome' => 'Doppler das Artérias Carótidas e Vertebrais',
    'categoria' => 'Angiologia / Cirurgia Vascular',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lado Avaliado',
                    'opcoes' => 'Direito;Esquerdo;Bilateral'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // ARTÉRIA CARÓTIDA COMUM
        //==========================================

        [
            'nome' => 'Artérias Carótidas Comuns',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura Médio-Intimal Direita',
                    'unidade' => 'mm',
                    'valor_referencia' => '≤ 0.9',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura Médio-Intimal Esquerda',
                    'unidade' => 'mm',
                    'valor_referencia' => '≤ 0.9',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Carótida Comum Direita',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '30 - 120',
                    'valor_minimo' => 0,
                    'valor_maximo' => 200
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Carótida Comum Esquerda',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '30 - 120',
                    'valor_minimo' => 0,
                    'valor_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto das Paredes Arteriais',
                    'opcoes' => 'Normal;Placas Ateroscleróticas;Espessamento Parietal'
                ],

            ]

        ],

        //==========================================
        // ARTÉRIAS CARÓTIDAS INTERNAS
        //==========================================

        [
            'nome' => 'Artérias Carótidas Internas',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Carótida Interna Direita',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '< 125',
                    'valor_minimo' => 0,
                    'valor_maximo' => 300
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Carótida Interna Esquerda',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '< 125',
                    'valor_minimo' => 0,
                    'valor_maximo' => 300
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Relação ACI/ACC Direita',
                    'unidade' => '',
                    'valor_referencia' => '< 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Relação ACI/ACC Esquerda',
                    'unidade' => '',
                    'valor_referencia' => '< 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Estenose Carótida Interna Direita',
                    'opcoes' => 'Sem Estenose;Até 49%;50-69%;70-99%;Oclusão Total'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Estenose Carótida Interna Esquerda',
                    'opcoes' => 'Sem Estenose;Até 49%;50-69%;70-99%;Oclusão Total'
                ],

            ]

        ],

        //==========================================
        // BULBO CAROTÍDEO
        //==========================================

        [
            'nome' => 'Bulbo Carotídeo',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Placas no Bulbo Carotídeo Direito',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Placas no Bulbo Carotídeo Esquerdo',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Características das Placas',
                    'opcoes' => 'Calcificadas;Fibrosas;Mistas;Ulceradas'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura da Placa',
                    'unidade' => 'mm',
                    'valor_referencia' => '0 - 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

            ]

        ],

        //==========================================
        // ARTÉRIAS VERTEBRAIS
        //==========================================

        [
            'nome' => 'Artérias Vertebrais',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Artéria Vertebral Direita',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '20 - 60',
                    'valor_minimo' => 0,
                    'valor_maximo' => 150
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Artéria Vertebral Esquerda',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '20 - 60',
                    'valor_minimo' => 0,
                    'valor_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vertebral Direito',
                    'opcoes' => 'Anterógrado;Retrógrado;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vertebral Esquerdo',
                    'opcoes' => 'Anterógrado;Retrógrado;Ausente'
                ],

            ]

        ],

        //==========================================
        // DOPPLER ESPECTRAL
        //==========================================

        [
            'nome' => 'Análise Doppler Espectral',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Direito',
                    'unidade' => '',
                    'valor_referencia' => '0.5 - 0.8',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Esquerdo',
                    'unidade' => '',
                    'valor_referencia' => '0.5 - 0.8',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão de Fluxo Arterial',
                    'opcoes' => 'Normal;Alterado;Turbulento'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'VASC002',
    'nome' => 'Doppler das Artérias Renais',
    'categoria' => 'Angiologia / Cirurgia Vascular',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lado Avaliado',
                    'opcoes' => 'Direito;Esquerdo;Bilateral'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO DOS RINS
        //==========================================

        [
            'nome' => 'Avaliação Renal',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Rim Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '9 - 12',
                    'valor_minimo' => 6,
                    'valor_maximo' => 15
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Rim Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '9 - 12',
                    'valor_minimo' => 6,
                    'valor_maximo' => 15
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade Renal',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Diferenciação Corticomedular',
                    'opcoes' => 'Preservada;Reduzida;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação do Sistema Coletor',
                    'opcoes' => 'Ausente;Leve;Moderada;Importante'
                ],

            ]

        ],

        //==========================================
        // ARTÉRIA RENAL DIREITA
        //==========================================

        [
            'nome' => 'Artéria Renal Direita',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '60 - 180',
                    'valor_minimo' => 0,
                    'valor_maximo' => 300
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Diastólica Final',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '10 - 50',
                    'valor_minimo' => 0,
                    'valor_maximo' => 150
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência',
                    'unidade' => '',
                    'valor_referencia' => '0.50 - 0.80',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Relação Renal-Aórtica (RAR)',
                    'unidade' => '',
                    'valor_referencia' => '< 3.5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Estenose',
                    'opcoes' => 'Sem Estenose;Leve;Moderada;Importante;Oclusão'
                ],

            ]

        ],

        //==========================================
        // ARTÉRIA RENAL ESQUERDA
        //==========================================

        [
            'nome' => 'Artéria Renal Esquerda',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '60 - 180',
                    'valor_minimo' => 0,
                    'valor_maximo' => 300
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Diastólica Final',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '10 - 50',
                    'valor_minimo' => 0,
                    'valor_maximo' => 150
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência',
                    'unidade' => '',
                    'valor_referencia' => '0.50 - 0.80',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Relação Renal-Aórtica (RAR)',
                    'unidade' => '',
                    'valor_referencia' => '< 3.5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Estenose',
                    'opcoes' => 'Sem Estenose;Leve;Moderada;Importante;Oclusão'
                ],

            ]

        ],

        //==========================================
        // AORTA ABDOMINAL
        //==========================================

        [
            'nome' => 'Aorta Abdominal',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Aórtica',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '60 - 125',
                    'valor_minimo' => 0,
                    'valor_maximo' => 250
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto da Aorta',
                    'opcoes' => 'Normal;Placas Ateroscleróticas;Aneurisma;Alterada'
                ],

            ]

        ],

        //==========================================
        // VASCULARIZAÇÃO INTRARRENAL
        //==========================================

        [
            'nome' => 'Vascularização Intrarrenal',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Arterial Renal Direita',
                    'unidade' => '',
                    'valor_referencia' => '0.50 - 0.70',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Arterial Renal Esquerda',
                    'unidade' => '',
                    'valor_referencia' => '0.50 - 0.70',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão de Fluxo Intrarrenal',
                    'opcoes' => 'Normal;Reduzido;Alterado'
                ],

            ]

        ],

        //==========================================
        // TRANSPLANTE RENAL (OPCIONAL)
        //==========================================

        [
            'nome' => 'Avaliação de Transplante Renal',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Rim Transplantado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular do Enxerto',
                    'opcoes' => 'Preservado;Reduzido;Ausente'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];


$dadosExame = [
    'codigo' => 'ABD001',
    'nome' => 'Ultrassonografia de Abdome Total',
    'categoria' => 'Imagem / Radiologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Preparo do Exame',
                    'opcoes' => 'Adequado;Inadequado;Não Realizado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // FÍGADO
        //==========================================

        [
            'nome' => 'Fígado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Lobo Direito Hepático',
                    'unidade' => 'cm',
                    'valor_referencia' => '≤ 15',
                    'valor_minimo' => 0,
                    'valor_maximo' => 25
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dimensões Hepáticas',
                    'opcoes' => 'Normais;Aumentadas;Reduzidas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura Hepática',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade Hepática',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Esteatose Hepática',
                    'opcoes' => 'Ausente;Leve;Moderada;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais Hepáticas',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa;Outras'
                ],

            ]

        ],

        //==========================================
        // VESÍCULA BILIAR
        //==========================================

        [
            'nome' => 'Vesícula Biliar',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura da Parede Vesicular',
                    'unidade' => 'mm',
                    'valor_referencia' => '≤ 3',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Distensão da Vesícula',
                    'opcoes' => 'Normal;Distendida;Contraída'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cálculos Vesiculares',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lama Biliar',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Pólipos Vesiculares',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // VIAS BILIARES
        //==========================================

        [
            'nome' => 'Vias Biliares',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Colédoco',
                    'unidade' => 'mm',
                    'valor_referencia' => '≤ 6',
                    'valor_minimo' => 0,
                    'valor_maximo' => 15
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação das Vias Biliares',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // PÂNCREAS
        //==========================================

        [
            'nome' => 'Pâncreas',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Visualização do Pâncreas',
                    'opcoes' => 'Adequada;Parcial;Não Visualizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura Pancreática',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Pancreáticas',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa'
                ],

            ]

        ],

        //==========================================
        // BAÇO
        //==========================================

        [
            'nome' => 'Baço',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Baço',
                    'unidade' => 'cm',
                    'valor_referencia' => '8 - 12',
                    'valor_minimo' => 5,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dimensão Esplênica',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Esplênicas',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa'
                ],

            ]

        ],

        //==========================================
        // RIM DIREITO
        //==========================================

        [
            'nome' => 'Rim Direito',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento Renal Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '9 - 12',
                    'valor_minimo' => 6,
                    'valor_maximo' => 15
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade Renal Direita',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cálculos Renais Direito',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação do Sistema Coletor Direito',
                    'opcoes' => 'Ausente;Leve;Moderada;Importante'
                ],

            ]

        ],

        //==========================================
        // RIM ESQUERDO
        //==========================================

        [
            'nome' => 'Rim Esquerdo',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento Renal Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '9 - 12',
                    'valor_minimo' => 6,
                    'valor_maximo' => 15
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade Renal Esquerda',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cálculos Renais Esquerdo',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação do Sistema Coletor Esquerdo',
                    'opcoes' => 'Ausente;Leve;Moderada;Importante'
                ],

            ]

        ],

        //==========================================
        // BEXIGA
        //==========================================

        [
            'nome' => 'Bexiga Urinária',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Vesical',
                    'unidade' => 'ml',
                    'valor_referencia' => '100 - 500',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Parede Vesical',
                    'opcoes' => 'Normal;Espessada;Irregular'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resíduo Pós-Miccional',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Residual Pós-Miccional',
                    'unidade' => 'ml',
                    'valor_referencia' => '< 50',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

            ]

        ],

        //==========================================
        // GRANDES VASOS
        //==========================================

        [
            'nome' => 'Grandes Vasos Abdominais',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Aorta Abdominal',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 60
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aorta Abdominal',
                    'opcoes' => 'Normal;Aneurisma;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Veia Cava Inferior',
                    'opcoes' => 'Normal;Dilatada;Alterada'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 11,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 4000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 12,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];

$dadosExame = [
    'codigo' => 'ABD002',
    'nome' => 'Ultrassonografia Hepatobiliar',
    'categoria' => 'Imagem / Radiologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Preparo do Exame',
                    'opcoes' => 'Adequado;Inadequado;Não Realizado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // FÍGADO
        //==========================================

        [
            'nome' => 'Fígado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Lobo Direito Hepático',
                    'unidade' => 'cm',
                    'valor_referencia' => '≤ 15',
                    'valor_minimo' => 0,
                    'valor_maximo' => 25
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Lobo Esquerdo Hepático',
                    'unidade' => 'cm',
                    'valor_referencia' => '≤ 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 18
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dimensões Hepáticas',
                    'opcoes' => 'Normais;Aumentadas;Reduzidas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Contornos Hepáticos',
                    'opcoes' => 'Regulares;Irregulares;Nodulares'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura Hepática',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade Hepática',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Esteatose Hepática',
                    'opcoes' => 'Ausente;Leve;Moderada;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais Hepáticas',
                    'opcoes' => 'Ausentes;Cisto;Hemangioma;Nódulo;Massa;Outras'
                ],

            ]

        ],

        //==========================================
        // VEIA PORTA
        //==========================================

        [
            'nome' => 'Sistema Portal',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro da Veia Porta',
                    'unidade' => 'mm',
                    'valor_referencia' => '≤ 13',
                    'valor_minimo' => 0,
                    'valor_maximo' => 25
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Portal',
                    'opcoes' => 'Hepatopetal Normal;Reduzido;Invertido;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Trombose da Veia Porta',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // VIAS BILIARES INTRA-HEPÁTICAS
        //==========================================

        [
            'nome' => 'Vias Biliares Intra-Hepáticas',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação das Vias Biliares Intra-Hepáticas',
                    'opcoes' => 'Ausente;Leve;Moderada;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto das Vias Biliares',
                    'opcoes' => 'Normais;Irregulares;Dilatadas'
                ],

            ]

        ],

        //==========================================
        // VESÍCULA BILIAR
        //==========================================

        [
            'nome' => 'Vesícula Biliar',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura da Parede Vesicular',
                    'unidade' => 'mm',
                    'valor_referencia' => '≤ 3',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Distensão Vesicular',
                    'opcoes' => 'Normal;Distendida;Contraída'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cálculos na Vesícula',
                    'opcoes' => 'Ausentes;Único;Múltiplos'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Mobilidade dos Cálculos',
                    'opcoes' => 'Móveis;Fixos;Não Aplicável'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lama Biliar',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Pólipos Vesiculares',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinal de Murphy Ultrassonográfico',
                    'opcoes' => 'Negativo;Positivo'
                ],

            ]

        ],

        //==========================================
        // COLÉDOCO
        //==========================================

        [
            'nome' => 'Colédoco e Via Biliar Principal',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro do Colédoco',
                    'unidade' => 'mm',
                    'valor_referencia' => '≤ 6',
                    'valor_minimo' => 0,
                    'valor_maximo' => 15
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação do Colédoco',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cálculo no Colédoco',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // PÂNCREAS (PARCIAL)
        //==========================================

        [
            'nome' => 'Pâncreas',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Visualização Pancreática',
                    'opcoes' => 'Adequada;Parcial;Limitada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto Pancreático',
                    'opcoes' => 'Normal;Alterado'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]
];

$dadosExame = [
    'codigo' => 'URI001',
    'nome' => 'Ultrassonografia dos Rins e Trato Urinário',
    'categoria' => 'Imagem / Urologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Preparo do Exame',
                    'opcoes' => 'Adequado;Inadequado;Não Realizado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // RIM DIREITO
        //==========================================

        [
            'nome' => 'Rim Direito',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Rim Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '9 - 12',
                    'valor_minimo' => 6,
                    'valor_maximo' => 15
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura do Parênquima Renal Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '1.0 - 2.0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 4
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dimensão do Rim Direito',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Contornos do Rim Direito',
                    'opcoes' => 'Regulares;Irregulares'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade do Rim Direito',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Diferenciação Corticomedular Direita',
                    'opcoes' => 'Preservada;Reduzida;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cistos no Rim Direito',
                    'opcoes' => 'Ausentes;Simples;Complexos'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cálculos no Rim Direito',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Cálculo Renal Direito',
                    'unidade' => 'mm',
                    'valor_referencia' => '0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

            ]

        ],

        //==========================================
        // RIM ESQUERDO
        //==========================================

        [
            'nome' => 'Rim Esquerdo',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Rim Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '9 - 12',
                    'valor_minimo' => 6,
                    'valor_maximo' => 15
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura do Parênquima Renal Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '1.0 - 2.0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 4
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dimensão do Rim Esquerdo',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Contornos do Rim Esquerdo',
                    'opcoes' => 'Regulares;Irregulares'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade do Rim Esquerdo',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Diferenciação Corticomedular Esquerda',
                    'opcoes' => 'Preservada;Reduzida;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cistos no Rim Esquerdo',
                    'opcoes' => 'Ausentes;Simples;Complexos'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cálculos no Rim Esquerdo',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Cálculo Renal Esquerdo',
                    'unidade' => 'mm',
                    'valor_referencia' => '0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

            ]

        ],

        //==========================================
        // SISTEMA COLETOR URINÁRIO
        //==========================================

        [
            'nome' => 'Sistema Coletor Urinário',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação Pielocalicial Direita',
                    'opcoes' => 'Ausente;Leve;Moderada;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação Pielocalicial Esquerda',
                    'opcoes' => 'Ausente;Leve;Moderada;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidronefrose Direita',
                    'opcoes' => 'Ausente;Grau I;Grau II;Grau III;Grau IV'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidronefrose Esquerda',
                    'opcoes' => 'Ausente;Grau I;Grau II;Grau III;Grau IV'
                ],

            ]

        ],

        //==========================================
        // URETERES
        //==========================================

        [
            'nome' => 'Ureteres',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Visualização dos Ureteres',
                    'opcoes' => 'Normal;Parcial;Não Visualizados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação Ureteral Direita',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação Ureteral Esquerda',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // BEXIGA URINÁRIA
        //==========================================

        [
            'nome' => 'Bexiga Urinária',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Vesical Inicial',
                    'unidade' => 'ml',
                    'valor_referencia' => '100 - 500',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Distensão Vesical',
                    'opcoes' => 'Adequada;Pouco Distendida;Muito Distendida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Parede Vesical',
                    'opcoes' => 'Normal;Espessada;Irregular'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sedimentos Vesicais',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cálculos Vesicais',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // RESÍDUO PÓS-MICCIONAL
        //==========================================

        [
            'nome' => 'Avaliação Pós-Miccional',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Residual Pós-Miccional',
                    'unidade' => 'ml',
                    'valor_referencia' => '< 50',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resíduo Pós-Miccional',
                    'opcoes' => 'Ausente;Significativo'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];

$dadosExame = [
    'codigo' => 'PEL001',
    'nome' => 'Ultrassonografia Pélvica',
    'categoria' => 'Imagem / Ginecologia e Urologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Via do Exame',
                    'opcoes' => 'Abdominal;Transvaginal;Combinada'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Preparo do Exame',
                    'opcoes' => 'Adequado;Inadequado;Não Realizado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // BEXIGA URINÁRIA
        //==========================================

        [
            'nome' => 'Bexiga Urinária',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Vesical Inicial',
                    'unidade' => 'ml',
                    'valor_referencia' => '100 - 500',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Distensão Vesical',
                    'opcoes' => 'Adequada;Pouco Distendida;Muito Distendida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Parede Vesical',
                    'opcoes' => 'Normal;Espessada;Irregular'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Vesicais',
                    'opcoes' => 'Ausentes;Cisto;Cálculo;Massa;Outras'
                ],

            ]

        ],

        //==========================================
        // ÚTERO
        //==========================================

        [
            'nome' => 'Útero',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento Uterino',
                    'unidade' => 'cm',
                    'valor_referencia' => '6 - 9',
                    'valor_minimo' => 2,
                    'valor_maximo' => 15
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro Ântero-Posterior do Útero',
                    'unidade' => 'cm',
                    'valor_referencia' => '2 - 5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro Transverso do Útero',
                    'unidade' => 'cm',
                    'valor_referencia' => '3 - 6',
                    'valor_minimo' => 1,
                    'valor_maximo' => 12
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Volume Uterino',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Posição Uterina',
                    'opcoes' => 'Anteversoflexão;Retroversão;Mediana'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Contornos Uterinos',
                    'opcoes' => 'Regulares;Irregulares'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Miométrio',
                    'opcoes' => 'Homogêneo;Heterogêneo;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Miomas Uterinos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Miometriais',
                    'opcoes' => 'Ausentes;Nódulo;Massa;Outras'
                ],

            ]

        ],

        //==========================================
        // ENDOMÉTRIO
        //==========================================

        [
            'nome' => 'Endométrio',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura Endometrial',
                    'unidade' => 'mm',
                    'valor_referencia' => '2 - 15',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto Endometrial',
                    'opcoes' => 'Normal;Homogêneo;Heterogêneo;Espessado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Endometriais',
                    'opcoes' => 'Ausentes;Pólipo;Massa;Outras'
                ],

            ]

        ],

        //==========================================
        // OVÁRIO DIREITO
        //==========================================

        [
            'nome' => 'Ovário Direito',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Ovário Direito',
                    'unidade' => 'ml',
                    'valor_referencia' => '3 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dimensão do Ovário Direito',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Folículos no Ovário Direito',
                    'opcoes' => 'Ausentes;Poucos;Numerosos'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cistos no Ovário Direito',
                    'opcoes' => 'Ausentes;Cisto Simples;Cisto Complexo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Massas Ovarianas Direitas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // OVÁRIO ESQUERDO
        //==========================================

        [
            'nome' => 'Ovário Esquerdo',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Ovário Esquerdo',
                    'unidade' => 'ml',
                    'valor_referencia' => '3 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dimensão do Ovário Esquerdo',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Folículos no Ovário Esquerdo',
                    'opcoes' => 'Ausentes;Poucos;Numerosos'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cistos no Ovário Esquerdo',
                    'opcoes' => 'Ausentes;Cisto Simples;Cisto Complexo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Massas Ovarianas Esquerdas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // TROMPAS E FUNDO DE SACO
        //==========================================

        [
            'nome' => 'Estruturas Adjacentes',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Trompas Uterinas',
                    'opcoes' => 'Normais;Não Visualizadas;Alteradas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Líquido Livre Pélvico',
                    'opcoes' => 'Ausente;Pequena Quantidade;Moderado;Grande Quantidade'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Quantidade de Líquido Livre',
                    'unidade' => 'ml',
                    'valor_referencia' => '0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];


$dadosExame = [
    'codigo' => 'URO001',
    'nome' => 'Ultrassonografia da Próstata',
    'categoria' => 'Imagem / Urologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Via de Realização',
                    'opcoes' => 'Abdominal;Transretal;Combinada'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Preparo do Exame',
                    'opcoes' => 'Adequado;Inadequado;Não Realizado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // BEXIGA URINÁRIA
        //==========================================

        [
            'nome' => 'Bexiga Urinária',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Vesical Pré-Miccional',
                    'unidade' => 'ml',
                    'valor_referencia' => '100 - 500',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1500
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Distensão Vesical',
                    'opcoes' => 'Adequada;Pouco Distendida;Muito Distendida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Parede Vesical',
                    'opcoes' => 'Normal;Espessada;Irregular;Trabeculada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Vesicais',
                    'opcoes' => 'Ausentes;Cálculo;Nódulo;Massa;Outras'
                ],

            ]

        ],

        //==========================================
        // PRÓSTATA - DIMENSÕES
        //==========================================

        [
            'nome' => 'Próstata - Dimensões',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro Longitudinal da Próstata',
                    'unidade' => 'cm',
                    'valor_referencia' => '2.5 - 4.5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro Transverso da Próstata',
                    'unidade' => 'cm',
                    'valor_referencia' => '3 - 5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Diâmetro Ântero-Posterior da Próstata',
                    'unidade' => 'cm',
                    'valor_referencia' => '2 - 4',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Prostático',
                    'unidade' => 'ml',
                    'valor_referencia' => '< 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tamanho Prostático',
                    'opcoes' => 'Normal;Aumentada Leve;Aumentada Moderada;Aumentada Importante'
                ],

            ]

        ],

        //==========================================
        // PARENQUIMA PROSTÁTICO
        //==========================================

        [
            'nome' => 'Parênquima Prostático',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura Prostática',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade Prostática',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Calcificações Prostáticas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cistos Prostáticos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Nódulos Prostáticos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // ZONA CENTRAL E TRANSICIONAL
        //==========================================

        [
            'nome' => 'Zonas Prostáticas',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Zona Transicional',
                    'opcoes' => 'Normal;Aumentada;Hiperplasia Nodular'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume da Zona Transicional',
                    'unidade' => 'ml',
                    'valor_referencia' => '< 20',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Zona Periférica',
                    'opcoes' => 'Preservada;Alterada'
                ],

            ]

        ],

        //==========================================
        // HIPERPLASIA PROSTÁTICA
        //==========================================

        [
            'nome' => 'Hiperplasia Prostática Benigna',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Hiperplasia Prostática',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Hiperplasia Prostática',
                    'opcoes' => 'Grau I;Grau II;Grau III;Grau IV'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Protrusão Prostática Intravesical',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

            ]

        ],

        //==========================================
        // VESÍCULAS SEMINAIS
        //==========================================

        [
            'nome' => 'Vesículas Seminais',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto das Vesículas Seminais',
                    'opcoes' => 'Normal;Aumentadas;Alteradas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Assimetria das Vesículas Seminais',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // RESÍDUO PÓS-MICCIONAL
        //==========================================

        [
            'nome' => 'Avaliação Pós-Miccional',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Residual Pós-Miccional',
                    'unidade' => 'ml',
                    'valor_referencia' => '< 50',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resíduo Urinário',
                    'opcoes' => 'Ausente;Pequeno;Significativo'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];


$dadosExame = [
    'codigo' => 'URO002',
    'nome' => 'Ultrassonografia Testicular',
    'categoria' => 'Imagem / Urologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lado Avaliado',
                    'opcoes' => 'Direito;Esquerdo;Bilateral'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // TESTÍCULO DIREITO
        //==========================================

        [
            'nome' => 'Testículo Direito',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Testículo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '3 - 5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Testículo Direito',
                    'unidade' => 'ml',
                    'valor_referencia' => '12 - 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dimensão do Testículo Direito',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura Testicular Direita',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade Testicular Direita',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais no Testículo Direito',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa;Outras'
                ],

            ]

        ],

        //==========================================
        // TESTÍCULO ESQUERDO
        //==========================================

        [
            'nome' => 'Testículo Esquerdo',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Testículo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '3 - 5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Testículo Esquerdo',
                    'unidade' => 'ml',
                    'valor_referencia' => '12 - 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dimensão do Testículo Esquerdo',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura Testicular Esquerda',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade Testicular Esquerda',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais no Testículo Esquerdo',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa;Outras'
                ],

            ]

        ],

        //==========================================
        // EPIDÍDIMOS
        //==========================================

        [
            'nome' => 'Epidídimos',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Epidídimo Direito',
                    'opcoes' => 'Normal;Aumentado;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Epidídimo Esquerdo',
                    'opcoes' => 'Normal;Aumentado;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cistos Epididimários',
                    'opcoes' => 'Ausentes;Direito;Esquerdo;Bilateral'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais de Epididimite',
                    'opcoes' => 'Ausentes;Direita;Esquerda;Bilateral'
                ],

            ]

        ],

        //==========================================
        // DOPPLER TESTICULAR
        //==========================================

        [
            'nome' => 'Avaliação Doppler',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Sanguíneo Testicular Direito',
                    'opcoes' => 'Preservado;Aumentado;Reduzido;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Sanguíneo Testicular Esquerdo',
                    'opcoes' => 'Preservado;Aumentado;Reduzido;Ausente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Testicular Direita',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '5 - 15',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Testicular Esquerda',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '5 - 15',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

            ]

        ],

        //==========================================
        // VARICOCELE
        //==========================================

        [
            'nome' => 'Avaliação de Varicocele',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Varicocele Direita',
                    'opcoes' => 'Ausente;Grau I;Grau II;Grau III'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Varicocele Esquerda',
                    'opcoes' => 'Ausente;Grau I;Grau II;Grau III'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro Venoso',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Refluxo Venoso ao Valsalva',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // HIDROCELE
        //==========================================

        [
            'nome' => 'Avaliação de Hidrocele',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidrocele Direita',
                    'opcoes' => 'Ausente;Pequena;Moderada;Grande'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidrocele Esquerda',
                    'opcoes' => 'Ausente;Pequena;Moderada;Grande'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Septações ou Debris',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // ESCROTO E PARTES MOLES
        //==========================================

        [
            'nome' => 'Bolsa Escrotal e Partes Moles',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Espessamento da Parede Escrotal',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Coleções Escrotais',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hérnia Inguino-Escrotal',
                    'opcoes' => 'Ausente;Direita;Esquerda;Bilateral'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];



$dadosExame = [
    'codigo' => 'URO003',
    'nome' => 'Ultrassonografia Escrotal',
    'categoria' => 'Imagem / Urologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lado Avaliado',
                    'opcoes' => 'Direito;Esquerdo;Bilateral'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // TESTÍCULO DIREITO
        //==========================================

        [
            'nome' => 'Testículo Direito',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Testículo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '3 - 5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura do Testículo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '2 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Altura do Testículo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '1.5 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Testículo Direito',
                    'unidade' => 'ml',
                    'valor_referencia' => '12 - 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura do Testículo Direito',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade do Testículo Direito',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais no Testículo Direito',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa;Calcificação;Outras'
                ],

            ]

        ],

        //==========================================
        // TESTÍCULO ESQUERDO
        //==========================================

        [
            'nome' => 'Testículo Esquerdo',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Testículo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '3 - 5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura do Testículo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '2 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Altura do Testículo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '1.5 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Testículo Esquerdo',
                    'unidade' => 'ml',
                    'valor_referencia' => '12 - 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura do Testículo Esquerdo',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade do Testículo Esquerdo',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais no Testículo Esquerdo',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa;Calcificação;Outras'
                ],

            ]

        ],

        //==========================================
        // EPIDÍDIMOS
        //==========================================

        [
            'nome' => 'Epidídimos',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Cabeça do Epidídimo Direito',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 12',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Cabeça do Epidídimo Esquerdo',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 12',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto do Epidídimo Direito',
                    'opcoes' => 'Normal;Aumentado;Heterogêneo;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto do Epidídimo Esquerdo',
                    'opcoes' => 'Normal;Aumentado;Heterogêneo;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cistos Epididimários',
                    'opcoes' => 'Ausentes;Direitos;Esquerdos;Bilaterais'
                ],

            ]

        ],

        //==========================================
        // DOPPLER ESCROTAL
        //==========================================

        [
            'nome' => 'Avaliação Doppler',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização Testicular Direita',
                    'opcoes' => 'Preservada;Aumentada;Reduzida;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização Testicular Esquerda',
                    'opcoes' => 'Preservada;Aumentada;Reduzida;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais de Torção Testicular',
                    'opcoes' => 'Ausentes;Direita;Esquerda;Bilateral'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais de Orquite',
                    'opcoes' => 'Ausentes;Direita;Esquerda;Bilateral'
                ],

            ]

        ],

        //==========================================
        // VARICOCELE
        //==========================================

        [
            'nome' => 'Varicocele',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Varicocele Direita',
                    'opcoes' => 'Ausente;Grau I;Grau II;Grau III'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Varicocele Esquerda',
                    'opcoes' => 'Ausente;Grau I;Grau II;Grau III'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro das Veias do Plexo Pampiniforme',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Refluxo Venoso ao Valsalva',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // HIDROCELE E COLEÇÕES
        //==========================================

        [
            'nome' => 'Coleções Escrotais',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidrocele Direita',
                    'opcoes' => 'Ausente;Pequena;Moderada;Grande'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidrocele Esquerda',
                    'opcoes' => 'Ausente;Pequena;Moderada;Grande'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Debris ou Septações no Líquido',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hematocele',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // PAREDE ESCROTAL
        //==========================================

        [
            'nome' => 'Parede Escrotal e Região Inguinal',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Espessura da Parede Escrotal',
                    'opcoes' => 'Normal;Espessada;Edemaciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hérnia Inguino-Escrotal',
                    'opcoes' => 'Ausente;Direita;Esquerda;Bilateral'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Massas Extratesticulares',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]

];



$dadosExame = [
    'codigo' => 'URO004',
    'nome' => 'Ultrassonografia Testicular com Doppler',
    'categoria' => 'Imagem / Urologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lado Avaliado',
                    'opcoes' => 'Direito;Esquerdo;Bilateral'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // TESTÍCULO DIREITO
        //==========================================

        [
            'nome' => 'Testículo Direito',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Testículo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '3 - 5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura do Testículo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '2 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Altura do Testículo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '1.5 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Testículo Direito',
                    'unidade' => 'ml',
                    'valor_referencia' => '12 - 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura do Testículo Direito',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade do Testículo Direito',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais no Testículo Direito',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa;Calcificação;Outras'
                ],

            ]

        ],

        //==========================================
        // TESTÍCULO ESQUERDO
        //==========================================

        [
            'nome' => 'Testículo Esquerdo',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Testículo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '3 - 5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura do Testículo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '2 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Altura do Testículo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '1.5 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Testículo Esquerdo',
                    'unidade' => 'ml',
                    'valor_referencia' => '12 - 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura do Testículo Esquerdo',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade do Testículo Esquerdo',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais no Testículo Esquerdo',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa;Calcificação;Outras'
                ],

            ]

        ],

        //==========================================
        // EPIDÍDIMOS
        //==========================================

        [
            'nome' => 'Epidídimos',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Cabeça do Epidídimo Direito',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 12',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Cabeça do Epidídimo Esquerdo',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 12',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto do Epidídimo Direito',
                    'opcoes' => 'Normal;Aumentado;Heterogêneo;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto do Epidídimo Esquerdo',
                    'opcoes' => 'Normal;Aumentado;Heterogêneo;Alterado'
                ],

            ]

        ],

        //==========================================
        // DOPPLER COLORIDO E ESPECTRAL
        //==========================================

        [
            'nome' => 'Avaliação Doppler Testicular',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Arterial Testicular Direito',
                    'opcoes' => 'Preservado;Aumentado;Reduzido;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Arterial Testicular Esquerdo',
                    'opcoes' => 'Preservado;Aumentado;Reduzido;Ausente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Arterial Direita',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '5 - 15',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Arterial Esquerda',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '5 - 15',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Arterial Direito',
                    'unidade' => '',
                    'valor_referencia' => '0.50 - 0.75',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Arterial Esquerdo',
                    'unidade' => '',
                    'valor_referencia' => '0.50 - 0.75',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Assimetria Vascular Testicular',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // TORÇÃO TESTICULAR
        //==========================================

        [
            'nome' => 'Avaliação de Torção Testicular',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais de Torção Direita',
                    'opcoes' => 'Ausentes;Suspeita;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais de Torção Esquerda',
                    'opcoes' => 'Ausentes;Suspeita;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Venoso Testicular',
                    'opcoes' => 'Preservado;Reduzido;Ausente'
                ],

            ]

        ],

        //==========================================
        // VARICOCELE
        //==========================================

        [
            'nome' => 'Varicocele',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro Venoso em Repouso',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro Venoso com Valsalva',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 3',
                    'valor_minimo' => 0,
                    'valor_maximo' => 15
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Varicocele Direita',
                    'opcoes' => 'Ausente;Grau I;Grau II;Grau III'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Varicocele Esquerda',
                    'opcoes' => 'Ausente;Grau I;Grau II;Grau III'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Refluxo Venoso ao Valsalva',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // HIDROCELE E COLEÇÕES
        //==========================================

        [
            'nome' => 'Coleções Escrotais',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidrocele Direita',
                    'opcoes' => 'Ausente;Pequena;Moderada;Grande'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidrocele Esquerda',
                    'opcoes' => 'Ausente;Pequena;Moderada;Grande'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hematocele',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Piocele',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'URO005',
    'nome' => 'Ultrassonografia Peniana com Doppler',
    'categoria' => 'Imagem / Urologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Avaliação',
                    'opcoes' => 'Flácido;Após Estímulo Farmacológico;Controle Pós-Tratamento'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Medicamento Utilizado no Estímulo',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO ANATÔMICA DO PÊNIS
        //==========================================

        [
            'nome' => 'Avaliação Anatômica Peniana',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento Peniano em Repouso',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Variável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento Peniano em Ereção',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Variável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 40
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anatomia Peniana',
                    'opcoes' => 'Preservada;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Curvatura Peniana',
                    'opcoes' => 'Ausente;Leve;Moderada;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Placas Fibrosas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Doença de Peyronie',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // CORPOS CAVERNOSOS
        //==========================================

        [
            'nome' => 'Corpos Cavernosos',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura dos Corpos Cavernosos',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fibrose Cavernosa',
                    'opcoes' => 'Ausente;Leve;Moderada;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Calcificações nos Corpos Cavernosos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais Penianas',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa;Outras'
                ],

            ]

        ],

        //==========================================
        // ARTÉRIAS CAVERNOSAS - DOPPLER
        //==========================================

        [
            'nome' => 'Avaliação Arterial Doppler',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Artéria Cavernosa Direita',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '> 25',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Artéria Cavernosa Esquerda',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '> 25',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Diastólica Final Artéria Cavernosa Direita',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '< 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Diastólica Final Artéria Cavernosa Esquerda',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '< 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resposta Arterial ao Estímulo',
                    'opcoes' => 'Adequada;Reduzida;Ausente'
                ],

            ]

        ],

        //==========================================
        // RESISTÊNCIA VASCULAR
        //==========================================

        [
            'nome' => 'Índices Hemodinâmicos',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Arterial Direita',
                    'unidade' => '',
                    'valor_referencia' => '0.80 - 1.00',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Arterial Esquerda',
                    'unidade' => '',
                    'valor_referencia' => '0.80 - 1.00',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Simetria do Fluxo Arterial',
                    'opcoes' => 'Simétrica;Assimétrica'
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO VENOSA
        //==========================================

        [
            'nome' => 'Avaliação Venosa',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Diastólica Final Pós-Estímulo',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '< 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Escape Venoso',
                    'opcoes' => 'Ausente;Suspeito;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Funcionamento Venoso',
                    'opcoes' => 'Normal;Alterado'
                ],

            ]

        ],

        //==========================================
        // EREÇÃO INDUZIDA
        //==========================================

        [
            'nome' => 'Avaliação da Ereção Induzida',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Ereção Obtida',
                    'opcoes' => 'Ausente;Parcial;Adequada;Completa'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo para Ereção Máxima',
                    'unidade' => 'minutos',
                    'valor_referencia' => '5 - 20',
                    'valor_minimo' => 0,
                    'valor_maximo' => 60
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dor Durante o Exame',
                    'opcoes' => 'Ausente;Leve;Moderada;Intensa'
                ],

            ]

        ],

        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],

        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'URO006',
    'nome' => 'Ultrassonografia Escrotal com Doppler',
    'categoria' => 'Imagem / Urologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lado Avaliado',
                    'opcoes' => 'Direito;Esquerdo;Bilateral'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // TESTÍCULO DIREITO
        //==========================================

        [
            'nome' => 'Testículo Direito',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Testículo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '3 - 5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura do Testículo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '2 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Altura do Testículo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '1.5 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Testículo Direito',
                    'unidade' => 'ml',
                    'valor_referencia' => '12 - 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura do Testículo Direito',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais no Testículo Direito',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa;Calcificação;Outras'
                ],

            ]

        ],


        //==========================================
        // TESTÍCULO ESQUERDO
        //==========================================

        [
            'nome' => 'Testículo Esquerdo',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Testículo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '3 - 5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 8
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura do Testículo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '2 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Altura do Testículo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '1.5 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Testículo Esquerdo',
                    'unidade' => 'ml',
                    'valor_referencia' => '12 - 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura do Testículo Esquerdo',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Focais no Testículo Esquerdo',
                    'opcoes' => 'Ausentes;Cisto;Nódulo;Massa;Calcificação;Outras'
                ],

            ]

        ],


        //==========================================
        // EPIDÍDIMOS
        //==========================================

        [
            'nome' => 'Epidídimos',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Cabeça do Epidídimo Direito',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 12',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Cabeça do Epidídimo Esquerdo',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 12',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto dos Epidídimos',
                    'opcoes' => 'Normal;Aumentado;Heterogêneo;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cistos Epididimários',
                    'opcoes' => 'Ausentes;Direitos;Esquerdos;Bilaterais'
                ],

            ]

        ],


        //==========================================
        // DOPPLER TESTICULAR
        //==========================================

        [
            'nome' => 'Avaliação Doppler Testicular',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização Testicular Direita',
                    'opcoes' => 'Preservada;Aumentada;Reduzida;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização Testicular Esquerda',
                    'opcoes' => 'Preservada;Aumentada;Reduzida;Ausente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Artéria Testicular Direita',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '5 - 15',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade Sistólica Máxima Artéria Testicular Esquerda',
                    'unidade' => 'cm/s',
                    'valor_referencia' => '5 - 15',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Arterial Direito',
                    'unidade' => '',
                    'valor_referencia' => '0.50 - 0.75',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Resistência Arterial Esquerdo',
                    'unidade' => '',
                    'valor_referencia' => '0.50 - 0.75',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Assimetria de Fluxo Vascular',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // VARICOCELE
        //==========================================

        [
            'nome' => 'Avaliação de Varicocele',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro Venoso Direito',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro Venoso Esquerdo',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Varicocele Direita',
                    'opcoes' => 'Ausente;Grau I;Grau II;Grau III'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Varicocele Esquerda',
                    'opcoes' => 'Ausente;Grau I;Grau II;Grau III'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Refluxo Venoso ao Valsalva',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // HIDROCELE E COLEÇÕES
        //==========================================

        [
            'nome' => 'Coleções Escrotais',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidrocele Direita',
                    'opcoes' => 'Ausente;Pequena;Moderada;Grande'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hidrocele Esquerda',
                    'opcoes' => 'Ausente;Pequena;Moderada;Grande'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hematocele',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Coleções Escrotais Complexas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // PROCESSOS INFLAMATÓRIOS
        //==========================================

        [
            'nome' => 'Avaliação Inflamatória',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais de Orquite',
                    'opcoes' => 'Ausentes;Direita;Esquerda;Bilateral'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais de Epididimite',
                    'opcoes' => 'Ausentes;Direita;Esquerda;Bilateral'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aumento da Vascularização Inflamatória',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],


        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]
];





$dadosExame = [
    'codigo' => 'IMG001',
    'nome' => 'Ultrassonografia de Partes Moles',
    'categoria' => 'Imagem / Ultrassonografia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Região Anatômica Avaliada',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lado Avaliado',
                    'opcoes' => 'Direito;Esquerdo;Bilateral;Linha Média'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // PELE E TECIDO SUBCUTÂNEO
        //==========================================

        [
            'nome' => 'Pele e Tecido Subcutâneo',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Espessura da Pele',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto do Tecido Subcutâneo',
                    'opcoes' => 'Preservado;Edemaciado;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Edema de Partes Moles',
                    'opcoes' => 'Ausente;Leve;Moderado;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Coleções Superficiais',
                    'opcoes' => 'Ausentes;Seroma;Hematoma;Abscesso;Outras'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO MUSCULAR
        //==========================================

        [
            'nome' => 'Avaliação Muscular',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Integridade Muscular',
                    'opcoes' => 'Preservada;Alterada;Ruptura Parcial;Ruptura Completa'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura Muscular',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesão Muscular',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Dimensão da Lesão Muscular',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

            ]

        ],


        //==========================================
        // TENDÕES E LIGAMENTOS
        //==========================================

        [
            'nome' => 'Tendões e Ligamentos',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Integridade Tendínea',
                    'opcoes' => 'Preservada;Tendinopatia;Ruptura Parcial;Ruptura Completa'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Espessamento Tendíneo',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Calcificações Tendíneas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alterações Ligamentares',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // LESÕES OU MASSAS
        //==========================================

        [
            'nome' => 'Avaliação de Lesões',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Massa ou Nódulo',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Localização da Lesão',
                    'opcoes' => 'Subcutânea;Intramuscular;Fascial;Outra'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento da Lesão',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura da Lesão',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Profundidade da Lesão',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Características da Lesão',
                    'opcoes' => 'Cística;Sólida;Mista;Complexa'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Margens da Lesão',
                    'opcoes' => 'Regulares;Irregulares'
                ],

            ]

        ],


        //==========================================
        // DOPPLER COLORIDO
        //==========================================

        [
            'nome' => 'Avaliação Doppler',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular Local',
                    'opcoes' => 'Normal;Aumentado;Reduzido;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização da Lesão',
                    'opcoes' => 'Ausente;Periférica;Interna;Mista'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais Inflamatórios ao Doppler',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO INFLAMATÓRIA
        //==========================================

        [
            'nome' => 'Processos Inflamatórios',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais de Inflamação',
                    'opcoes' => 'Ausentes;Leves;Moderados;Importantes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Abscesso',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Celulite de Partes Moles',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],


        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'IMG002',
    'nome' => 'Ultrassonografia Cervical/Lombar (Partes Moles)',
    'categoria' => 'Imagem / Ultrassonografia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Região Avaliada',
                    'opcoes' => 'Cervical;Lombar;Cervical e Lombar'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lado Avaliado',
                    'opcoes' => 'Direito;Esquerdo;Bilateral;Linha Média'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // PELE E TECIDO SUBCUTÂNEO
        //==========================================

        [
            'nome' => 'Pele e Tecido Subcutâneo',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto da Pele',
                    'opcoes' => 'Normal;Espessada;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tecido Celular Subcutâneo',
                    'opcoes' => 'Preservado;Edemaciado;Infiltrado;Alterado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura do Tecido Subcutâneo',
                    'unidade' => 'mm',
                    'valor_referencia' => 'Variável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Coleções Superficiais',
                    'opcoes' => 'Ausentes;Seroma;Hematoma;Abscesso;Outras'
                ],

            ]

        ],


        //==========================================
        // MUSCULATURA CERVICAL
        //==========================================

        [
            'nome' => 'Musculatura Cervical',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Músculos Cervicais',
                    'opcoes' => 'Preservados;Alterados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura Muscular Cervical',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Musculares Cervicais',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ruptura Muscular',
                    'opcoes' => 'Ausente;Parcial;Completa'
                ],

            ]

        ],


        //==========================================
        // MUSCULATURA LOMBAR
        //==========================================

        [
            'nome' => 'Musculatura Lombar',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Músculos Lombares',
                    'opcoes' => 'Preservados;Alterados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura Muscular Lombar',
                    'opcoes' => 'Homogênea;Heterogênea;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Lesões Musculares Lombares',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hematoma Muscular',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // ESTRUTURAS POSTERIORES
        //==========================================

        [
            'nome' => 'Estruturas Posteriores',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fáscia Muscular',
                    'opcoes' => 'Preservada;Espessada;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ligamentos Superficiais',
                    'opcoes' => 'Preservados;Alterados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Planos Anatômicos',
                    'opcoes' => 'Preservados;Desorganizados'
                ],

            ]

        ],


        //==========================================
        // MASSAS E NÓDULOS
        //==========================================

        [
            'nome' => 'Avaliação de Massas e Lesões',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Massa ou Nódulo',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Localização da Lesão',
                    'opcoes' => 'Subcutânea;Intramuscular;Fascial;Paravertebral;Outra'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento da Lesão',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura da Lesão',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Profundidade da Lesão',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Características da Lesão',
                    'opcoes' => 'Cística;Sólida;Mista;Complexa'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Margens da Lesão',
                    'opcoes' => 'Regulares;Irregulares'
                ],

            ]

        ],


        //==========================================
        // DOPPLER COLORIDO
        //==========================================

        [
            'nome' => 'Avaliação Doppler',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular Local',
                    'opcoes' => 'Normal;Aumentado;Reduzido;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização da Lesão',
                    'opcoes' => 'Ausente;Periférica;Interna;Mista'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais Inflamatórios ao Doppler',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO INFLAMATÓRIA
        //==========================================

        [
            'nome' => 'Processos Inflamatórios',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais de Inflamação',
                    'opcoes' => 'Ausentes;Leves;Moderados;Importantes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Abscesso',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Celulite de Partes Moles',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],


        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'END001',
    'nome' => 'Ultrassonografia da Tireoide',
    'categoria' => 'Imagem / Endocrinologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DA TIREOIDE
        //==========================================

        [
            'nome' => 'Avaliação Geral da Tireoide',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Localização da Tireoide',
                    'opcoes' => 'Normal;Ectópica;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Volume da Tireoide',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecotextura do Parênquima',
                    'opcoes' => 'Homogênea;Heterogênea;Difusamente Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade da Tireoide',
                    'opcoes' => 'Normal;Reduzida;Aumentada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização Global',
                    'opcoes' => 'Normal;Aumentada;Reduzida'
                ],

            ]

        ],


        //==========================================
        // LOBO DIREITO
        //==========================================

        [
            'nome' => 'Lobo Direito da Tireoide',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Lobo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '3.5 - 5.5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura do Lobo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '1 - 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura do Lobo Direito',
                    'unidade' => 'cm',
                    'valor_referencia' => '1 - 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Lobo Direito',
                    'unidade' => 'ml',
                    'valor_referencia' => '5 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

            ]

        ],


        //==========================================
        // LOBO ESQUERDO
        //==========================================

        [
            'nome' => 'Lobo Esquerdo da Tireoide',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Comprimento do Lobo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '3.5 - 5.5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Largura do Lobo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '1 - 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura do Lobo Esquerdo',
                    'unidade' => 'cm',
                    'valor_referencia' => '1 - 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume do Lobo Esquerdo',
                    'unidade' => 'ml',
                    'valor_referencia' => '5 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

            ]

        ],


        //==========================================
        // ISTMO
        //==========================================

        [
            'nome' => 'Istmo da Tireoide',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Espessura do Istmo',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto do Istmo',
                    'opcoes' => 'Normal;Aumentado;Alterado'
                ],

            ]

        ],


        //==========================================
        // NÓDULOS TIREOIDIANOS
        //==========================================

        [
            'nome' => 'Avaliação de Nódulos',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Nódulos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Quantidade de Nódulos',
                    'opcoes' => 'Único;Múltiplos;Multinodular'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Localização do Nódulo',
                    'opcoes' => 'Lobo Direito;Lobo Esquerdo;Istmo;Bilateral'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Dimensão do Nódulo',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Composição do Nódulo',
                    'opcoes' => 'Sólido;Cístico;Misto;Espongiforme'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade do Nódulo',
                    'opcoes' => 'Anecóico;Hiperecogênico;Isoecogênico;Hipoecogênico'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Margens do Nódulo',
                    'opcoes' => 'Regulares;Irregulares;Mal Definidas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Calcificações no Nódulo',
                    'opcoes' => 'Ausentes;Microcalcificações;Macrocalcificações'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação TI-RADS',
                    'opcoes' => 'TR1;TR2;TR3;TR4;TR5'
                ],

            ]

        ],


        //==========================================
        // DOPPLER COLORIDO
        //==========================================

        [
            'nome' => 'Avaliação Doppler Colorido',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular da Tireoide',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização dos Nódulos',
                    'opcoes' => 'Ausente;Periférica;Central;Mista'
                ],

            ]

        ],


        //==========================================
        // LINFONODOS CERVICAIS
        //==========================================

        [
            'nome' => 'Linfonodos Cervicais',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Linfonodos Cervicais',
                    'opcoes' => 'Normais;Aumentados;Alterados'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro do Linfonodo',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Características dos Linfonodos',
                    'opcoes' => 'Benignas;Suspeitas'
                ],

            ]

        ],


        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],


        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'END002',
    'nome' => 'Ultrassonografia das Paratireoides',
    'categoria' => 'Imagem / Endocrinologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'História de Cirurgia Cervical Prévia',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DAS PARATIREOIDES
        //==========================================

        [
            'nome' => 'Avaliação das Paratireoides',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Visualização das Paratireoides',
                    'opcoes' => 'Identificadas;Não Identificadas;Parcialmente Identificadas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Número de Glândulas Alteradas',
                    'opcoes' => 'Nenhuma;Uma;Duas;Múltiplas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Localização das Alterações',
                    'opcoes' => 'Superior Direita;Inferior Direita;Superior Esquerda;Inferior Esquerda;Outra'
                ],

            ]

        ],


        //==========================================
        // LESÕES / ADENOMAS PARATIREOIDEANOS
        //==========================================

        [
            'nome' => 'Avaliação de Lesões Paratireoideas',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Lesão Paratireoidea',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Lesão',
                    'opcoes' => 'Adenoma;Hiperplasia;Nódulo Indeterminado;Outra'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Dimensão da Lesão',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume da Lesão',
                    'unidade' => 'ml',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Formato da Lesão',
                    'opcoes' => 'Ovalado;Arredondado;Irregular'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade da Lesão',
                    'opcoes' => 'Hipoecogênica;Isoecogênica;Hiperecogênica;Cística'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Limites da Lesão',
                    'opcoes' => 'Bem Definidos;Mal Definidos'
                ],

            ]

        ],


        //==========================================
        // LOCAIS ECTÓPICOS
        //==========================================

        [
            'nome' => 'Pesquisa de Paratireoides Ectópicas',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Paratireoide Ectópica',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Localização Ectópica',
                    'opcoes' => 'Mediastino Superior;Retroesofágica;Intratireoidiana;Outra'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Profundidade da Lesão Ectópica',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Não aplicável',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

            ]

        ],


        //==========================================
        // DOPPLER COLORIDO
        //==========================================

        [
            'nome' => 'Avaliação Doppler Colorido',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Vascularização da Lesão',
                    'opcoes' => 'Ausente;Periférica;Central;Mista'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxo Vascular das Paratireoides',
                    'opcoes' => 'Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão Vascular Suspeito',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // RELAÇÃO COM A TIREOIDE
        //==========================================

        [
            'nome' => 'Relação com a Tireoide',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Relação com a Tireoide',
                    'opcoes' => 'Independente;Aderida;Intra-tireoidiana'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alterações Tireoidianas Associadas',
                    'opcoes' => 'Ausentes;Nódulos;Cistos;Bócio;Outras'
                ],

            ]

        ],


        //==========================================
        // LINFONODOS CERVICAIS
        //==========================================

        [
            'nome' => 'Linfonodos Cervicais',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Linfonodos Cervicais',
                    'opcoes' => 'Normais;Aumentados;Alterados'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Maior Diâmetro do Linfonodo',
                    'unidade' => 'mm',
                    'valor_referencia' => '< 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Características dos Linfonodos',
                    'opcoes' => 'Benignas;Suspeitas'
                ],

            ]

        ],


        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],


        //==========================================
        // ANEXOS
        //==========================================

        [
            'nome' => 'Anexos',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagens do Exame',
                    'extensoes_permitidas' => 'jpg,jpeg,png,pdf'
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'HEM001',
    'nome' => 'Hemácias',
    'categoria' => 'Laboratório / Hematologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Material Biológico',
                    'tamanho_maximo' => 100
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // CONTAGEM DE HEMÁCIAS
        //==========================================

        [
            'nome' => 'Contagem de Hemácias',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Número de Hemácias',
                    'unidade' => 'milhões/mm³',
                    'valor_referencia' => 'Homens: 4.5 - 6.0 | Mulheres: 4.0 - 5.5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação da Contagem de Hemácias',
                    'opcoes' => 'Normal;Reduzida;Aumentada'
                ],

            ]

        ],


        //==========================================
        // ÍNDICES HEMATIMÉTRICOS
        //==========================================

        [
            'nome' => 'Índices Hematimétricos',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Corpuscular Médio (VCM)',
                    'unidade' => 'fL',
                    'valor_referencia' => '80 - 100',
                    'valor_minimo' => 40,
                    'valor_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do VCM',
                    'opcoes' => 'Microcítica;Normocítica;Macrocítica'
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina Corpuscular Média (HCM)',
                    'unidade' => 'pg',
                    'valor_referencia' => '27 - 33',
                    'valor_minimo' => 10,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do HCM',
                    'opcoes' => 'Hipocrômica;Normocrômica;Hipercrômica'
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Hemoglobina Corpuscular Média (CHCM)',
                    'unidade' => 'g/dL',
                    'valor_referencia' => '32 - 36',
                    'valor_minimo' => 10,
                    'valor_maximo' => 50
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Amplitude de Distribuição das Hemácias (RDW)',
                    'unidade' => '%',
                    'valor_referencia' => '11 - 15',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

            ]

        ],


        //==========================================
        // HEMOGLOBINA E HEMATÓCRITO
        //==========================================

        [
            'nome' => 'Hemoglobina e Hematócrito',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina',
                    'unidade' => 'g/dL',
                    'valor_referencia' => 'Homens: 13 - 17 | Mulheres: 12 - 16',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Hematócrito',
                    'unidade' => '%',
                    'valor_referencia' => 'Homens: 40 - 52 | Mulheres: 36 - 46',
                    'valor_minimo' => 0,
                    'valor_maximo' => 80
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Hematócrito',
                    'opcoes' => 'Normal;Reduzido;Elevado'
                ],

            ]

        ],


        //==========================================
        // MORFOLOGIA DAS HEMÁCIAS
        //==========================================

        [
            'nome' => 'Morfologia das Hemácias',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Anisocitose',
                    'opcoes' => 'Ausente;Leve;Moderada;Intensa'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Poiquilocitose',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipocromia',
                    'opcoes' => 'Ausente;Leve;Moderada;Intensa'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Microcitose',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Macrocitose',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Esferócitos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Drepanócitos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fragmentação Eritrocitária',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // ALTERAÇÕES ASSOCIADAS
        //==========================================

        [
            'nome' => 'Alterações Associadas',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Inclusões Eritrocitárias',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Reticulócitos Avaliados',
                    'opcoes' => 'Não Avaliado;Normal;Aumentado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais Compatíveis com Anemia',
                    'opcoes' => 'Ausentes;Sugestivos;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais Compatíveis com Policitemia',
                    'opcoes' => 'Ausentes;Sugestivos;Presentes'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'HEM002',
    'nome' => 'Leucócitos',
    'categoria' => 'Laboratório / Hematologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Material Biológico',
                    'tamanho_maximo' => 100
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // CONTAGEM TOTAL DE LEUCÓCITOS
        //==========================================

        [
            'nome' => 'Contagem Total de Leucócitos',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Leucócitos Totais',
                    'unidade' => 'mil/mm³',
                    'valor_referencia' => '4.000 - 11.000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação dos Leucócitos',
                    'opcoes' => 'Normal;Leucopenia;Leucocitose'
                ],

            ]

        ],


        //==========================================
        // DIFERENCIAL DE LEUCÓCITOS
        //==========================================

        [
            'nome' => 'Contagem Diferencial de Leucócitos',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Neutrófilos (%)',
                    'unidade' => '%',
                    'valor_referencia' => '40 - 70',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Neutrófilos Absolutos',
                    'unidade' => 'mil/mm³',
                    'valor_referencia' => '1.500 - 7.500',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Linfócitos (%)',
                    'unidade' => '%',
                    'valor_referencia' => '20 - 45',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Linfócitos Absolutos',
                    'unidade' => 'mil/mm³',
                    'valor_referencia' => '1.000 - 4.000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Monócitos (%)',
                    'unidade' => '%',
                    'valor_referencia' => '2 - 10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Monócitos Absolutos',
                    'unidade' => 'mil/mm³',
                    'valor_referencia' => '200 - 800',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Eosinófilos (%)',
                    'unidade' => '%',
                    'valor_referencia' => '1 - 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Eosinófilos Absolutos',
                    'unidade' => 'mil/mm³',
                    'valor_referencia' => '20 - 500',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Basófilos (%)',
                    'unidade' => '%',
                    'valor_referencia' => '0 - 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Basófilos Absolutos',
                    'unidade' => 'mil/mm³',
                    'valor_referencia' => '0 - 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

            ]

        ],


        //==========================================
        // CLASSIFICAÇÃO LEUCOCITÁRIA
        //==========================================

        [
            'nome' => 'Classificação Morfológica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Neutrófilos em Bastão',
                    'opcoes' => 'Ausentes;Normais;Aumentados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Desvio à Esquerda',
                    'opcoes' => 'Ausente;Leve;Moderado;Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Granulações Tóxicas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Linfócitos Atípicos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alterações Morfológicas Leucocitárias',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // ALTERAÇÕES CLÍNICAS ASSOCIADAS
        //==========================================

        [
            'nome' => 'Avaliação Clínica Laboratorial',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais Laboratoriais de Infecção',
                    'opcoes' => 'Ausentes;Sugestivos;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão Inflamatório',
                    'opcoes' => 'Ausente;Agudo;Crônico'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Origem da Alteração',
                    'opcoes' => 'Infecciosa;Inflamatória;Medicamentosa;Hematológica;Outra'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];






$dadosExame = [
    'codigo' => 'URO001',
    'nome' => 'Células Epiteliais',
    'categoria' => 'Laboratório / Urinálise',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Material Biológico',
                    'tamanho_maximo' => 100
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DAS CÉLULAS EPITELIAIS
        //==========================================

        [
            'nome' => 'Contagem de Células Epiteliais',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Quantidade de Células Epiteliais',
                    'unidade' => 'por campo',
                    'valor_referencia' => '0 - 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação da Quantidade',
                    'opcoes' => 'Ausentes;Raras;Poucas;Moderadas;Numerosas'
                ],

            ]

        ],


        //==========================================
        // TIPO DE CÉLULAS EPITELIAIS
        //==========================================

        [
            'nome' => 'Tipos de Células Epiteliais',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Epiteliais Escamosas',
                    'opcoes' => 'Ausentes;Raras;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Epiteliais Transicionais',
                    'opcoes' => 'Ausentes;Raras;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Epiteliais Tubulares Renais',
                    'opcoes' => 'Ausentes;Raras;Presentes'
                ],

            ]

        ],


        //==========================================
        // CARACTERÍSTICAS MORFOLÓGICAS
        //==========================================

        [
            'nome' => 'Características Morfológicas',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto das Células',
                    'opcoes' => 'Normal;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Atipias Celulares',
                    'opcoes' => 'Ausentes;Suspeitas;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alterações Degenerativas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Origem das Células',
                    'opcoes' => 'Descamação Normal;Inflamatória;Urotelial;Renal;Indeterminada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestão de Contaminação da Amostra',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Investigação Complementar',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'MIC001',
    'nome' => 'Trichomonas vaginalis (Pesquisa de Tricomonas)',
    'categoria' => 'Laboratório / Microbiologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Material Biológico',
                    'tamanho_maximo' => 100
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Local da Coleta',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // PESQUISA DE TRICHOMONAS VAGINALIS
        //==========================================

        [
            'nome' => 'Pesquisa de Trichomonas vaginalis',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Pesquisa',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Visualização do Parasita',
                    'opcoes' => 'Não Visualizado;Visualizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Quantidade Observada',
                    'opcoes' => 'Ausente;Rara;Pouca;Moderada;Numerosa'
                ],

            ]

        ],


        //==========================================
        // CARACTERÍSTICAS MICROSCÓPICAS
        //==========================================

        [
            'nome' => 'Avaliação Microscópica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Mobilidade dos Tricomonas',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Morfologia Observada',
                    'opcoes' => 'Compatível com Trichomonas vaginalis;Não Característica'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Protozoários',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // ASPECTOS ASSOCIADOS
        //==========================================

        [
            'nome' => 'Achados Associados',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Leucócitos na Amostra',
                    'opcoes' => 'Ausentes;Raros;Aumentados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Epiteliais',
                    'opcoes' => 'Ausentes;Poucas;Numerosas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Flora Bacteriana',
                    'opcoes' => 'Normal;Alterada;Aumentada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Hemácias',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DO MATERIAL
        //==========================================

        [
            'nome' => 'Qualidade da Amostra',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Adequação da Amostra',
                    'opcoes' => 'Adequada;Inadequada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Conservação do Material',
                    'opcoes' => 'Adequada;Prejudicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // INFORMAÇÕES COMPLEMENTARES
        //==========================================

        [
            'nome' => 'Informações Complementares',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Tricomoníase',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Recomendação de Tratamento',
                    'opcoes' => 'Não Avaliado;Conforme Avaliação Médica'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Pós-Tratamento',
                    'opcoes' => 'Não Realizado;Realizado'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'MIC002',
    'nome' => 'Bactérias (Pesquisa em Exsudato Uretral/Vaginal)',
    'categoria' => 'Laboratório / Microbiologia',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Material Coletado',
                    'opcoes' => 'Exsudato Vaginal;Exsudato Uretral'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Local da Coleta',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO MICROSCÓPICA
        //==========================================

        [
            'nome' => 'Avaliação Microscópica',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Bactérias',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Quantidade de Bactérias',
                    'opcoes' => 'Ausentes;Raras;Poucas;Moderadas;Numerosas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto da Flora Bacteriana',
                    'opcoes' => 'Flora Normal;Alterada;Predomínio Bacteriano'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Morfologia Bacteriana Predominante',
                    'opcoes' => 'Cocos;Bacilos;Cocobacilos;Mista;Não Identificada'
                ],

            ]

        ],


        //==========================================
        // FLORA VAGINAL
        //==========================================

        [
            'nome' => 'Avaliação da Flora Vaginal',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Lactobacilos',
                    'opcoes' => 'Preservados;Reduzidos;Ausentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Predomínio da Flora',
                    'opcoes' => 'Lactobacilar;Bacteriana Mista;Cocobacilar'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Vaginoses Bacterianas',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Guia (Clue Cells)',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // EXAME DE EXSUDATO URETRAL
        //==========================================

        [
            'nome' => 'Avaliação do Exsudato Uretral',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Diplococos Gram Negativos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Bactérias Intracelulares',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Achado Sugestivo de Gonococo',
                    'opcoes' => 'Não Sugestivo;Sugestivo'
                ],

            ]

        ],


        //==========================================
        // CÉLULAS INFLAMATÓRIAS
        //==========================================

        [
            'nome' => 'Resposta Inflamatória',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Leucócitos no Campo Microscópico',
                    'unidade' => 'por campo',
                    'valor_referencia' => 'Ausentes ou poucos',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Intensidade da Inflamação',
                    'opcoes' => 'Ausente;Leve;Moderada;Intensa'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Piócitos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // OUTROS ACHADOS MICROBIOLÓGICOS
        //==========================================

        [
            'nome' => 'Outros Achados',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Leveduras/Cândida',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Trichomonas vaginalis',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Outros Microrganismos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // CULTURA (QUANDO APLICÁVEL)
        //==========================================

        [
            'nome' => 'Cultura Bacteriana',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Cultura Realizada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Cultura',
                    'opcoes' => 'Negativa;Positiva'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Bactéria Isolada',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Antibiograma / Sensibilidade',
                    'tamanho_maximo' => 500
                ],

            ]

        ],


        //==========================================
        // CONCLUSÃO
        //==========================================

        [
            'nome' => 'Conclusão',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];




$dadosExame = [
    'codigo' => 'BIO001',
    'nome' => 'Cloreto na Urina de 24 Horas',
    'categoria' => 'Laboratório / Bioquímica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Urina de 24 Horas'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 250
                ],

            ]

        ],


        //==========================================
        // CARACTERÍSTICAS DA COLETA
        //==========================================

        [
            'nome' => 'Dados da Coleta de Urina 24 Horas',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Urinário Total',
                    'unidade' => 'mL/24h',
                    'valor_referencia' => '800 - 2000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Adequação da Coleta',
                    'opcoes' => 'Adequada;Inadequada;Não Informada'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Tempo de Coleta',
                    'tamanho_maximo' => 50
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE CLORETO
        //==========================================

        [
            'nome' => 'Dosagem de Cloreto Urinário',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Cloreto na Urina',
                    'unidade' => 'mEq/L',
                    'valor_referencia' => '110 - 250',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Excreção de Cloreto em 24 Horas',
                    'unidade' => 'mEq/24h',
                    'valor_referencia' => '110 - 250',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Normal;Reduzido;Aumentado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO LABORATORIAL
        //==========================================

        [
            'nome' => 'Interpretação do Cloreto Urinário',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Excreção de Cloreto',
                    'opcoes' => 'Adequada;Baixa;Elevada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Alteração Hidroeletrolítica',
                    'opcoes' => 'Ausente;Sugestiva;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Perdas de Cloro',
                    'opcoes' => 'Não Sugestiva;Renal;Extrarrenal;Indeterminada'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Correlação Clínica',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação do Equilíbrio Ácido-Base',
                    'opcoes' => 'Não Avaliado;Compatível com Alcalose;Compatível com Acidose'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Hidratação',
                    'opcoes' => 'Normal;Desidratação;Excesso de Hidratação'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Investigação de Distúrbios Renais',
                    'opcoes' => 'Não Sugestiva;Sugestiva'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];








$dadosExame = [
    'codigo' => 'BIO002',
    'nome' => 'Sódio na Urina de 24 Horas',
    'categoria' => 'Laboratório / Bioquímica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Urina de 24 Horas'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 250
                ],

            ]

        ],


        //==========================================
        // CARACTERÍSTICAS DA COLETA
        //==========================================

        [
            'nome' => 'Dados da Coleta de Urina 24 Horas',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Urinário Total',
                    'unidade' => 'mL/24h',
                    'valor_referencia' => '800 - 2000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Adequação da Coleta',
                    'opcoes' => 'Adequada;Inadequada;Não Informada'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Tempo de Coleta',
                    'tamanho_maximo' => 50
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE SÓDIO URINÁRIO
        //==========================================

        [
            'nome' => 'Dosagem de Sódio Urinário',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Sódio na Urina',
                    'unidade' => 'mEq/L',
                    'valor_referencia' => '40 - 220',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Excreção de Sódio em 24 Horas',
                    'unidade' => 'mEq/24h',
                    'valor_referencia' => '40 - 220',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Normal;Reduzido;Aumentado'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO HIDROELETROLÍTICA
        //==========================================

        [
            'nome' => 'Avaliação Hidroeletrolítica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Excreção Renal de Sódio',
                    'opcoes' => 'Adequada;Reduzida;Elevada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Balanço de Sódio',
                    'opcoes' => 'Equilibrado;Retenção de Sódio;Perda de Sódio'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Distúrbio Hidroeletrolítico',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Correlação Clínica',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Volume Extracelular',
                    'opcoes' => 'Normal;Reduzido;Aumentado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Investigação de Hiponatremia',
                    'opcoes' => 'Não Avaliada;Sugestiva;Não Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Investigação de Hipertensão Arterial',
                    'opcoes' => 'Não Avaliada;Sugestiva;Não Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação da Função Renal',
                    'opcoes' => 'Sem Alteração Aparente;Alterada;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'BIO003',
    'nome' => 'Ácido Úrico na Urina de 24 Horas',
    'categoria' => 'Laboratório / Bioquímica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Urina de 24 Horas'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 250
                ],

            ]

        ],


        //==========================================
        // DADOS DA COLETA
        //==========================================

        [
            'nome' => 'Dados da Coleta de Urina 24 Horas',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Urinário Total',
                    'unidade' => 'mL/24h',
                    'valor_referencia' => '800 - 2000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Adequação da Coleta',
                    'opcoes' => 'Adequada;Inadequada;Não Informada'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Tempo de Coleta',
                    'tamanho_maximo' => 50
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE ÁCIDO ÚRICO
        //==========================================

        [
            'nome' => 'Dosagem de Ácido Úrico Urinário',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Ácido Úrico na Urina',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Variável conforme laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Excreção de Ácido Úrico em 24 Horas',
                    'unidade' => 'mg/24h',
                    'valor_referencia' => '250 - 750',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Normal;Reduzido;Aumentado'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DO METABOLISMO DO ÁCIDO ÚRICO
        //==========================================

        [
            'nome' => 'Avaliação Metabólica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Excreção Urinária de Ácido Úrico',
                    'opcoes' => 'Normal;Hipouricosúria;Hiperuricosúria'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Risco Metabólico Associado',
                    'opcoes' => 'Baixo;Moderado;Elevado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação para Formação de Cálculos',
                    'opcoes' => 'Sem Alteração;Sugestiva de Risco;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE LITÍASE RENAL
        //==========================================

        [
            'nome' => 'Avaliação de Litíase Renal',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico de Cálculo Renal',
                    'opcoes' => 'Não;Sim;Não Informado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Cálculo Suspeito',
                    'opcoes' => 'Ácido Úrico;Cálcio;Misto;Não Determinado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliação Complementar',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Correlação Clínica',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Gota/Hiperuricemia',
                    'opcoes' => 'Não Avaliada;Sem Alteração;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação da Função Renal',
                    'opcoes' => 'Sem Alteração Aparente;Alterada;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dieta Rica em Purinas (Informação Clínica)',
                    'opcoes' => 'Não;Sim;Não Informado'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'BIO004',
    'nome' => 'Proteínas na Urina de 24 Horas',
    'categoria' => 'Laboratório / Bioquímica',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Urina de 24 Horas'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 250
                ],

            ]

        ],


        //==========================================
        // DADOS DA COLETA
        //==========================================

        [
            'nome' => 'Dados da Coleta de Urina 24 Horas',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Urinário Total',
                    'unidade' => 'mL/24h',
                    'valor_referencia' => '800 - 2000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Adequação da Coleta',
                    'opcoes' => 'Adequada;Inadequada;Não Informada'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Tempo de Coleta',
                    'tamanho_maximo' => 50
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE PROTEÍNAS
        //==========================================

        [
            'nome' => 'Dosagem de Proteínas Urinárias',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Proteínas na Urina',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Variável conforme laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Proteína Total Urinária em 24 Horas',
                    'unidade' => 'mg/24h',
                    'valor_referencia' => '< 150',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação da Proteinúria',
                    'opcoes' => 'Normal;Aumentada;Proteinúria Leve;Proteinúria Moderada;Proteinúria Importante'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DA PROTEINÚRIA
        //==========================================

        [
            'nome' => 'Avaliação da Proteinúria',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Proteinúria',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau da Proteinúria',
                    'opcoes' => 'Fisiológica;Discreta;Moderada;Acentuada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão de Perda Proteica',
                    'opcoes' => 'Não Avaliado;Glomerular;Tubular;Mista'
                ],

            ]

        ],


        //==========================================
        // ALBUMINÚRIA
        //==========================================

        [
            'nome' => 'Avaliação de Albumina Urinária',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Albumina Urinária em 24 Horas',
                    'unidade' => 'mg/24h',
                    'valor_referencia' => '< 30',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação da Albuminúria',
                    'opcoes' => 'Normal;A1 (Normal a Levemente Aumentada);A2 (Moderadamente Aumentada);A3 (Muito Aumentada)'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO RENAL
        //==========================================

        [
            'nome' => 'Avaliação da Função Renal',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Origem da Proteinúria',
                    'opcoes' => 'Não Sugestiva;Glomerular;Tubular;Pós-Renal'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Lesão Renal',
                    'opcoes' => 'Não;Sim;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Nefropatia',
                    'opcoes' => 'Sem Alteração Aparente;Sugestiva;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Correlação Clínica',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Diabetes Mellitus',
                    'opcoes' => 'Não Avaliada;Sem Alteração;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Hipertensão Arterial',
                    'opcoes' => 'Não Avaliada;Sem Alteração;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Síndrome Nefrótica',
                    'opcoes' => 'Não Sugestiva;Sugestiva;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'BIO005',
    'nome' => 'Depuração de Creatinina (Clearance de Creatinina)',
    'categoria' => 'Laboratório / Função Renal',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Urina de 24 Horas + Sangue'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 250
                ],

            ]

        ],


        //==========================================
        // DADOS DA COLETA URINÁRIA
        //==========================================

        [
            'nome' => 'Dados da Coleta de Urina 24 Horas',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Urinário Total',
                    'unidade' => 'mL/24h',
                    'valor_referencia' => '800 - 2000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Adequação da Coleta',
                    'opcoes' => 'Adequada;Inadequada;Não Informada'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Tempo de Coleta',
                    'tamanho_maximo' => 50
                ],

            ]

        ],


        //==========================================
        // DADOS DO PACIENTE
        //==========================================

        [
            'nome' => 'Dados Antropométricos',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Peso Corporal',
                    'unidade' => 'kg',
                    'valor_referencia' => 'Informado conforme paciente',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Altura',
                    'unidade' => 'cm',
                    'valor_referencia' => 'Informado conforme paciente',
                    'valor_minimo' => 0,
                    'valor_maximo' => 300
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Superfície Corporal',
                    'unidade' => 'm²',
                    'valor_referencia' => '1.5 - 2.2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE CREATININA
        //==========================================

        [
            'nome' => 'Dosagem de Creatinina',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Creatinina Sérica',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Homens: 0.7 - 1.3 | Mulheres: 0.6 - 1.1',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Creatinina Urinária',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Variável conforme laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Excreção de Creatinina em 24 Horas',
                    'unidade' => 'mg/24h',
                    'valor_referencia' => 'Homens: 1000 - 2000 | Mulheres: 800 - 1800',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5000
                ],

            ]

        ],


        //==========================================
        // CLEARANCE DE CREATININA
        //==========================================

        [
            'nome' => 'Depuração de Creatinina',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Clearance de Creatinina',
                    'unidade' => 'mL/min',
                    'valor_referencia' => 'Adultos: 90 - 140',
                    'valor_minimo' => 0,
                    'valor_maximo' => 300
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Clearance Corrigido pela Superfície Corporal',
                    'unidade' => 'mL/min/1.73m²',
                    'valor_referencia' => '90 - 120',
                    'valor_minimo' => 0,
                    'valor_maximo' => 300
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação da Função Renal',
                    'opcoes' => 'Preservada;Redução Leve;Redução Moderada;Redução Importante;Falência Renal'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DA FUNÇÃO RENAL
        //==========================================

        [
            'nome' => 'Avaliação da Função Renal',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Taxa de Filtração Glomerular Estimada',
                    'opcoes' => 'Normal;Reduzida;Muito Reduzida;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Comprometimento Renal',
                    'opcoes' => 'Sem Alteração;Leve;Moderado;Grave'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Doença Renal Crônica',
                    'opcoes' => 'Não;Sim;Não Avaliado'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Correlação Clínica',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação para Ajuste de Medicamentos',
                    'opcoes' => 'Não Necessário;Pode Ser Necessário;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação Pré-Operatória',
                    'opcoes' => 'Sem Restrição Aparente;Necessita Avaliação;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Monitorização de Função Renal',
                    'opcoes' => 'Normal;Alterada;Necessita Acompanhamento'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'URO002',
    'nome' => 'Contagem de Addis',
    'categoria' => 'Laboratório / Urinálise',

    'parametros' => [

        //==========================================
        // IDENTIFICAÇÃO DO EXAME
        //==========================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Urina de 12 Horas;Urina de 24 Horas;Urina de 3 Horas'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 250
                ],

            ]

        ],


        //==========================================
        // DADOS DA COLETA
        //==========================================

        [
            'nome' => 'Dados da Coleta Urinária',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Urinário Coletado',
                    'unidade' => 'mL',
                    'valor_referencia' => 'Conforme tempo de coleta informado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo de Coleta',
                    'unidade' => 'horas',
                    'valor_referencia' => 'Conforme protocolo do laboratório',
                    'valor_minimo' => 1,
                    'valor_maximo' => 48
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Adequação da Coleta',
                    'opcoes' => 'Adequada;Inadequada;Não Informada'
                ],

            ]

        ],


        //==========================================
        // CONTAGEM DE ELEMENTOS URINÁRIOS
        //==========================================

        [
            'nome' => 'Elementos Formados na Urina',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemácias',
                    'unidade' => 'elementos/hora',
                    'valor_referencia' => '< 500.000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação das Hemácias',
                    'opcoes' => 'Normal;Aumentada'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Leucócitos',
                    'unidade' => 'elementos/hora',
                    'valor_referencia' => '< 1.000.000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20000000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação dos Leucócitos',
                    'opcoes' => 'Normal;Aumentado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Cilindros',
                    'unidade' => 'elementos/hora',
                    'valor_referencia' => '< 5.000',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação dos Cilindros',
                    'opcoes' => 'Normal;Aumentado'
                ],

            ]

        ],


        //==========================================
        // TIPOS DE CILINDROS
        //==========================================

        [
            'nome' => 'Avaliação dos Cilindros Urinários',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Cilindros Hialinos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cilindros Granulosos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cilindros Hemáticos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cilindros Leucocitários',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cilindros Epiteliais',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // OUTROS ELEMENTOS URINÁRIOS
        //==========================================

        [
            'nome' => 'Outros Elementos',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Epiteliais',
                    'opcoes' => 'Ausentes;Raras;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cristais',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Bactérias',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Leveduras',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO RENAL
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Hematúria',
                    'opcoes' => 'Ausente;Sugestiva;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Leucocitúria',
                    'opcoes' => 'Ausente;Sugestiva;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Lesão Renal',
                    'opcoes' => 'Não;Sim;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Processo Inflamatório Urinário',
                    'opcoes' => 'Ausente;Sugestivo;Presente'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Exame',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],

    ]
];

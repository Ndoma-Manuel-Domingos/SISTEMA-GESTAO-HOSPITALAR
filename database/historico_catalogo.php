<?php
$HemogramaCompleto = [
    'codigo' => 'HMG001',
    'nome' => 'Hemograma Completo',
    'categoria' => 'Hematologia',
    'parametros' => [


        // =====================================
        // ERITROGRAMA
        // =====================================

        [
            'nome' => 'Eritrograma',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemácias',
                    'unidade' => 'milhões/µL',
                    'valor_referencia' => '4.5 - 5.9',
                    'valor_minimo' => 4.5,
                    'valor_maximo' => 5.9
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina',
                    'unidade' => 'g/dL',
                    'valor_referencia' => '13.5 - 17.5',
                    'valor_minimo' => 13.5,
                    'valor_maximo' => 17.5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Hematócrito',
                    'unidade' => '%',
                    'valor_referencia' => '41 - 53',
                    'valor_minimo' => 41,
                    'valor_maximo' => 53
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'VCM - Volume Corpuscular Médio',
                    'unidade' => 'fL',
                    'valor_referencia' => '80 - 100',
                    'valor_minimo' => 80,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'HCM - Hemoglobina Corpuscular Média',
                    'unidade' => 'pg',
                    'valor_referencia' => '27 - 33',
                    'valor_minimo' => 27,
                    'valor_maximo' => 33
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'CHCM - Concentração de Hemoglobina Corpuscular Média',
                    'unidade' => 'g/dL',
                    'valor_referencia' => '32 - 36',
                    'valor_minimo' => 32,
                    'valor_maximo' => 36
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'RDW - Amplitude de Distribuição dos Eritrócitos',
                    'unidade' => '%',
                    'valor_referencia' => '11.5 - 14.5',
                    'valor_minimo' => 11.5,
                    'valor_maximo' => 14.5
                ],

            ]
        ],



        // =====================================
        // LEUCOGRAMA
        // =====================================


        [
            'nome' => 'Leucograma',
            'ordem' => 2,

            'subparametros' => [


                [
                    'tipo' => 'numero',
                    'nome' => 'Leucócitos Totais',
                    'unidade' => '/µL',
                    'valor_referencia' => '4000 - 11000',
                    'valor_minimo' => 4000,
                    'valor_maximo' => 11000
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Neutrófilos',
                    'unidade' => '%',
                    'valor_referencia' => '40 - 70',
                    'valor_minimo' => 40,
                    'valor_maximo' => 70
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Neutrófilos Bastonetes',
                    'unidade' => '%',
                    'valor_referencia' => '0 - 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Linfócitos',
                    'unidade' => '%',
                    'valor_referencia' => '20 - 45',
                    'valor_minimo' => 20,
                    'valor_maximo' => 45
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Monócitos',
                    'unidade' => '%',
                    'valor_referencia' => '2 - 10',
                    'valor_minimo' => 2,
                    'valor_maximo' => 10
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Eosinófilos',
                    'unidade' => '%',
                    'valor_referencia' => '1 - 6',
                    'valor_minimo' => 1,
                    'valor_maximo' => 6
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Basófilos',
                    'unidade' => '%',
                    'valor_referencia' => '0 - 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2
                ],

            ]
        ],




        // =====================================
        // PLAQUETOGRAMA
        // =====================================


        [
            'nome' => 'Plaquetograma',
            'ordem' => 3,

            'subparametros' => [


                [
                    'tipo' => 'numero',
                    'nome' => 'Plaquetas',
                    'unidade' => '/µL',
                    'valor_referencia' => '150000 - 450000',
                    'valor_minimo' => 150000,
                    'valor_maximo' => 450000
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'VPM - Volume Plaquetário Médio',
                    'unidade' => 'fL',
                    'valor_referencia' => '7.5 - 11.5',
                    'valor_minimo' => 7.5,
                    'valor_maximo' => 11.5
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'PDW - Distribuição Plaquetária',
                    'unidade' => '%',
                    'valor_referencia' => '9 - 17',
                    'valor_minimo' => 9,
                    'valor_maximo' => 17
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'PCT - Plaquetócrito',
                    'unidade' => '%',
                    'valor_referencia' => '0.20 - 0.40',
                    'valor_minimo' => 0.20,
                    'valor_maximo' => 0.40
                ],


            ]
        ],




        // =====================================
        // MORFOLOGIA CELULAR
        // =====================================


        [
            'nome' => 'Morfologia Celular',
            'ordem' => 4,


            'subparametros' => [


                [
                    'tipo' => 'lista',
                    'nome' => 'Anisocitose',
                    'opcoes' => 'Ausente;Discreta;Moderada;Intensa'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Microcitose',
                    'opcoes' => 'Ausente;Discreta;Moderada;Intensa'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Macrocitose',
                    'opcoes' => 'Ausente;Discreta;Moderada;Intensa'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Hipocromia',
                    'opcoes' => 'Ausente;Discreta;Moderada;Intensa'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Poiquilocitose',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Esferócitos',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Esquizócitos',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


    ]
];

$Glicemia = [

    'codigo' => 'GLI001',

    'nome' => 'Glicemia',

    'categoria' => 'Bioquímica',


    'parametros' => [

        [
            'nome' => 'Glicose Sanguínea',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Glicemia em Jejum',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '70 - 99',
                    'valor_minimo' => 70,
                    'valor_maximo' => 99
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Glicemia Pós-Prandial (2 horas após refeição)',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 140',
                    'valor_minimo' => 0,
                    'valor_maximo' => 140
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Glicemia Casual',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Variável conforme avaliação clínica',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estado da Coleta',
                    'opcoes' => 'Jejum;Pós-Prandial;Casual'
                ],

            ]
        ],



        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação da Glicemia em Jejum',
                    'opcoes' => 'Normal;Glicemia Alterada em Jejum;Diabetes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação Pós-Prandial',
                    'opcoes' => 'Normal;Alterada;Diabetes'
                ],

            ]
        ],



        [
            'nome' => 'Informações Complementares',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 100
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 500
                ],


            ]
        ]

    ]

];

$Urina = [

    'codigo' => 'URI001',

    'nome' => 'Exame de Urina (EAS)',

    'categoria' => 'Urinálise',


    'parametros' => [


        // =====================================
        // ANÁLISE FÍSICA
        // =====================================

        [
            'nome' => 'Análise Física',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Cor',
                    'opcoes' => 'Amarelo Claro;Amarelo;Âmbar;Avermelhado;Castanho;Esverdeado;Outro'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto',
                    'opcoes' => 'Límpido;Levemente Turvo;Turvo'
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Densidade',
                    'unidade' => '',
                    'valor_referencia' => '1.005 - 1.030',
                    'valor_minimo' => 1.005,
                    'valor_maximo' => 1.030
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'pH Urinário',
                    'unidade' => '',
                    'valor_referencia' => '5.0 - 8.0',
                    'valor_minimo' => 5,
                    'valor_maximo' => 8
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Odor',
                    'tamanho_maximo' => 100
                ],

            ]

        ],



        // =====================================
        // ANÁLISE QUÍMICA
        // =====================================

        [
            'nome' => 'Análise Química',
            'ordem' => 2,


            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Proteínas',
                    'opcoes' => 'Negativo;Traços;1+;2+;3+;4+'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Glicose',
                    'opcoes' => 'Negativo;Traços;1+;2+;3+;4+'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Corpos Cetônicos',
                    'opcoes' => 'Negativo;Traços;1+;2+;3+'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Bilirrubina',
                    'opcoes' => 'Negativo;Positivo'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Urobilinogênio',
                    'opcoes' => 'Normal;Aumentado;Ausente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Sangue/Hemoglobina',
                    'opcoes' => 'Negativo;Traços;1+;2+;3+'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Nitrito',
                    'opcoes' => 'Negativo;Positivo'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Esterase Leucocitária',
                    'opcoes' => 'Negativo;Traços;Positivo'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Albumina',
                    'opcoes' => 'Negativo;Presente'
                ],

            ]

        ],



        // =====================================
        // SEDIMENTOSCOPIA
        // =====================================

        [
            'nome' => 'Sedimentoscopia',
            'ordem' => 3,


            'subparametros' => [


                [
                    'tipo' => 'numero',
                    'nome' => 'Hemácias',
                    'unidade' => '/campo',
                    'valor_referencia' => '0 - 2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Leucócitos',
                    'unidade' => '/campo',
                    'valor_referencia' => '0 - 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Células Epiteliais',
                    'opcoes' => 'Ausentes;Raras;Poucas;Moderadas;Numerosas'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Bactérias',
                    'opcoes' => 'Ausentes;Raras;Poucas;Moderadas;Numerosas'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Muco',
                    'opcoes' => 'Ausente;Escasso;Moderado;Aumentado'
                ],


            ]

        ],



        // =====================================
        // CILINDROS URINÁRIOS
        // =====================================

        [
            'nome' => 'Cilindros',
            'ordem' => 4,


            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Cilindros Hialinos',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Cilindros Granulosos',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Cilindros Hemáticos',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Cilindros Leucocitários',
                    'opcoes' => 'Ausente;Presente'
                ],


            ]

        ],



        // =====================================
        // CRISTAIS
        // =====================================

        [
            'nome' => 'Cristais',
            'ordem' => 5,


            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Oxalato de Cálcio',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Ácido Úrico',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Fosfato Triplo',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Cistina',
                    'opcoes' => 'Ausente;Presente'
                ],


            ]

        ],



        // =====================================
        // MICROORGANISMOS
        // =====================================

        [
            'nome' => 'Microorganismos',
            'ordem' => 6,


            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Leveduras/Fungos',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Parasitas',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Observações do Sedimento',
                    'tamanho_maximo' => 500
                ],

            ]

        ]

    ]

];


$HIV = [

    'codigo' => 'VIH001',

    'nome' => 'Teste de HIV (VIH) 1/2',

    'categoria' => 'Imunologia / Sorologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO TESTE
        // =====================================

        [
            'nome' => 'Identificação do Teste',
            'ordem' => 1,


            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma;Sangue Total'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Teste Rápido;ELISA;Quimioluminescência;Imunoensaio de 4ª Geração;Outro'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Nome do Kit/Reagente',
                    'tamanho_maximo' => 200
                ],


            ]

        ],



        // =====================================
        // RESULTADO SOROLÓGICO
        // =====================================

        [
            'nome' => 'Resultado Sorológico',
            'ordem' => 2,


            'subparametros' => [


                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado HIV 1/2',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Índice S/CO ou COI',
                    'unidade' => 'Índice',
                    'valor_referencia' => '< 1.0 Não Reagente / ≥ 1.0 Reagente',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Antígeno p24',
                    'opcoes' => 'Não Detectado;Detectado'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos Anti-HIV 1',
                    'opcoes' => 'Não Detectado;Detectado'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos Anti-HIV 2',
                    'opcoes' => 'Não Detectado;Detectado'
                ],


            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação do Resultado',
            'ordem' => 3,


            'subparametros' => [


                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação Final',
                    'opcoes' => 'Resultado Negativo;Resultado Positivo;Resultado Inconclusivo'
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Interpretação/Observação Laboratorial',
                    'tamanho_maximo' => 1000
                ],


            ]

        ],



        // =====================================
        // CONTROLE E VALIDAÇÃO
        // =====================================

        [
            'nome' => 'Controle do Exame',
            'ordem' => 4,


            'subparametros' => [


                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno do Teste',
                    'opcoes' => 'Válido;Inválido'
                ],


                [
                    'tipo' => 'booleano',
                    'nome' => 'Necessita Confirmação',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],


            ]

        ]

    ]

];


$Malaria = [

    'codigo' => 'MAL001',

    'nome' => 'Teste de Malária (Gota Espessa)',

    'categoria' => 'Parasitologia / Hematologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DA AMOSTRA
        // =====================================

        [
            'nome' => 'Identificação da Amostra',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Sangue Total;Sangue Capilar;Sangue Venoso'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Local da Coleta',
                    'tamanho_maximo' => 100
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

            ]

        ],



        // =====================================
        // EXAME MICROSCÓPICO
        // =====================================

        [
            'nome' => 'Microscopia da Gota Espessa',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Pesquisa de Plasmodium',
                    'opcoes' => 'Negativo;Positivo'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Parasitas',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Espécie de Plasmodium Identificada',
                    'opcoes' => 'Não Identificado;Plasmodium falciparum;Plasmodium vivax;Plasmodium malariae;Plasmodium ovale;Plasmodium knowlesi;Mista'
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Parasitemia',
                    'unidade' => 'parasitas/µL',
                    'valor_referencia' => 'Ausente',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Percentual de Parasitas',
                    'unidade' => '%',
                    'valor_referencia' => '0',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],


            ]

        ],



        // =====================================
        // ESTÁGIO DO PARASITA
        // =====================================

        [
            'nome' => 'Estágio Parasitário',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Trofozoítos',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Esquizontes',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Gametócitos',
                    'opcoes' => 'Ausente;Presente'
                ],


            ]

        ],



        // =====================================
        // AVALIAÇÃO DO CAMPO MICROSCÓPICO
        // =====================================

        [
            'nome' => 'Avaliação Microscópica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Quantidade de Parasitas Observados',
                    'opcoes' => 'Nenhum;Raro;Poucos;Moderado;Numeroso'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Qualidade da Lâmina',
                    'opcoes' => 'Adequada;Inadequada'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Observações Microscópicas',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],



        // =====================================
        // RESULTADO FINAL
        // =====================================

        [
            'nome' => 'Conclusão do Exame',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Diagnóstico Laboratorial',
                    'opcoes' => 'Ausência de Plasmodium;Presença de Plasmodium'
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Comentário Final do Laboratório',
                    'tamanho_maximo' => 1000
                ],


            ]

        ]

    ]

];


$UreiaCreatinina = [

    'codigo' => 'URECRE001',

    'nome' => 'Ureia e Creatinina',

    'categoria' => 'Bioquímica / Função Renal',


    'parametros' => [


        // =====================================
        // UREIA
        // =====================================

        [
            'nome' => 'Ureia',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Ureia Sérica',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '15 - 45',
                    'valor_minimo' => 15,
                    'valor_maximo' => 45
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Nitrogênio Ureico (BUN)',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '7 - 20',
                    'valor_minimo' => 7,
                    'valor_maximo' => 20
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 100
                ],


            ]

        ],



        // =====================================
        // CREATININA
        // =====================================

        [
            'nome' => 'Creatinina',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Creatinina Sérica',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '0.6 - 1.3',
                    'valor_minimo' => 0.6,
                    'valor_maximo' => 1.3
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Creatinina Urinária',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Variável conforme coleta',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 100
                ],


            ]

        ],



        // =====================================
        // TAXA DE FILTRAÇÃO GLOMERULAR
        // =====================================

        [
            'nome' => 'Avaliação da Função Renal',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'TFG - Taxa de Filtração Glomerular Estimada',
                    'unidade' => 'mL/min/1.73m²',
                    'valor_referencia' => '>= 90',
                    'valor_minimo' => 90,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação da Função Renal',
                    'opcoes' => 'Normal;Redução Leve;Redução Moderada;Redução Grave;Falência Renal'
                ],


            ]

        ],



        // =====================================
        // RELAÇÃO UREIA/CREATININA
        // =====================================

        [
            'nome' => 'Relação Bioquímica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Relação Ureia/Creatinina',
                    'unidade' => 'Razão',
                    'valor_referencia' => '10 - 20',
                    'valor_minimo' => 10,
                    'valor_maximo' => 20
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1000
                ],

            ]

        ]

    ]

];


$FuncaoHepatica = [

    'codigo' => 'HEP001',

    'nome' => 'Função Hepática',

    'categoria' => 'Bioquímica / Hepatologia',


    'parametros' => [


        // =====================================
        // ENZIMAS HEPÁTICAS
        // =====================================

        [
            'nome' => 'Enzimas Hepáticas',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'ALT / TGP (Alanina Aminotransferase)',
                    'unidade' => 'U/L',
                    'valor_referencia' => '7 - 56',
                    'valor_minimo' => 7,
                    'valor_maximo' => 56
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'AST / TGO (Aspartato Aminotransferase)',
                    'unidade' => 'U/L',
                    'valor_referencia' => '10 - 40',
                    'valor_minimo' => 10,
                    'valor_maximo' => 40
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'GGT (Gama Glutamil Transferase)',
                    'unidade' => 'U/L',
                    'valor_referencia' => '9 - 48',
                    'valor_minimo' => 9,
                    'valor_maximo' => 48
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Fosfatase Alcalina (FA)',
                    'unidade' => 'U/L',
                    'valor_referencia' => '40 - 129',
                    'valor_minimo' => 40,
                    'valor_maximo' => 129
                ],

            ]

        ],



        // =====================================
        // BILIRRUBINAS
        // =====================================

        [
            'nome' => 'Bilirrubinas',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Bilirrubina Total',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '0.3 - 1.2',
                    'valor_minimo' => 0.3,
                    'valor_maximo' => 1.2
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Bilirrubina Direta (Conjugada)',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '0.0 - 0.3',
                    'valor_minimo' => 0,
                    'valor_maximo' => 0.3
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Bilirrubina Indireta (Não Conjugada)',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '0.2 - 0.9',
                    'valor_minimo' => 0.2,
                    'valor_maximo' => 0.9
                ],

            ]

        ],



        // =====================================
        // PROTEÍNAS PLASMÁTICAS
        // =====================================

        [
            'nome' => 'Proteínas Plasmáticas',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Proteínas Totais',
                    'unidade' => 'g/dL',
                    'valor_referencia' => '6.0 - 8.3',
                    'valor_minimo' => 6,
                    'valor_maximo' => 8.3
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Albumina',
                    'unidade' => 'g/dL',
                    'valor_referencia' => '3.5 - 5.0',
                    'valor_minimo' => 3.5,
                    'valor_maximo' => 5
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Globulinas',
                    'unidade' => 'g/dL',
                    'valor_referencia' => '2.0 - 3.5',
                    'valor_minimo' => 2,
                    'valor_maximo' => 3.5
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Relação Albumina/Globulina',
                    'unidade' => 'Razão',
                    'valor_referencia' => '1.0 - 2.5',
                    'valor_minimo' => 1,
                    'valor_maximo' => 2.5
                ],

            ]

        ],



        // =====================================
        // FUNÇÃO DE SÍNTESE HEPÁTICA
        // =====================================

        [
            'nome' => 'Função de Síntese Hepática',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo de Protrombina (TP)',
                    'unidade' => 'segundos',
                    'valor_referencia' => '11 - 14',
                    'valor_minimo' => 11,
                    'valor_maximo' => 14
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'INR',
                    'unidade' => 'Índice',
                    'valor_referencia' => '0.8 - 1.2',
                    'valor_minimo' => 0.8,
                    'valor_maximo' => 1.2
                ],


            ]

        ],



        // =====================================
        // INFORMAÇÕES COMPLEMENTARES
        // =====================================

        [
            'nome' => 'Informações Complementares',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 100
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1000
                ],


            ]

        ]

    ]

];


$Radiografia = [

    'codigo' => 'RAD001',

    'nome' => 'Radiografia',

    'categoria' => 'Imagem / Radiologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Radiografia',
                    'opcoes' => 'Radiografia Simples;Radiografia Contrastada;Radiografia Digital'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Região Anatômica',
                    'opcoes' => 'Tórax;Crânio;Coluna Cervical;Coluna Torácica;Coluna Lombar;Bacia;Membros Superiores;Membros Inferiores;Abdómen;Outros'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Descrição da Região Examinada',
                    'tamanho_maximo' => 200
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data da Realização'
                ],

            ]

        ],



        // =====================================
        // TÉCNICA DO EXAME
        // =====================================

        [
            'nome' => 'Técnica Radiológica',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Incidência',
                    'opcoes' => 'AP (Antero Posterior);PA (Postero Anterior);Perfil;Oblíqua;Outras'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Técnica Utilizada',
                    'tamanho_maximo' => 500
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Qualidade da Imagem',
                    'opcoes' => 'Adequada;Limitada;Inadequada'
                ],

            ]

        ],



        // =====================================
        // ACHADOS RADIOGRÁFICOS
        // =====================================

        [
            'nome' => 'Achados Radiográficos',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição dos Achados',
                    'tamanho_maximo' => 2000
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Alterações',
                    'opcoes' => 'Sem Alterações;Alteração Presente'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Localização da Alteração',
                    'tamanho_maximo' => 300
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Alteração',
                    'opcoes' => 'Fratura;Luxação;Lesão;Inflamação;Infecção;Calcificação;Massa;Outro'
                ],

            ]

        ],



        // =====================================
        // CONCLUSÃO
        // =====================================

        [
            'nome' => 'Conclusão Radiológica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Radiologista',
                    'tamanho_maximo' => 2000
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Final',
                    'opcoes' => 'Normal;Alterado;Necessita Avaliação Complementar'
                ],


            ]

        ],



        // =====================================
        // ANEXOS
        // =====================================

        [
            'nome' => 'Documentos e Imagens',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagem Radiográfica',
                    'extensoes_permitidas' => 'jpg;jpeg;png;dicom'
                ],


                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagem Adicional',
                    'extensoes_permitidas' => 'jpg;jpeg;png;dicom'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Nome do Radiologista',
                    'tamanho_maximo' => 150
                ],

            ]

        ]

    ]

];


$Ecografia = [

    'codigo' => 'ECO001',

    'nome' => 'Ecografia (Ultrassonografia)',

    'categoria' => 'Imagem / Ultrassonografia',

    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Ecografia',
                    'opcoes' => 'Ecografia Abdominal;Ecografia Pélvica;Ecografia Obstétrica;Ecografia Renal;Ecografia Hepática;Ecografia Tiroide;Ecografia Mamária;Ecografia Cardíaca;Ecografia Vascular;Outros'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Órgão ou Região Avaliada',
                    'tamanho_maximo' => 200
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data da Realização'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 500
                ],

            ]

        ],



        // =====================================
        // TÉCNICA DO EXAME
        // =====================================

        [
            'nome' => 'Técnica Ultrassonográfica',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Transdutor',
                    'opcoes' => 'Convexo;Linear;Endocavitário;Cardíaco;Outro'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Modo de Avaliação',
                    'opcoes' => 'Modo B;Doppler Colorido;Doppler Pulsado;3D;4D'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Descrição da Técnica',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],



        // =====================================
        // MEDIDAS E CARACTERÍSTICAS
        // =====================================

        [
            'nome' => 'Medidas e Características',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'texto',
                    'nome' => 'Dimensões do Órgão Avaliado',
                    'tamanho_maximo' => 500
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Ecogenicidade',
                    'opcoes' => 'Normal;Aumentada;Reduzida;Heterogênea'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Lesão',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Descrição da Lesão',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],



        // =====================================
        // DOPPLER (QUANDO APLICÁVEL)
        // =====================================

        [
            'nome' => 'Avaliação Doppler',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'booleano',
                    'nome' => 'Foi realizado Doppler',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade do Fluxo Sanguíneo',
                    'unidade' => 'cm/s',
                    'valor_referencia' => 'Conforme vaso avaliado',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Descrição Doppler',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],



        // =====================================
        // CONCLUSÃO DO EXAME
        // =====================================

        [
            'nome' => 'Conclusão Ecográfica',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Final',
                    'opcoes' => 'Normal;Alterado;Necessita Avaliação Complementar'
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão do Médico Radiologista',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // IMAGENS E DOCUMENTOS
        // =====================================

        [
            'nome' => 'Anexos',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagem Ecográfica',
                    'extensoes_permitidas' => 'jpg;jpeg;png;dicom'
                ],


                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagem Adicional',
                    'extensoes_permitidas' => 'jpg;jpeg;png;dicom'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Nome do Médico Responsável',
                    'tamanho_maximo' => 150
                ],

            ]

        ]

    ]

];


$TesteGravidez = [

    'codigo' => 'BHCG001',

    'nome' => 'Beta-hCG (Teste de Gravidez)',

    'categoria' => 'Imunologia / Hormônios',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Urina'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Teste',
                    'opcoes' => 'Qualitativo;Quantitativo'
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // RESULTADO QUALITATIVO
        // =====================================

        [
            'nome' => 'Resultado Qualitativo',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado do Teste de Gravidez',
                    'opcoes' => 'Negativo;Positivo;Indeterminado'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de hCG',
                    'opcoes' => 'Não Detectado;Detectado'
                ],

            ]

        ],



        // =====================================
        // RESULTADO QUANTITATIVO
        // =====================================

        [
            'nome' => 'Dosagem Quantitativa',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Beta-hCG Quantitativo',
                    'unidade' => 'mUI/mL',
                    'valor_referencia' => '< 5 Negativo / 5 - 25 Indeterminado / > 25 Positivo',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação do Valor',
                    'opcoes' => 'Negativo;Zona Cinzenta;Positivo'
                ],

            ]

        ],



        // =====================================
        // INFORMAÇÕES GESTACIONAIS
        // =====================================

        [
            'nome' => 'Informações Gestacionais',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Idade Gestacional Estimada',
                    'unidade' => 'semanas',
                    'valor_referencia' => 'Conforme valor de Beta-hCG',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data da Última Menstruação (DUM)'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Situação Clínica',
                    'opcoes' => 'Suspeita de Gravidez;Acompanhamento Gestacional;Controle Pós-Aborto;Outros'
                ],

            ]

        ],



        // =====================================
        // CONTROLE E OBSERVAÇÕES
        // =====================================

        [
            'nome' => 'Observações Laboratoriais',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Comentários do Laboratório',
                    'tamanho_maximo' => 1000
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Final',
                    'opcoes' => 'Teste Não Reagente;Teste Reagente;Necessita Repetição'
                ],

            ]

        ]

    ]

];


$Fezes = [

    'codigo' => 'FEZ001',

    'nome' => 'Exame de Fezes (Coprocultura / Parasitológico)',

    'categoria' => 'Parasitologia / Microbiologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DA AMOSTRA
        // =====================================

        [
            'nome' => 'Identificação da Amostra',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Exame',
                    'opcoes' => 'Exame Parasitológico de Fezes;Coprocultura;Ambos'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Fezes Frescas;Fezes Conservadas;Swab Retal'
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Conservante Utilizado',
                    'tamanho_maximo' => 100
                ],

            ]

        ],



        // =====================================
        // CARACTERÍSTICAS MACROSCÓPICAS
        // =====================================

        [
            'nome' => 'Análise Macroscópica',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto das Fezes',
                    'opcoes' => 'Formadas;Pastosas;Líquidas;Mucoide;Sanguinolenta'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Cor',
                    'opcoes' => 'Castanha;Amarelada;Escura;Esverdeada;Avermelhada;Outra'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Muco',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Sangue',
                    'opcoes' => 'Ausente;Presente'
                ],


            ]

        ],



        // =====================================
        // EXAME PARASITOLÓGICO
        // =====================================

        [
            'nome' => 'Exame Parasitológico de Fezes',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral Parasitológico',
                    'opcoes' => 'Negativo;Positivo'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Protozoários',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Helmintos',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Parasita Identificado',
                    'tamanho_maximo' => 300
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Forma Parasitária Encontrada',
                    'opcoes' => 'Não Identificada;Ovos;Larvas;Cistos;Trofozoítos'
                ],


            ]

        ],



        // =====================================
        // COPROCULTURA
        // =====================================

        [
            'nome' => 'Coprocultura',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Crescimento Bacteriano',
                    'opcoes' => 'Sem Crescimento;Crescimento Bacteriano'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Cultura',
                    'opcoes' => 'Negativo;Positivo'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Bactéria Identificada',
                    'tamanho_maximo' => 300
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Teste de Sensibilidade Antimicrobiana',
                    'opcoes' => 'Não Realizado;Realizado'
                ],


            ]

        ],



        // =====================================
        // MICROSCOPIA
        // =====================================

        [
            'nome' => 'Microscopia',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Leucócitos nas Fezes',
                    'opcoes' => 'Ausente;Raros;Moderados;Numerosos'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Hemácias nas Fezes',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Leveduras',
                    'opcoes' => 'Ausente;Presente'
                ],


            ]

        ],



        // =====================================
        // CONCLUSÃO
        // =====================================

        [
            'nome' => 'Conclusão do Exame',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Final',
                    'opcoes' => 'Normal;Alterado;Positivo para Agente Infeccioso'
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],


            ]

        ]

    ]

];


$ColesterolTriglicerídeos = [

    'codigo' => 'LIP001',

    'nome' => 'Perfil Lipídico (Colesterol e Triglicerídeos)',

    'categoria' => 'Bioquímica / Metabolismo Lipídico',


    'parametros' => [


        // =====================================
        // COLETA E INFORMAÇÕES DO EXAME
        // =====================================

        [
            'nome' => 'Informações da Coleta',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Condição da Coleta',
                    'opcoes' => 'Jejum;Sem Jejum'
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo de Jejum',
                    'unidade' => 'horas',
                    'valor_referencia' => '0 - 12',
                    'valor_minimo' => 0,
                    'valor_maximo' => 12
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],


            ]

        ],



        // =====================================
        // COLESTEROL TOTAL
        // =====================================

        [
            'nome' => 'Colesterol Total',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol Total',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 190',
                    'valor_minimo' => 0,
                    'valor_maximo' => 190
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação Colesterol Total',
                    'opcoes' => 'Desejável;Elevado'
                ],

            ]

        ],



        // =====================================
        // HDL
        // =====================================

        [
            'nome' => 'HDL - Colesterol Bom',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'HDL Colesterol',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '> 40',
                    'valor_minimo' => 40,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação HDL',
                    'opcoes' => 'Adequado;Baixo'
                ],

            ]

        ],



        // =====================================
        // LDL
        // =====================================

        [
            'nome' => 'LDL - Colesterol Ruim',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'LDL Colesterol',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 130',
                    'valor_minimo' => 0,
                    'valor_maximo' => 130
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação LDL',
                    'opcoes' => 'Ótimo;Desejável;Elevado;Muito Elevado'
                ],

            ]

        ],



        // =====================================
        // VLDL
        // =====================================

        [
            'nome' => 'VLDL',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'VLDL Colesterol',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '5 - 40',
                    'valor_minimo' => 5,
                    'valor_maximo' => 40
                ],

            ]

        ],



        // =====================================
        // TRIGLICERÍDEOS
        // =====================================

        [
            'nome' => 'Triglicerídeos',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Triglicerídeos',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 150',
                    'valor_minimo' => 0,
                    'valor_maximo' => 150
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação Triglicerídeos',
                    'opcoes' => 'Normal;Limítrofe;Elevado;Muito Elevado'
                ],

            ]

        ],



        // =====================================
        // CÁLCULOS DO PERFIL LIPÍDICO
        // =====================================

        [
            'nome' => 'Cálculos Lipídicos',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol Não-HDL',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 160',
                    'valor_minimo' => 0,
                    'valor_maximo' => 160
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Relação Colesterol Total / HDL',
                    'unidade' => 'Índice',
                    'valor_referencia' => '< 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Relação LDL / HDL',
                    'unidade' => 'Índice',
                    'valor_referencia' => '< 3.5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 3.5
                ],

            ]

        ],



        // =====================================
        // OBSERVAÇÕES
        // =====================================

        [
            'nome' => 'Observações Laboratoriais',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Comentários do Laboratório',
                    'tamanho_maximo' => 1000
                ],

            ]

        ]

    ]

];


$Eletrocardiograma = [

    'codigo' => 'ECG001',

    'nome' => 'Eletrocardiograma (ECG)',

    'categoria' => 'Cardiologia / Exames Funcionais',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de ECG',
                    'opcoes' => 'Repouso;Esforço;Holter;Outro'
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Número de Derivações',
                    'unidade' => 'derivações',
                    'valor_referencia' => '12',
                    'valor_minimo' => 12,
                    'valor_maximo' => 12
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data da Realização'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Indicação Clínica',
                    'tamanho_maximo' => 500
                ],

            ]

        ],



        // =====================================
        // FREQUÊNCIA CARDÍACA
        // =====================================

        [
            'nome' => 'Frequência Cardíaca',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Frequência Cardíaca',
                    'unidade' => 'bpm',
                    'valor_referencia' => '60 - 100',
                    'valor_minimo' => 60,
                    'valor_maximo' => 100
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Ritmo Cardíaco',
                    'opcoes' => 'Sinusal;Irregular;Arrítmico;Outro'
                ],

            ]

        ],



        // =====================================
        // INTERVALOS CARDÍACOS
        // =====================================

        [
            'nome' => 'Intervalos e Medidas',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Intervalo PR',
                    'unidade' => 'ms',
                    'valor_referencia' => '120 - 200',
                    'valor_minimo' => 120,
                    'valor_maximo' => 200
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Duração do QRS',
                    'unidade' => 'ms',
                    'valor_referencia' => '70 - 120',
                    'valor_minimo' => 70,
                    'valor_maximo' => 120
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Intervalo QT',
                    'unidade' => 'ms',
                    'valor_referencia' => '350 - 440',
                    'valor_minimo' => 350,
                    'valor_maximo' => 440
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Intervalo QTc (Corrigido)',
                    'unidade' => 'ms',
                    'valor_referencia' => '< 450',
                    'valor_minimo' => 0,
                    'valor_maximo' => 450
                ],


            ]

        ],



        // =====================================
        // EIXO CARDÍACO
        // =====================================

        [
            'nome' => 'Eixo Cardíaco',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Eixo Elétrico Cardíaco',
                    'unidade' => 'graus',
                    'valor_referencia' => '-30 a +90',
                    'valor_minimo' => -30,
                    'valor_maximo' => 90
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Posição do Eixo',
                    'opcoes' => 'Normal;Desviado à Esquerda;Desviado à Direita'
                ],

            ]

        ],



        // =====================================
        // ANÁLISE DAS ONDAS
        // =====================================

        [
            'nome' => 'Análise Eletrocardiográfica',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Onda P',
                    'opcoes' => 'Normal;Alterada;Ausente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Complexo QRS',
                    'opcoes' => 'Normal;Alargado;Alterado'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Segmento ST',
                    'opcoes' => 'Normal;Elevação;Depressão;Alterado'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Onda T',
                    'opcoes' => 'Normal;Invertida;Alterada'
                ],


            ]

        ],



        // =====================================
        // ALTERAÇÕES CARDÍACAS
        // =====================================

        [
            'nome' => 'Alterações Detectadas',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Arritmia',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Bloqueios Cardíacos',
                    'opcoes' => 'Ausente;Bloqueio AV;Bloqueio de Ramo Direito;Bloqueio de Ramo Esquerdo;Outro'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais de Isquemia',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Descrição das Alterações',
                    'tamanho_maximo' => 1500
                ],


            ]

        ],



        // =====================================
        // CONCLUSÃO
        // =====================================

        [
            'nome' => 'Conclusão do ECG',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Final',
                    'opcoes' => 'ECG Normal;ECG Alterado;Necessita Avaliação Médica'
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Laudo do Cardiologista',
                    'tamanho_maximo' => 2000
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Nome do Médico Responsável',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // ANEXOS
        // =====================================

        [
            'nome' => 'Anexos',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'imagem',
                    'nome' => 'Imagem do ECG',
                    'extensoes_permitidas' => 'jpg;jpeg;png;pdf'
                ],

            ]

        ]

    ]

];


$Hepatite = [

    'codigo' => 'HBV001',

    'nome' => 'Teste de Hepatite B (HBsAg)',

    'categoria' => 'Imunologia / Sorologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma;Sangue Total'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Teste Rápido;ELISA;Quimioluminescência;Imunoensaio'
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Reagente/KIT Utilizado',
                    'tamanho_maximo' => 200
                ],

            ]

        ],



        // =====================================
        // ANTÍGENO HBsAg
        // =====================================

        [
            'nome' => 'Detecção do HBsAg',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado HBsAg',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Índice S/CO ou COI',
                    'unidade' => 'Índice',
                    'valor_referencia' => '< 1.0 Não Reagente / ≥ 1.0 Reagente',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Presença do Antígeno de Superfície da Hepatite B',
                    'opcoes' => 'Não Detectado;Detectado'
                ],

            ]

        ],



        // =====================================
        // MARCADORES COMPLEMENTARES
        // =====================================

        [
            'nome' => 'Marcadores da Hepatite B',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Anti-HBs',
                    'opcoes' => 'Não Reagente;Reagente'
                ],


                [
                    'tipo' => 'numero',
                    'nome' => 'Anti-HBs Quantitativo',
                    'unidade' => 'mUI/mL',
                    'valor_referencia' => '< 10 Não Imune / ≥ 10 Imune',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Anti-HBc Total',
                    'opcoes' => 'Não Reagente;Reagente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Anti-HBc IgM',
                    'opcoes' => 'Não Reagente;Reagente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'HBeAg',
                    'opcoes' => 'Não Reagente;Reagente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Anti-HBe',
                    'opcoes' => 'Não Reagente;Reagente'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Situação Sorológica',
                    'opcoes' => 'Sem Evidência de Infecção;Infecção Atual;Infecção Passada;Imunidade por Vacina;Resultado Inconclusivo'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Confirmação',
                    'opcoes' => 'Sim;Não'
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Interpretação/Observações',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO LABORATÓRIO
        // =====================================

        [
            'nome' => 'Controle do Exame',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Válido;Inválido'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Nome do Responsável Técnico',
                    'tamanho_maximo' => 150
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$ProteínaReativa = [

    'codigo' => 'PCR001',

    'nome' => 'Proteína C Reativa (PCR)',

    'categoria' => 'Imunologia / Inflamação',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Imunoturbidimetria;Nefelometria;Teste Rápido;Outro'
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],


            ]

        ],



        // =====================================
        // PCR QUALITATIVA
        // =====================================

        [
            'nome' => 'Proteína C Reativa Qualitativa',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Negativo;Positivo'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Inflamação',
                    'opcoes' => 'Não Detectada;Detectada'
                ],

            ]

        ],



        // =====================================
        // PCR QUANTITATIVA
        // =====================================

        [
            'nome' => 'Proteína C Reativa Quantitativa',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'PCR Quantitativa',
                    'unidade' => 'mg/L',
                    'valor_referencia' => '< 5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Normal;Elevada;Muito Elevada'
                ],


            ]

        ],



        // =====================================
        // PCR ULTRASSENSÍVEL
        // =====================================

        [
            'nome' => 'PCR Ultrassensível (PCR-us)',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'PCR Ultrassensível',
                    'unidade' => 'mg/L',
                    'valor_referencia' => '< 1 baixo risco; 1-3 risco intermediário; >3 alto risco',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Risco Cardiovascular',
                    'opcoes' => 'Baixo;Intermediário;Alto'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Inflamatória',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Processo Inflamatório',
                    'opcoes' => 'Ausente;Presente'
                ],


                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Elevação',
                    'opcoes' => 'Normal;Leve;Moderada;Alta'
                ],


                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1000
                ],


            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle do Exame',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'texto',
                    'nome' => 'Método/Equipamento Utilizado',
                    'tamanho_maximo' => 200
                ],


                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],


                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],


            ]

        ]

    ]

];


$VelocidadeSedimentacao = [

    'codigo' => 'VS001',

    'nome' => 'Velocidade de Sedimentação (VS)',

    'categoria' => 'Hematologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Sangue Total'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticoagulante',
                    'opcoes' => 'Citrato de Sódio;EDTA'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Westergren;Wintrobe;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

            ]

        ],



        // =====================================
        // RESULTADO DA VS
        // =====================================

        [
            'nome' => 'Resultado da Velocidade de Sedimentação',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade de Sedimentação (1ª Hora)',
                    'unidade' => 'mm/h',
                    'valor_referencia' => 'Homens: 0 - 15 | Mulheres: 0 - 20',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Velocidade de Sedimentação (2ª Hora)',
                    'unidade' => 'mm/h',
                    'valor_referencia' => 'Conforme protocolo laboratorial',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Normal;Elevado;Muito Elevado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Processo Inflamatório',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Processo Infeccioso',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Doença Autoimune',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // OBSERVAÇÕES
        // =====================================

        [
            'nome' => 'Observações',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$GrupoSanguíneo = [

    'codigo' => 'ABORH001',

    'nome' => 'Grupo Sanguíneo e Fator Rh',

    'categoria' => 'Imuno-Hematologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Sangue Total;Sangue com EDTA'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Hemaglutinação em Lâmina;Hemaglutinação em Tubo;Gel Centrifugação;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

            ]

        ],



        // =====================================
        // SISTEMA ABO
        // =====================================

        [
            'nome' => 'Tipagem ABO',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Grupo Sanguíneo',
                    'opcoes' => 'A;B;AB;O'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anti-A',
                    'opcoes' => 'Positivo;Negativo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anti-B',
                    'opcoes' => 'Positivo;Negativo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipagem Reversa',
                    'opcoes' => 'Compatível;Incompatível;Não Realizada'
                ],

            ]

        ],



        // =====================================
        // FATOR RH
        // =====================================

        [
            'nome' => 'Fator Rh',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fator Rh (D)',
                    'opcoes' => 'Positivo;Negativo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Du (Rh Fraco)',
                    'opcoes' => 'Positivo;Negativo;Não Realizado'
                ],

            ]

        ],



        // =====================================
        // RESULTADO FINAL
        // =====================================

        [
            'nome' => 'Resultado Final',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo Sanguíneo Final',
                    'opcoes' => 'A+;A-;B+;B-;AB+;AB-;O+;O-'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Confirmado',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // CONTROLE DE QUALIDADE
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$TesteSifilis = [

    'codigo' => 'VDRL001',

    'nome' => 'Teste de Sífilis (VDRL)',

    'categoria' => 'Imunologia / Sorologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma;Líquido Cefalorraquidiano (LCR)'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'VDRL;RPR'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Kit/Reagente Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // RESULTADO DO VDRL
        // =====================================

        [
            'nome' => 'Resultado Sorológico',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado VDRL',
                    'opcoes' => 'Não Reagente;Reagente;Inconclusivo'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Título do VDRL',
                    'tamanho_maximo' => 20
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Floculação',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],



        // =====================================
        // TESTES TREPONÊMICOS
        // =====================================

        [
            'nome' => 'Testes Confirmatórios',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste Treponêmico Realizado',
                    'opcoes' => 'Não;FTA-ABS;TPHA;TPPA;Teste Rápido;ELISA'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado do Teste Treponêmico',
                    'opcoes' => 'Não Reagente;Reagente;Não Realizado'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Conclusão',
                    'opcoes' => 'Sem Evidência Sorológica;Sífilis Ativa;Cicatriz Sorológica;Resultado Inconclusivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Teste Confirmatório',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DE QUALIDADE
        // =====================================

        [
            'nome' => 'Controle do Exame',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Válido;Inválido'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$HemoglobinaGlicada = [

    'codigo' => 'HBA1C001',

    'nome' => 'Hemoglobina Glicada (HbA1c)',

    'categoria' => 'Bioquímica / Endocrinologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Sangue Total (EDTA)'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'HPLC;Imunoturbidimetria;Imunoensaio;Afinidade por Boronato;Capilar Eletroforese;Outro'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // RESULTADO DA HBA1C
        // =====================================

        [
            'nome' => 'Resultado da Hemoglobina Glicada',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina Glicada (HbA1c)',
                    'unidade' => '%',
                    'valor_referencia' => '< 5.7',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5.6
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'HbA1c (IFCC)',
                    'unidade' => 'mmol/mol',
                    'valor_referencia' => '< 39',
                    'valor_minimo' => 0,
                    'valor_maximo' => 38
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Glicemia Média Estimada (eAG)',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Calculada a partir da HbA1c',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO CLÍNICA
        // =====================================

        [
            'nome' => 'Interpretação Clínica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação',
                    'opcoes' => 'Normal;Pré-diabetes;Diabetes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Glicêmico',
                    'opcoes' => 'Excelente;Bom;Regular;Inadequado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Meta Terapêutica Atingida',
                    'opcoes' => 'Sim;Não;Não Aplicável'
                ],

            ]

        ],



        // =====================================
        // FATORES QUE PODEM INTERFERIR
        // =====================================

        [
            'nome' => 'Fatores Interferentes',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'booleano',
                    'nome' => 'Presença de Hemoglobinopatia',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Paciente Transfundido nos Últimos 3 Meses',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Anemia Pode Interferir no Resultado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

            ]

        ],



        // =====================================
        // OBSERVAÇÕES
        // =====================================

        [
            'nome' => 'Observações',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$Coagulograma = [

    'codigo' => 'COAG001',

    'nome' => 'Coagulograma (TP, TTPa, INR)',

    'categoria' => 'Hematologia / Coagulação',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Plasma Citratado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Coagulometria Óptica;Coagulometria Mecânica;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // TEMPO DE PROTROMBINA (TP)
        // =====================================

        [
            'nome' => 'Tempo de Protrombina (TP)',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo de Protrombina',
                    'unidade' => 'segundos',
                    'valor_referencia' => '10 - 14',
                    'valor_minimo' => 10,
                    'valor_maximo' => 14
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Atividade da Protrombina',
                    'unidade' => '%',
                    'valor_referencia' => '70 - 100',
                    'valor_minimo' => 70,
                    'valor_maximo' => 100
                ],

            ]

        ],



        // =====================================
        // INR
        // =====================================

        [
            'nome' => 'INR',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'INR',
                    'unidade' => '',
                    'valor_referencia' => '0.8 - 1.2 (não anticoagulado)',
                    'valor_minimo' => 0.8,
                    'valor_maximo' => 1.2
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Paciente em Uso de Anticoagulante',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // TTPa
        // =====================================

        [
            'nome' => 'Tempo de Tromboplastina Parcial Ativada (TTPa)',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'TTPa',
                    'unidade' => 'segundos',
                    'valor_referencia' => '25 - 35',
                    'valor_minimo' => 25,
                    'valor_maximo' => 35
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Relação TTPa',
                    'unidade' => '',
                    'valor_referencia' => '0.8 - 1.2',
                    'valor_minimo' => 0.8,
                    'valor_maximo' => 1.2
                ],

            ]

        ],



        // =====================================
        // FIBRINOGÊNIO
        // =====================================

        [
            'nome' => 'Fibrinogênio',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Fibrinogênio',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '200 - 400',
                    'valor_minimo' => 200,
                    'valor_maximo' => 400
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tempo de Protrombina',
                    'opcoes' => 'Normal;Prolongado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'TTPa',
                    'opcoes' => 'Normal;Prolongado;Reduzido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Risco de Sangramento',
                    'opcoes' => 'Baixo;Moderado;Elevado'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Laboratorial',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];

$AcidoÚrico = [

    'codigo' => 'AU001',

    'nome' => 'Ácido Úrico',

    'categoria' => 'Bioquímica',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma;Urina'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Enzimático Colorimétrico;Uricase;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // ÁCIDO ÚRICO SÉRICO
        // =====================================

        [
            'nome' => 'Ácido Úrico Sérico',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Ácido Úrico',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Homens: 3.4 - 7.0 | Mulheres: 2.4 - 6.0',
                    'valor_minimo' => 2.4,
                    'valor_maximo' => 7.0
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // ÁCIDO ÚRICO URINÁRIO
        // =====================================

        [
            'nome' => 'Ácido Úrico Urinário (quando solicitado)',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Ácido Úrico na Urina 24 Horas',
                    'unidade' => 'mg/24h',
                    'valor_referencia' => '250 - 750',
                    'valor_minimo' => 250,
                    'valor_maximo' => 750
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hiperuricemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipouricemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Risco para Gota',
                    'opcoes' => 'Baixo;Moderado;Elevado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Risco para Litíase Renal',
                    'opcoes' => 'Baixo;Moderado;Elevado'
                ],

            ]

        ],



        // =====================================
        // OBSERVAÇÕES
        // =====================================

        [
            'nome' => 'Observações',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$CálcioSérico = [

    'codigo' => 'CAL001',

    'nome' => 'Cálcio Sérico',

    'categoria' => 'Bioquímica',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Colorimétrico;Espectrofotométrico;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // CÁLCIO TOTAL
        // =====================================

        [
            'nome' => 'Cálcio Total',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Cálcio Total',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '8.5 - 10.5',
                    'valor_minimo' => 8.5,
                    'valor_maximo' => 10.5
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Cálcio Total',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // CÁLCIO IONIZADO
        // =====================================

        [
            'nome' => 'Cálcio Ionizado',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Cálcio Ionizado',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '1.12 - 1.32',
                    'valor_minimo' => 1.12,
                    'valor_maximo' => 1.32
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Cálcio Ionizado',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipocalcemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipercalcemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Correlação com Albumina',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];

$PotássioSérico = [

    'codigo' => 'K001',

    'nome' => 'Potássio Sérico',

    'categoria' => 'Bioquímica / Eletrólitos',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma;Sangue Total'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Eletrodo Íon Seletivo (ISE);Potenciometria Direta;Potenciometria Indireta'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // DOSAGEM DO POTÁSSIO
        // =====================================

        [
            'nome' => 'Dosagem do Potássio',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Potássio',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '3.5 - 5.1',
                    'valor_minimo' => 3.5,
                    'valor_maximo' => 5.1
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO DE QUALIDADE DA AMOSTRA
        // =====================================

        [
            'nome' => 'Avaliação da Amostra',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Hemólise',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipocalemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipercalemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estado do Resultado',
                    'opcoes' => 'Normal;Alterado;Valor Crítico'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$CloroSérico = [

    'codigo' => 'CL001',

    'nome' => 'Cloro Sérico',

    'categoria' => 'Bioquímica / Eletrólitos',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma;Sangue Total'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Eletrodo Íon Seletivo (ISE);Potenciometria Direta;Potenciometria Indireta;Colorimétrico'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // DOSAGEM DO CLORETO
        // =====================================

        [
            'nome' => 'Dosagem do Cloro',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Cloro',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '98 - 106',
                    'valor_minimo' => 98,
                    'valor_maximo' => 106
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // RELAÇÃO COM EQUILÍBRIO ÁCIDO-BASE
        // =====================================

        [
            'nome' => 'Avaliação Ácido-Base',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração do Equilíbrio Ácido-Base',
                    'opcoes' => 'Sem Alteração;Possível Acidose;Possível Alcalose'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Bicarbonato (HCO3-) Associado',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '22 - 26',
                    'valor_minimo' => 22,
                    'valor_maximo' => 26
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipocloremia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipercloremia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estado do Resultado',
                    'opcoes' => 'Normal;Alterado;Valor Crítico'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];

$MagnésioSérico = [

    'codigo' => 'MG001',

    'nome' => 'Magnésio Sérico',

    'categoria' => 'Bioquímica / Eletrólitos',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma;Urina'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Colorimétrico;Espectrofotométrico;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // MAGNÉSIO SÉRICO
        // =====================================

        [
            'nome' => 'Dosagem do Magnésio',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Magnésio Sérico',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '1.7 - 2.4',
                    'valor_minimo' => 1.7,
                    'valor_maximo' => 2.4
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Magnésio Sérico (SI)',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '0.70 - 1.00',
                    'valor_minimo' => 0.70,
                    'valor_maximo' => 1.00
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // MAGNÉSIO URINÁRIO (QUANDO SOLICITADO)
        // =====================================

        [
            'nome' => 'Magnésio Urinário',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Magnésio na Urina 24 Horas',
                    'unidade' => 'mg/24h',
                    'valor_referencia' => 'Conforme laboratório',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO CLÍNICA
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipomagnesemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipermagnesemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração Eletrolítica',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$AlbuminaSérica = [

    'codigo' => 'ALB001',

    'nome' => 'Albumina Sérica',

    'categoria' => 'Bioquímica / Proteínas Plasmáticas',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Verde de Bromocresol (BCG);Vermelho de Bromocresol (BCP);Colorimétrico;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // DOSAGEM DE ALBUMINA
        // =====================================

        [
            'nome' => 'Dosagem de Albumina',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Albumina Sérica',
                    'unidade' => 'g/dL',
                    'valor_referencia' => '3.5 - 5.0',
                    'valor_minimo' => 3.5,
                    'valor_maximo' => 5.0
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Albumina Sérica (SI)',
                    'unidade' => 'g/L',
                    'valor_referencia' => '35 - 50',
                    'valor_minimo' => 35,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Baixa;Normal;Elevada'
                ],

            ]

        ],



        // =====================================
        // PROTEÍNAS TOTAIS E RELAÇÃO
        // =====================================

        [
            'nome' => 'Avaliação Proteica Complementar',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Proteínas Totais',
                    'unidade' => 'g/dL',
                    'valor_referencia' => '6.0 - 8.3',
                    'valor_minimo' => 6.0,
                    'valor_maximo' => 8.3
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Globulinas Calculadas',
                    'unidade' => 'g/dL',
                    'valor_referencia' => '2.0 - 3.5',
                    'valor_minimo' => 2.0,
                    'valor_maximo' => 3.5
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Relação Albumina/Globulina (A/G)',
                    'unidade' => 'Índice',
                    'valor_referencia' => '1.0 - 2.5',
                    'valor_minimo' => 1.0,
                    'valor_maximo' => 2.5
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipoalbuminemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hiperalbuminemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Alteração Hepática',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Alteração Nutricional',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$Amilase = [

    'codigo' => 'AMI001',

    'nome' => 'Amilase',

    'categoria' => 'Bioquímica / Enzimas',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma;Urina'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Enzimático Colorimétrico;Cinético Enzimático;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // AMILASE SÉRICA
        // =====================================

        [
            'nome' => 'Amilase Sérica',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Amilase Total',
                    'unidade' => 'U/L',
                    'valor_referencia' => '30 - 110',
                    'valor_minimo' => 30,
                    'valor_maximo' => 110
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // AMILASE URINÁRIA
        // =====================================

        [
            'nome' => 'Amilase Urinária',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Amilase na Urina',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Conforme laboratório',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Coleta Urinária',
                    'opcoes' => 'Urina Isolada;Urina 24 Horas'
                ],

            ]

        ],



        // =====================================
        // AMILASE PANCREÁTICA
        // =====================================

        [
            'nome' => 'Amilase Pancreática (quando disponível)',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Amilase Pancreática',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hiperamilasemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipoamilasemia',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Alteração Pancreática',
                    'opcoes' => 'Sim;Não;Não Conclusivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Elevação',
                    'opcoes' => 'Normal;Leve;Moderada;Elevada'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$Lipase = [

    'codigo' => 'LIP001',

    'nome' => 'Lipase',

    'categoria' => 'Bioquímica / Enzimas',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Colorimétrico Enzimático;Cinético Enzimático;Imunoensaio;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // DOSAGEM DE LIPASE
        // =====================================

        [
            'nome' => 'Dosagem da Lipase',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Lipase Sérica',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Até 60',
                    'valor_minimo' => 0,
                    'valor_maximo' => 60
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO PANCREÁTICA
        // =====================================

        [
            'nome' => 'Avaliação Pancreática',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Elevação da Lipase',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Elevação',
                    'opcoes' => 'Normal;Leve;Moderada;Maior que 3 vezes o limite superior'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Lesão Pancreática',
                    'opcoes' => 'Sim;Não;Não Conclusivo'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Compatível com Pancreatite',
                    'opcoes' => 'Sim;Não;Necessita Avaliação Clínica'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Alteração Biliar',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Alteração Renal Associada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$GasometriaArterial = [

    'codigo' => 'GAS001',

    'nome' => 'Gasometria Arterial',

    'categoria' => 'Bioquímica / Equilíbrio Ácido-Base',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Sangue Arterial;Sangue Venoso'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Local da Coleta',
                    'opcoes' => 'Artéria Radial;Artéria Femoral;Artéria Braquial;Outro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Analisador de Gases Sanguíneos;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // PARÂMETROS ÁCIDO-BASE
        // =====================================

        [
            'nome' => 'Equilíbrio Ácido-Base',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'pH',
                    'unidade' => '',
                    'valor_referencia' => '7.35 - 7.45',
                    'valor_minimo' => 7.35,
                    'valor_maximo' => 7.45
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'pCO2',
                    'unidade' => 'mmHg',
                    'valor_referencia' => '35 - 45',
                    'valor_minimo' => 35,
                    'valor_maximo' => 45
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'pO2',
                    'unidade' => 'mmHg',
                    'valor_referencia' => '80 - 100',
                    'valor_minimo' => 80,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Bicarbonato (HCO3-)',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '22 - 26',
                    'valor_minimo' => 22,
                    'valor_maximo' => 26
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Excesso de Base (BE)',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '-2 a +2',
                    'valor_minimo' => -2,
                    'valor_maximo' => 2
                ],

            ]

        ],



        // =====================================
        // OXIGENAÇÃO
        // =====================================

        [
            'nome' => 'Avaliação da Oxigenação',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Saturação de Oxigênio (SaO2)',
                    'unidade' => '%',
                    'valor_referencia' => '95 - 100',
                    'valor_minimo' => 95,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'FiO2',
                    'unidade' => '%',
                    'valor_referencia' => '21 - 100',
                    'valor_minimo' => 21,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Relação PaO2/FiO2 (P/F)',
                    'unidade' => '',
                    'valor_referencia' => '> 300',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // ELETRÓLITOS
        // =====================================

        [
            'nome' => 'Eletrólitos na Gasometria',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Sódio (Na+)',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '135 - 145',
                    'valor_minimo' => 135,
                    'valor_maximo' => 145
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Potássio (K+)',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '3.5 - 5.1',
                    'valor_minimo' => 3.5,
                    'valor_maximo' => 5.1
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Cálcio Ionizado',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '1.12 - 1.32',
                    'valor_minimo' => 1.12,
                    'valor_maximo' => 1.32
                ],

            ]

        ],



        // =====================================
        // METABOLISMO
        // =====================================

        [
            'nome' => 'Metabolismo',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Lactato',
                    'unidade' => 'mmol/L',
                    'valor_referencia' => '< 2.0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Glicose',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '70 - 100',
                    'valor_minimo' => 70,
                    'valor_maximo' => 100
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Distúrbio Ácido-Base',
                    'opcoes' => 'Normal;Acidose Metabólica;Alcalose Metabólica;Acidose Respiratória;Alcalose Respiratória;Misto'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Oxigenação',
                    'opcoes' => 'Normal;Hipoxemia;Hiperóxia'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Laboratorial',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$Hemocultura = [

    'codigo' => 'HEMOC001',

    'nome' => 'Hemocultura',

    'categoria' => 'Microbiologia / Bacteriologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Sangue Periférico;Cateter Venoso;Outro'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Quantidade de Frascos Coletados',
                    'unidade' => 'frascos',
                    'valor_referencia' => 'Conforme protocolo',
                    'valor_minimo' => 1,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Frasco',
                    'opcoes' => 'Aeróbio;Anaeróbio;Pediátrico'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Local da Coleta',
                    'tamanho_maximo' => 200
                ],

            ]

        ],



        // =====================================
        // INCUBAÇÃO
        // =====================================

        [
            'nome' => 'Processamento da Amostra',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sistema de Incubação',
                    'opcoes' => 'Automatizado;Manual'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo de Incubação',
                    'unidade' => 'horas',
                    'valor_referencia' => 'Até 5 dias',
                    'valor_minimo' => 0,
                    'valor_maximo' => 120
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Crescimento Microbiano',
                    'opcoes' => 'Detectado;Não Detectado'
                ],

            ]

        ],



        // =====================================
        // RESULTADO MICROBIOLÓGICO
        // =====================================

        [
            'nome' => 'Resultado Microbiológico',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Hemocultura',
                    'opcoes' => 'Negativa;Positiva;Contaminante;Inconclusiva'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Microrganismo Isolado',
                    'tamanho_maximo' => 300
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Microrganismo',
                    'opcoes' => 'Bactéria Gram Positiva;Bactéria Gram Negativa;Levedura;Fungo;Outro'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Gram do Microrganismo',
                    'tamanho_maximo' => 200
                ],

            ]

        ],



        // =====================================
        // IDENTIFICAÇÃO DO AGENTE
        // =====================================

        [
            'nome' => 'Identificação do Agente Infeccioso',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'texto',
                    'nome' => 'Nome do Microrganismo Identificado',
                    'tamanho_maximo' => 300
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método de Identificação',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Importância Clínica',
                    'opcoes' => 'Patogênico;Possível Contaminante;Necessita Correlação Clínica'
                ],

            ]

        ],



        // =====================================
        // ANTIBIOGRAMA
        // =====================================

        [
            'nome' => 'Teste de Sensibilidade aos Antimicrobianos',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Antibiograma Realizado',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Antibióticos Testados',
                    'tamanho_maximo' => 1000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Perfil de Sensibilidade',
                    'opcoes' => 'Sensível;Intermediário;Resistente'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações do Antibiograma',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONCLUSÃO
        // =====================================

        [
            'nome' => 'Conclusão Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Final',
                    'tamanho_maximo' => 2000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Comunicação Imediata',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$Urocultura = [

    'codigo' => 'UROC001',

    'nome' => 'Urocultura',

    'categoria' => 'Microbiologia / Bacteriologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Urina Jato Médio;Urina de Sonda;Punção Suprapúbica;Outro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método de Coleta',
                    'opcoes' => 'Jato Médio;Cateterismo;Coletor Infantil'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Condição da Amostra',
                    'tamanho_maximo' => 200
                ],

            ]

        ],



        // =====================================
        // ANÁLISE DA CULTURA
        // =====================================

        [
            'nome' => 'Crescimento Microbiológico',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Urocultura',
                    'opcoes' => 'Negativa;Positiva;Contaminada;Inconclusiva'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Contagem de Colônias (UFC/mL)',
                    'unidade' => 'UFC/mL',
                    'valor_referencia' => '< 100.000 UFC/mL',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Significância da Bacteriúria',
                    'opcoes' => 'Ausente;Significativa;Não Significativa'
                ],

            ]

        ],



        // =====================================
        // IDENTIFICAÇÃO DO AGENTE
        // =====================================

        [
            'nome' => 'Identificação do Microrganismo',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'texto',
                    'nome' => 'Microrganismo Isolado',
                    'tamanho_maximo' => 300
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Microrganismo',
                    'opcoes' => 'Bactéria Gram Negativa;Bactéria Gram Positiva;Levedura;Outro'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Identificação Completa do Agente',
                    'tamanho_maximo' => 300
                ],

            ]

        ],



        // =====================================
        // ANTIBIOGRAMA
        // =====================================

        [
            'nome' => 'Teste de Sensibilidade aos Antimicrobianos',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Antibiograma Realizado',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Antibióticos Testados',
                    'tamanho_maximo' => 1000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado de Sensibilidade',
                    'opcoes' => 'Sensível;Intermediário;Resistente'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações do Antibiograma',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONTAMINAÇÃO / QUALIDADE
        // =====================================

        [
            'nome' => 'Avaliação da Amostra',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Contaminação',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Quantidade de Microrganismos',
                    'opcoes' => 'Um Microrganismo;Múltiplos Microrganismos'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações da Cultura',
                    'tamanho_maximo' => 1500
                ],

            ]

        ],



        // =====================================
        // CONCLUSÃO
        // =====================================

        [
            'nome' => 'Conclusão Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Diagnóstico Laboratorial Sugestivo',
                    'opcoes' => 'Sem Crescimento Bacteriano;Infecção Urinária;Contaminação'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Final',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$Tuberculose = [

    'codigo' => 'TB001',

    'nome' => 'Teste de Tuberculose (Baciloscopia)',

    'categoria' => 'Microbiologia / Micobacteriologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Escarro;Lavado Broncoalveolar;Aspirado Traqueal;Líquido Pleural;Outro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Número de Amostras Coletadas',
                    'opcoes' => '1 Amostra;2 Amostras;3 Amostras;Outro'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Local da Coleta',
                    'tamanho_maximo' => 200
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO DA AMOSTRA
        // =====================================

        [
            'nome' => 'Avaliação da Amostra',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Qualidade da Amostra',
                    'opcoes' => 'Adequada;Inadequada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto da Amostra',
                    'opcoes' => 'Purulenta;Mucosa;Hemoptoica;Salivar;Outro'
                ],

            ]

        ],



        // =====================================
        // BACILOSCOPIA
        // =====================================

        [
            'nome' => 'Baciloscopia para BAAR',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Método de Coloração',
                    'opcoes' => 'Ziehl-Neelsen;Auramina-Rodamina'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Baciloscopia',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Quantidade de Bacilos Encontrados',
                    'unidade' => 'BAAR/campo',
                    'valor_referencia' => 'Ausência de BAAR',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Carga Bacilar',
                    'opcoes' => 'Negativa;Rara;1+;2+;3+'
                ],

            ]

        ],



        // =====================================
        // TESTES COMPLEMENTARES
        // =====================================

        [
            'nome' => 'Testes Complementares',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Cultura para Mycobacterium',
                    'opcoes' => 'Não Realizada;Negativa;Positiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste Molecular (GeneXpert/NAAT)',
                    'opcoes' => 'Não Realizado;Detectado;Não Detectado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resistência à Rifampicina',
                    'opcoes' => 'Não Avaliada;Detectada;Não Detectada'
                ],

            ]

        ],



        // =====================================
        // IDENTIFICAÇÃO DO AGENTE
        // =====================================

        [
            'nome' => 'Identificação do Agente',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Micobactéria Identificada',
                    'opcoes' => 'Mycobacterium tuberculosis;Outra Micobactéria;Não Identificada'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Espécie Identificada',
                    'tamanho_maximo' => 300
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Compatível com Tuberculose',
                    'opcoes' => 'Sim;Não;Necessita Confirmação'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Paciente Potencialmente Infectante',
                    'opcoes' => 'Sim;Não;Avaliação Clínica Necessária'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$Toxoplasmose = [

    'codigo' => 'TOXO001',

    'nome' => 'Toxoplasmose (IgG/IgM)',

    'categoria' => 'Imunologia / Sorologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência;Imunoensaio;Teste Rápido'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // TOXOPLASMOSE IgG
        // =====================================

        [
            'nome' => 'Anticorpo IgG Anti-Toxoplasma',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Toxoplasma IgG',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado IgG',
                    'opcoes' => 'Negativo;Positivo;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação IgG',
                    'opcoes' => 'Sem Imunidade;Imunidade Prévia;Possível Infecção Antiga'
                ],

            ]

        ],



        // =====================================
        // TOXOPLASMOSE IgM
        // =====================================

        [
            'nome' => 'Anticorpo IgM Anti-Toxoplasma',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Toxoplasma IgM',
                    'unidade' => 'Índice',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado IgM',
                    'opcoes' => 'Negativo;Positivo;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação IgM',
                    'opcoes' => 'Ausente;Possível Infecção Recente;Necessita Confirmação'
                ],

            ]

        ],



        // =====================================
        // AVIDEZ IgG
        // =====================================

        [
            'nome' => 'Avidez do IgG',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Avidez IgG',
                    'unidade' => '%',
                    'valor_referencia' => 'Conforme laboratório',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação da Avidez',
                    'opcoes' => 'Baixa;Intermediária;Alta'
                ],

            ]

        ],



        // =====================================
        // SITUAÇÕES ESPECIAIS
        // =====================================

        [
            'nome' => 'Avaliação Clínica Complementar',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestante',
                    'opcoes' => 'Sim;Não;Não Informado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Infecção Primária',
                    'opcoes' => 'Sim;Não;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Acompanhamento',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO FINAL
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Perfil Sorológico',
                    'opcoes' => 'IgG Negativo / IgM Negativo;IgG Positivo / IgM Negativo;IgG Positivo / IgM Positivo;IgG Negativo / IgM Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Sem Evidência de Infecção;Infecção Antiga;Suspeita de Infecção Recente'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$RastreioHepatite = [

    'codigo' => 'HCV001',

    'nome' => 'Rastreio de Hepatite C (Anti-HCV)',

    'categoria' => 'Imunologia / Sorologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência;Imunoensaio;Teste Rápido'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // ANTICORPOS ANTI-HCV
        // =====================================

        [
            'nome' => 'Anticorpo Anti-HCV',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice Anti-HCV',
                    'unidade' => 'S/CO',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Anti-HCV',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação Inicial',
                    'opcoes' => 'Sem Evidência Sorológica;Suspeita de Exposição ao Vírus;Necessita Confirmação'
                ],

            ]

        ],



        // =====================================
        // CONFIRMAÇÃO MOLECULAR
        // =====================================

        [
            'nome' => 'Teste Confirmatório (HCV-RNA)',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'HCV-RNA Realizado',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado HCV-RNA',
                    'opcoes' => 'Detectado;Não Detectado;Não Realizado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Carga Viral HCV',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => 'Não Detectável',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // GENOTIPAGEM
        // =====================================

        [
            'nome' => 'Genotipagem do Vírus',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Genotipagem Realizada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Genótipo do HCV',
                    'tamanho_maximo' => 100
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO COMPLEMENTAR
        // =====================================

        [
            'nome' => 'Avaliação Clínica Complementar',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Infecção Ativa',
                    'opcoes' => 'Sim;Não;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Confirmação Molecular',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Acompanhamento Recomendado',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO FINAL
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Perfil Sorológico',
                    'opcoes' => 'Anti-HCV Não Reagente;Anti-HCV Reagente / HCV-RNA Não Detectado;Anti-HCV Reagente / HCV-RNA Detectado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Negativo;Positivo;Necessita Investigação Complementar'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$TSH = [

    'codigo' => 'TSH001',

    'nome' => 'TSH (Hormona Estimulante da Tiroide)',

    'categoria' => 'Endocrinologia / Hormonas',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência;Eletroquimioluminescência;ELISA;Imunoensaio'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // DOSAGEM DO TSH
        // =====================================

        [
            'nome' => 'Dosagem do TSH',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'TSH',
                    'unidade' => 'µUI/mL',
                    'valor_referencia' => '0.4 - 4.0',
                    'valor_minimo' => 0.4,
                    'valor_maximo' => 4.0
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO DA FUNÇÃO TIREOIDIANA
        // =====================================

        [
            'nome' => 'Avaliação da Função da Tiroide',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Tireoidiana',
                    'opcoes' => 'Normal;Hipotireoidismo Sugestivo;Hipertireoidismo Sugestivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração do TSH',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'TSH Elevado',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'TSH Reduzido',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // EXAMES COMPLEMENTARES
        // =====================================

        [
            'nome' => 'Exames Complementares da Tiroide',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'T4 Livre Realizado',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'T4 Livre',
                    'unidade' => 'ng/dL',
                    'valor_referencia' => '0.8 - 1.8',
                    'valor_minimo' => 0.8,
                    'valor_maximo' => 1.8
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos Anti-TPO Realizados',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Anti-TPO',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => '< 35',
                    'valor_minimo' => 0,
                    'valor_maximo' => 35
                ],

            ]

        ],



        // =====================================
        // SITUAÇÕES ESPECIAIS
        // =====================================

        [
            'nome' => 'Avaliação Clínica Complementar',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestação',
                    'opcoes' => 'Sim;Não;Não Informado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Medicação Tireoidiana',
                    'opcoes' => 'Sim;Não;Não Informado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Seguimento de Tratamento',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO FINAL
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Normal;Compatível com Hipotireoidismo;Compatível com Hipertireoidismo;Resultado Indeterminado'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Laboratorial',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$T4 = [

    'codigo' => 'T4L001',

    'nome' => 'T4 Livre (Tiroxina Livre)',

    'categoria' => 'Endocrinologia / Hormonas da Tiroide',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência;Eletroquimioluminescência;ELISA;Imunoensaio'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // DOSAGEM DO T4 LIVRE
        // =====================================

        [
            'nome' => 'Dosagem do T4 Livre',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'T4 Livre',
                    'unidade' => 'ng/dL',
                    'valor_referencia' => '0.8 - 1.8',
                    'valor_minimo' => 0.8,
                    'valor_maximo' => 1.8
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO TIREOIDIANA
        // =====================================

        [
            'nome' => 'Avaliação da Função Tireoidiana',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Função da Tiroide',
                    'opcoes' => 'Normal;Hipotireoidismo Sugestivo;Hipertireoidismo Sugestivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'T4 Livre Reduzido',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'T4 Livre Elevado',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // CORRELAÇÃO COM TSH
        // =====================================

        [
            'nome' => 'Correlação com TSH',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'TSH Disponível',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'TSH Associado',
                    'unidade' => 'µUI/mL',
                    'valor_referencia' => '0.4 - 4.0',
                    'valor_minimo' => 0.4,
                    'valor_maximo' => 4.0
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão Hormonal',
                    'opcoes' => 'Normal;Hipotireoidismo Primário;Hipertireoidismo Primário;Alteração Subclínica'
                ],

            ]

        ],



        // =====================================
        // EXAMES COMPLEMENTARES
        // =====================================

        [
            'nome' => 'Exames Complementares',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Anti-TPO Realizado',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Anti-TPO',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => '< 35',
                    'valor_minimo' => 0,
                    'valor_maximo' => 35
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'T3 Livre Realizado',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // SITUAÇÕES ESPECIAIS
        // =====================================

        [
            'nome' => 'Avaliação Clínica Complementar',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestação',
                    'opcoes' => 'Sim;Não;Não Informado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Hormona Tireoidiana',
                    'opcoes' => 'Sim;Não;Não Informado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Acompanhamento de Tratamento',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO FINAL
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Normal;Compatível com Hipotireoidismo;Compatível com Hipertireoidismo;Resultado Indeterminado'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Laboratorial',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$Ferritina = [

    'codigo' => 'FER001',

    'nome' => 'Ferritina',

    'categoria' => 'Bioquímica / Metabolismo do Ferro',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência;ELISA;Imunoensaio;Eletroquimioluminescência'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // DOSAGEM DA FERRITINA
        // =====================================

        [
            'nome' => 'Dosagem da Ferritina',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Ferritina Sérica',
                    'unidade' => 'ng/mL',
                    'valor_referencia' => 'Homens: 30 - 400 | Mulheres: 15 - 150',
                    'valor_minimo' => 15,
                    'valor_maximo' => 400
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Baixa;Normal;Elevada'
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO DO FERRO
        // =====================================

        [
            'nome' => 'Avaliação do Metabolismo do Ferro',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Ferro Sérico',
                    'unidade' => 'µg/dL',
                    'valor_referencia' => '60 - 170',
                    'valor_minimo' => 60,
                    'valor_maximo' => 170
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Transferrina',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '200 - 360',
                    'valor_minimo' => 200,
                    'valor_maximo' => 360
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Saturação da Transferrina',
                    'unidade' => '%',
                    'valor_referencia' => '20 - 50',
                    'valor_minimo' => 20,
                    'valor_maximo' => 50
                ],

            ]

        ],



        // =====================================
        // CORRELAÇÃO HEMATOLÓGICA
        // =====================================

        [
            'nome' => 'Correlação Hematológica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemograma Associado',
                    'opcoes' => 'Disponível;Não Disponível'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina',
                    'unidade' => 'g/dL',
                    'valor_referencia' => 'Homens: 13-17 | Mulheres: 12-15',
                    'valor_minimo' => 12,
                    'valor_maximo' => 17
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Deficiência de Ferro',
                    'opcoes' => 'Sim;Não;Necessita Avaliação'
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO INFLAMATÓRIA
        // =====================================

        [
            'nome' => 'Avaliação Inflamatória',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Ferritina Elevada por Inflamação',
                    'opcoes' => 'Sim;Não;Possível'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Marcador Inflamatório Associado',
                    'opcoes' => 'PCR Elevada;PCR Normal;Não Avaliado'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO FINAL
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Reservas de Ferro Adequadas;Deficiência de Ferro;Sobrecarga de Ferro;Alteração Inflamatória'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Laboratorial',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$FerroSérico = [

    'codigo' => 'FERRO001',

    'nome' => 'Ferro Sérico',

    'categoria' => 'Bioquímica / Metabolismo do Ferro',

    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Colorimétrico;Espectrofotométrico;Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // DOSAGEM DO FERRO
        // =====================================

        [
            'nome' => 'Dosagem do Ferro Sérico',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Ferro Sérico',
                    'unidade' => 'µg/dL',
                    'valor_referencia' => 'Homens: 65 - 175 | Mulheres: 50 - 170',
                    'valor_minimo' => 50,
                    'valor_maximo' => 175
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

            ]

        ],



        // =====================================
        // CAPACIDADE DE TRANSPORTE DO FERRO
        // =====================================

        [
            'nome' => 'Avaliação da Capacidade de Transporte',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Transferrina',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '200 - 360',
                    'valor_minimo' => 200,
                    'valor_maximo' => 360
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Capacidade Total de Ligação do Ferro (TIBC)',
                    'unidade' => 'µg/dL',
                    'valor_referencia' => '250 - 450',
                    'valor_minimo' => 250,
                    'valor_maximo' => 450
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Saturação da Transferrina',
                    'unidade' => '%',
                    'valor_referencia' => '20 - 50',
                    'valor_minimo' => 20,
                    'valor_maximo' => 50
                ],

            ]

        ],



        // =====================================
        // CORRELAÇÃO COM FERRITINA
        // =====================================

        [
            'nome' => 'Correlação com Reservas de Ferro',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Ferritina Disponível',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Ferritina',
                    'unidade' => 'ng/mL',
                    'valor_referencia' => 'Conforme sexo e idade',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação das Reservas de Ferro',
                    'opcoes' => 'Adequadas;Reduzidas;Aumentadas'
                ],

            ]

        ],



        // =====================================
        // CORRELAÇÃO HEMATOLÓGICA
        // =====================================

        [
            'nome' => 'Correlação Hematológica',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemograma Associado',
                    'opcoes' => 'Disponível;Não Disponível'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina',
                    'unidade' => 'g/dL',
                    'valor_referencia' => 'Conforme sexo e idade',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Anemia Ferropriva',
                    'opcoes' => 'Sim;Não;Necessita Avaliação'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO FINAL
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Normal;Deficiência de Ferro;Sobrecarga de Ferro;Resultado Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão Compatível com Deficiência de Ferro',
                    'opcoes' => 'Sim;Não;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão Compatível com Sobrecarga de Ferro',
                    'opcoes' => 'Sim;Não;Indeterminado'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];

$PCR_COVID19 = [

    'codigo' => 'COVIDPCR001',

    'nome' => 'PCR para COVID-19 (SARS-CoV-2)',

    'categoria' => 'Biologia Molecular / Virologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Swab Nasofaríngeo;Swab Orofaringeo;Aspirado Respiratório;Outro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'RT-PCR;PCR em Tempo Real;Teste Molecular Automatizado'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Local da Coleta',
                    'tamanho_maximo' => 200
                ],

            ]

        ],



        // =====================================
        // QUALIDADE DA AMOSTRA
        // =====================================

        [
            'nome' => 'Avaliação da Amostra',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Qualidade da Amostra',
                    'opcoes' => 'Adequada;Inadequada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno da Extração',
                    'opcoes' => 'Válido;Inválido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Material Genético Detectável',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // DETECÇÃO SARS-CoV-2
        // =====================================

        [
            'nome' => 'Detecção Molecular do SARS-CoV-2',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado PCR COVID-19',
                    'opcoes' => 'Detectado;Não Detectado;Inconclusivo'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Gene(s) Alvo Detectado(s)',
                    'tamanho_maximo' => 300
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Valor Ct (Cycle Threshold)',
                    'unidade' => 'Ct',
                    'valor_referencia' => 'Conforme kit utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 45
                ],

            ]

        ],



        // =====================================
        // GENES VIRAIS
        // =====================================

        [
            'nome' => 'Genes Pesquisados',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Gene N Detectado',
                    'opcoes' => 'Detectado;Não Detectado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Gene E Detectado',
                    'opcoes' => 'Detectado;Não Detectado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Gene ORF1ab/RdRp Detectado',
                    'opcoes' => 'Detectado;Não Detectado;Não Avaliado'
                ],

            ]

        ],



        // =====================================
        // INFORMAÇÕES CLÍNICAS
        // =====================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Sintomas',
                    'opcoes' => 'Sim;Não;Não Informado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Caso',
                    'opcoes' => 'Suspeito;Confirmado;Rastreio;Controle'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Dias de Sintomas',
                    'unidade' => 'dias',
                    'valor_referencia' => 'Informativo',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação Final',
                    'opcoes' => 'Positivo para SARS-CoV-2;Negativo para SARS-CoV-2;Resultado Inconclusivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Repetição do Teste',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações Laboratoriais',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];

$Espermograma = [

    'codigo' => 'ESP001',

    'nome' => 'Espermograma',

    'categoria' => 'Andrologia / Fertilidade',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Sêmen'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Dias de Abstinência Sexual',
                    'unidade' => 'dias',
                    'valor_referencia' => '2 - 7',
                    'valor_minimo' => 2,
                    'valor_maximo' => 7
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Local da Coleta',
                    'opcoes' => 'Laboratório;Domicílio'
                ],

            ]

        ],



        // =====================================
        // ANÁLISE MACROSCÓPICA
        // =====================================

        [
            'nome' => 'Avaliação Macroscópica do Sêmen',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Volume Seminal',
                    'unidade' => 'mL',
                    'valor_referencia' => '≥ 1.4',
                    'valor_minimo' => 1.4,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto',
                    'opcoes' => 'Normal;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cor',
                    'opcoes' => 'Branco Opalescente;Amarelado;Outro'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Viscosidade',
                    'unidade' => '',
                    'valor_referencia' => 'Normal',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo de Liquefação',
                    'unidade' => 'minutos',
                    'valor_referencia' => '< 60',
                    'valor_minimo' => 0,
                    'valor_maximo' => 60
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'pH Seminal',
                    'unidade' => '',
                    'valor_referencia' => '≥ 7.2',
                    'valor_minimo' => 7.2,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // CONCENTRAÇÃO ESPERMÁTICA
        // =====================================

        [
            'nome' => 'Concentração dos Espermatozoides',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Espermatozoides',
                    'unidade' => 'milhões/mL',
                    'valor_referencia' => '≥ 16',
                    'valor_minimo' => 16,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Número Total de Espermatozoides',
                    'unidade' => 'milhões',
                    'valor_referencia' => '≥ 39',
                    'valor_minimo' => 39,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // MOTILIDADE
        // =====================================

        [
            'nome' => 'Motilidade Espermática',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Motilidade Progressiva',
                    'unidade' => '%',
                    'valor_referencia' => '≥ 30',
                    'valor_minimo' => 30,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Motilidade Total',
                    'unidade' => '%',
                    'valor_referencia' => '≥ 42',
                    'valor_minimo' => 42,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Espermatozoides Imóveis',
                    'unidade' => '%',
                    'valor_referencia' => '≤ 58',
                    'valor_minimo' => 0,
                    'valor_maximo' => 58
                ],

            ]

        ],



        // =====================================
        // MORFOLOGIA
        // =====================================

        [
            'nome' => 'Morfologia dos Espermatozoides',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Formas Normais',
                    'unidade' => '%',
                    'valor_referencia' => '≥ 4',
                    'valor_minimo' => 4,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Formas Anormais',
                    'unidade' => '%',
                    'valor_referencia' => '≤ 96',
                    'valor_minimo' => 0,
                    'valor_maximo' => 96
                ],

            ]

        ],



        // =====================================
        // CÉLULAS E ELEMENTOS ASSOCIADOS
        // =====================================

        [
            'nome' => 'Células Associadas',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Leucócitos',
                    'unidade' => 'milhões/mL',
                    'valor_referencia' => '< 1',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Hemácias',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Aglutinação Espermática',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],



        // =====================================
        // TESTES COMPLEMENTARES
        // =====================================

        [
            'nome' => 'Testes Complementares',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste de Vitalidade',
                    'opcoes' => 'Não Realizado;Normal;Alterado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Vitalidade dos Espermatozoides',
                    'unidade' => '%',
                    'valor_referencia' => '≥ 54',
                    'valor_minimo' => 54,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cultura de Sêmen',
                    'opcoes' => 'Não Realizada;Negativa;Positiva'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO FINAL
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação Final',
                    'opcoes' => 'Normal;Oligozoospermia;Astenozoospermia;Teratozoospermia;Azoospermia;Alteração Mista'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Laboratorial',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$ExameSecreçãoVaginalCervical = [

    'codigo' => 'SEVC001',

    'nome' => 'Exame de Secreção Vaginal/Cervical',

    'categoria' => 'Microbiologia / Ginecologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Secreção Vaginal;Secreção Cervical;Endocervical'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método de Coleta',
                    'opcoes' => 'Swab Vaginal;Swab Cervical;Outro'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Local da Coleta',
                    'tamanho_maximo' => 200
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO MACROSCÓPICA
        // =====================================

        [
            'nome' => 'Avaliação Macroscópica da Secreção',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto da Secreção',
                    'opcoes' => 'Normal;Branca;Amarelada;Esverdeada;Purulenta;Sanguinolenta'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Quantidade de Secreção',
                    'opcoes' => 'Ausente;Pouca;Moderada;Abundante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Odor',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],



        // =====================================
        // MICROSCOPIA DIRETA
        // =====================================

        [
            'nome' => 'Microscopia Direta',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Epiteliais',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Leucócitos',
                    'unidade' => 'por campo',
                    'valor_referencia' => 'Baixo número',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemácias',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Guia (Clue Cells)',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],



        // =====================================
        // PESQUISA DE FUNGOS
        // =====================================

        [
            'nome' => 'Pesquisa de Fungos',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Candida spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Leveduras / Pseudohifas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],



        // =====================================
        // PESQUISA DE PARASITAS
        // =====================================

        [
            'nome' => 'Pesquisa de Parasitas',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Trichomonas vaginalis',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],



        // =====================================
        // BACTERIOLOGIA
        // =====================================

        [
            'nome' => 'Pesquisa Bacteriana',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Flora Bacteriana',
                    'opcoes' => 'Normal;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Gardnerella vaginalis',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Bactérias Patogênicas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Bactéria Identificada',
                    'tamanho_maximo' => 300
                ],

            ]

        ],



        // =====================================
        // CULTURA MICROBIOLÓGICA
        // =====================================

        [
            'nome' => 'Cultura da Secreção',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Cultura Realizada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Cultura',
                    'opcoes' => 'Negativa;Positiva;Contaminada'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Microrganismo Isolado',
                    'tamanho_maximo' => 300
                ],

            ]

        ],



        // =====================================
        // ISTs
        // =====================================

        [
            'nome' => 'Pesquisa de Infecções Sexualmente Transmissíveis',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Chlamydia trachomatis',
                    'opcoes' => 'Não Pesquisado;Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Neisseria gonorrhoeae',
                    'opcoes' => 'Não Pesquisado;Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'HPV',
                    'opcoes' => 'Não Pesquisado;Negativo;Positivo'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO FINAL
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Normal;Vaginose Bacteriana;Candidíase;Tricomoníase;Infecção Bacteriana;Outro'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Laboratorial',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$dadosExame = [

    'codigo' => 'PAP001',

    'nome' => 'Papanicolau (Citologia Cervical)',

    'categoria' => 'Citologia / Anatomopatologia / Ginecologia',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Citologia Cervical Convencional;Citologia em Meio Líquido'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Local da Coleta',
                    'opcoes' => 'Colo do Útero;Zona de Transformação;Endocérvice;Exocérvice'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Profissional Responsável pela Coleta',
                    'tamanho_maximo' => 150
                ],

            ]

        ],



        // =====================================
        // INFORMAÇÕES CLÍNICAS
        // =====================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Idade da Paciente',
                    'unidade' => 'anos',
                    'valor_referencia' => 'Informativo',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Motivo do Exame',
                    'opcoes' => 'Rastreio;Seguimento;Sintomas Clínicos;Controle Pós-Tratamento'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Dispositivo Intrauterino (DIU)',
                    'opcoes' => 'Sim;Não;Não Informado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestação',
                    'opcoes' => 'Sim;Não;Não Informado'
                ],

            ]

        ],



        // =====================================
        // ADEQUABILIDADE DA AMOSTRA
        // =====================================

        [
            'nome' => 'Avaliação da Amostra',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Adequabilidade do Material',
                    'opcoes' => 'Satisfatória;Insatisfatória'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Células da Zona de Transformação',
                    'opcoes' => 'Presente;Ausente;Não Avaliável'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Motivo da Insatisfatoriedade',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],



        // =====================================
        // FLORA E MICROORGANISMOS
        // =====================================

        [
            'nome' => 'Microbiologia na Citologia',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Flora Bacteriana',
                    'opcoes' => 'Normal;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Candida spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Trichomonas vaginalis',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Vaginose Bacteriana',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Efeito Citopático por HPV',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO CELULAR
        // =====================================

        [
            'nome' => 'Avaliação Citológica',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Escamosas',
                    'opcoes' => 'Normais;Alteradas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Glandulares',
                    'opcoes' => 'Normais;Alteradas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Inflamação',
                    'opcoes' => 'Ausente;Leve;Moderada;Intensa'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Atrofia Celular',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],



        // =====================================
        // CLASSIFICAÇÃO BETHESDA
        // =====================================

        [
            'nome' => 'Classificação Bethesda',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Citológico Geral',
                    'opcoes' => 'Negativo para Lesão Intraepitelial ou Malignidade;Alteração Celular;Lesão Intraepitelial;Suspeita de Malignidade'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'ASC-US',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'LSIL (Lesão de Baixo Grau)',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'HSIL (Lesão de Alto Grau)',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Carcinoma',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],



        // =====================================
        // HPV
        // =====================================

        [
            'nome' => 'Avaliação Relacionada ao HPV',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste HPV Realizado',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado HPV',
                    'opcoes' => 'Negativo;Positivo;Não Realizado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Tipo de HPV Identificado',
                    'tamanho_maximo' => 200
                ],

            ]

        ],



        // =====================================
        // CONCLUSÃO
        // =====================================

        [
            'nome' => 'Conclusão Laboratorial',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Diagnóstico Citológico Final',
                    'tamanho_maximo' => 3000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Recomendação de Seguimento',
                    'opcoes' => 'Rotina;Repetir Citologia;Colposcopia;Avaliação Especializada'
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Citopatologista Responsável',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];

$dadosExame = [

    'codigo' => 'OVU001',

    'nome' => 'Teste de Ovulação',

    'categoria' => 'Endocrinologia / Reprodução Humana',


    'parametros' => [


        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Urina;Sangue'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Teste Imunocromatográfico;ELISA;Imunoensaio'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Dia do Ciclo Menstrual',
                    'unidade' => 'dia',
                    'valor_referencia' => 'Informativo',
                    'valor_minimo' => 1,
                    'valor_maximo' => 40
                ],

            ]

        ],



        // =====================================
        // HORMONA LH
        // =====================================

        [
            'nome' => 'Detecção do Pico de LH',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado do Teste de LH',
                    'opcoes' => 'Positivo;Negativo;Inválido'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de LH',
                    'unidade' => 'mUI/mL',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Pico Ovulatório Detectado',
                    'opcoes' => 'Sim;Não'
                ],

            ]

        ],



        // =====================================
        // HORMONAS RELACIONADAS
        // =====================================

        [
            'nome' => 'Avaliação Hormonal Complementar',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Progesterona Avaliada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Progesterona',
                    'unidade' => 'ng/mL',
                    'valor_referencia' => 'Conforme fase do ciclo',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estradiol Avaliado',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Estradiol',
                    'unidade' => 'pg/mL',
                    'valor_referencia' => 'Conforme fase do ciclo',
                    'valor_minimo' => null,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // INFORMAÇÕES DO CICLO
        // =====================================

        [
            'nome' => 'Informações do Ciclo Menstrual',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Duração Média do Ciclo',
                    'unidade' => 'dias',
                    'valor_referencia' => '21 - 35',
                    'valor_minimo' => 21,
                    'valor_maximo' => 35
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ciclo Regular',
                    'opcoes' => 'Sim;Não;Não Informado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Medicamentos para Fertilidade',
                    'opcoes' => 'Sim;Não;Não Informado'
                ],

            ]

        ],



        // =====================================
        // AVALIAÇÃO DA FERTILIDADE
        // =====================================

        [
            'nome' => 'Avaliação de Fertilidade',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Ovulação Provável',
                    'opcoes' => 'Sim;Não;Indeterminada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Período Fértil Identificado',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data Provável da Ovulação'
                ],

            ]

        ],



        // =====================================
        // INTERPRETAÇÃO FINAL
        // =====================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Pico de LH Detectado;Sem Pico de LH Detectado;Teste Inválido'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Laboratorial',
                    'tamanho_maximo' => 2000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Responsável Técnico',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];


$dadosExame = [
    'codigo' => 'ECO001',
    'nome' => 'Ecocardiograma',
    'categoria' => 'Cardiologia / Imagem',
    'parametros' => [
        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

        [
            'nome' => 'Identificação do Exame',
            'ordem' => 1,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Ecocardiograma',
                    'opcoes' => 'Transtorácico;Transesofágico;Sob Estresse'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Modo M;Bidimensional;Doppler;Doppler Colorido'
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Exame'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Equipamento Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        // =====================================
        // CÂMARAS CARDÍACAS
        // =====================================

        [
            'nome' => 'Avaliação das Câmaras Cardíacas',
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
                    'nome' => 'Ventrículo Direito',
                    'unidade' => 'mm',
                    'valor_referencia' => '≤ 42',
                    'valor_minimo' => 0,
                    'valor_maximo' => 42
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Dilatação das Câmaras',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],



        // =====================================
        // FUNÇÃO DO VENTRÍCULO ESQUERDO
        // =====================================

        [
            'nome' => 'Função Ventricular Esquerda',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Fração de Ejeção (FE)',
                    'unidade' => '%',
                    'valor_referencia' => '≥ 55',
                    'valor_minimo' => 55,
                    'valor_maximo' => 70
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Sistólica',
                    'opcoes' => 'Normal;Reduzida;Aumentada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Diastólica',
                    'opcoes' => 'Normal;Alterada'
                ],

            ]

        ],



        // =====================================
        // FUNÇÃO DO VENTRÍCULO DIREITO
        // =====================================

        [
            'nome' => 'Função Ventricular Direita',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'TAPSE',
                    'unidade' => 'mm',
                    'valor_referencia' => '≥ 17',
                    'valor_minimo' => 17,
                    'valor_maximo' => null
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função do Ventrículo Direito',
                    'opcoes' => 'Normal;Reduzida'
                ],

            ]

        ],



        // =====================================
        // VÁLVULAS CARDÍACAS
        // =====================================

        [
            'nome' => 'Avaliação das Válvulas Cardíacas',
            'ordem' => 5,

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
                    'opcoes' => 'Normal;Insuficiência;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Válvula Pulmonar',
                    'opcoes' => 'Normal;Alterada'
                ],

            ]

        ],



        // =====================================
        // DOPPLER CARDÍACO
        // =====================================

        [
            'nome' => 'Avaliação Doppler',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Pressão Sistólica da Artéria Pulmonar',
                    'unidade' => 'mmHg',
                    'valor_referencia' => '< 35',
                    'valor_minimo' => 0,
                    'valor_maximo' => 35
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fluxos Cardíacos',
                    'opcoes' => 'Normais;Alterados'
                ],

            ]

        ],



        // =====================================
        // PERICÁRDIO
        // =====================================

        [
            'nome' => 'Avaliação do Pericárdio',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Derrame Pericárdico',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Quantidade do Derrame',
                    'unidade' => 'mm',
                    'valor_referencia' => 'Ausente',
                    'valor_minimo' => 0,
                    'valor_maximo' => null
                ],

            ]

        ],



        // =====================================
        // MASSAS E ALTERAÇÕES
        // =====================================

        [
            'nome' => 'Alterações Estruturais',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Massa ou Trombo',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração da Parede Cardíaca',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],



        // =====================================
        // CONCLUSÃO
        // =====================================

        [
            'nome' => 'Conclusão do Ecocardiograma',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Normal;Alteração Leve;Alteração Moderada;Alteração Grave'
                ],

                [
                    'tipo' => 'textarea',
                    'nome' => 'Conclusão Médica',
                    'tamanho_maximo' => 3000
                ],

            ]

        ],



        // =====================================
        // CONTROLE DO EXAME
        // =====================================

        [
            'nome' => 'Controle de Qualidade',
            'ordem' => 10,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Interno',
                    'opcoes' => 'Aprovado;Reprovado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Médico Responsável',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'data',
                    'nome' => 'Data do Resultado'
                ],

            ]

        ]

    ]

];

<?php


$dadosExame = [
    'codigo' => 'PAR001',
    'nome' => 'Pesquisa de Filária (Microfilárias)',
    'categoria' => 'Laboratório / Parasitologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Sangue Periférico;Sangue Venoso'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Horário da Coleta',
                    'opcoes' => 'Diurno;Noturno'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // PESQUISA DE MICROFILÁRIAS
        //==========================================

        [
            'nome' => 'Pesquisa de Microfilárias',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Pesquisa',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Visualização de Microfilárias',
                    'opcoes' => 'Não Visualizadas;Visualizadas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Quantidade de Microfilárias',
                    'opcoes' => 'Ausentes;Raras;Poucas;Moderadas;Numerosas'
                ],

            ]

        ],


        //==========================================
        // IDENTIFICAÇÃO DO PARASITA
        //==========================================

        [
            'nome' => 'Identificação do Parasita',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Espécie Identificada',
                    'opcoes' => 'Wuchereria bancrofti;Brugia malayi;Brugia timori;Mansonella spp.;Loa loa;Não Identificada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Morfologia Compatível',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Parasitemia',
                    'opcoes' => 'Baixa;Moderada;Alta'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO MICROSCÓPICA
        //==========================================

        [
            'nome' => 'Avaliação Microscópica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Qualidade da Amostra',
                    'opcoes' => 'Adequada;Inadequada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Outros Hemoparasitas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Compatível com Filariose',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Exames Complementares',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Recomendação de Acompanhamento',
                    'opcoes' => 'Não Necessário;Recomendado'
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
    'codigo' => 'PAR002',
    'nome' => 'Pesquisa de Schistosoma mansoni na Urina',
    'categoria' => 'Laboratório / Parasitologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Urina'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Horário da Coleta',
                    'tamanho_maximo' => 50
                ],

            ]

        ],

        //==========================================
        // PESQUISA DE OVOS
        //==========================================

        [
            'nome' => 'Pesquisa de Schistosoma mansoni',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Pesquisa',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ovos de Schistosoma mansoni',
                    'opcoes' => 'Não Visualizados;Visualizados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Quantidade de Ovos',
                    'opcoes' => 'Ausentes;Raros;Poucos;Moderados;Numerosos'
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO MICROSCÓPICA
        //==========================================

        [
            'nome' => 'Avaliação Microscópica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Morfologia Compatível',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Espinho Lateral Característico',
                    'opcoes' => 'Presente;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Integridade dos Ovos',
                    'opcoes' => 'Íntegros;Degenerados;Mistos'
                ],

            ]

        ],

        //==========================================
        // QUALIDADE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Qualidade da Amostra',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Outros Parasitas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // INTERPRETAÇÃO
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Compatível com Esquistossomose',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Exames Complementares',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Recomendação de Acompanhamento',
                    'opcoes' => 'Não Necessário;Recomendado'
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
    'codigo' => 'PAR003',
    'nome' => 'Pesquisa de Schistosoma mansoni nas Fezes',
    'categoria' => 'Laboratório / Parasitologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Fezes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Kato-Katz;Hoffman, Pons e Janer (HPJ);Ritchie;Sedimentação Espontânea;Outro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Consistência das Fezes',
                    'opcoes' => 'Formadas;Pastosas;Líquidas'
                ],

            ]

        ],

        //==========================================
        // PESQUISA PARASITOLÓGICA
        //==========================================

        [
            'nome' => 'Pesquisa de Schistosoma mansoni',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Pesquisa',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ovos de Schistosoma mansoni',
                    'opcoes' => 'Não Visualizados;Visualizados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Morfologia Compatível',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Espinho Lateral Característico',
                    'opcoes' => 'Presente;Ausente'
                ],

            ]

        ],

        //==========================================
        // QUANTIFICAÇÃO
        //==========================================

        [
            'nome' => 'Quantificação Parasitária',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Ovos por Grama de Fezes (OPG)',
                    'unidade' => 'OPG',
                    'valor_referencia' => '0',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Carga Parasitária',
                    'opcoes' => 'Ausente;Leve;Moderada;Intensa'
                ],

            ]

        ],

        //==========================================
        // QUALIDADE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Qualidade da Amostra',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Outros Parasitas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // INTERPRETAÇÃO
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Compatível com Esquistossomose',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Exames Complementares',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Recomendação de Acompanhamento',
                    'opcoes' => 'Não Necessário;Recomendado'
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
    'codigo' => 'PAR004',
    'nome' => 'Pesquisa de Ovos de Parasitas',
    'categoria' => 'Laboratório / Parasitologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Fezes;Urina;Outro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Exame Direto;Hoffman, Pons e Janer (HPJ);Kato-Katz;Ritchie;Faust;Sedimentação Espontânea;Outro'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Número de Amostras',
                    'unidade' => 'amostras',
                    'valor_referencia' => '1 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 10
                ],

            ]

        ],

        //==========================================
        // PESQUISA DE OVOS
        //==========================================

        [
            'nome' => 'Pesquisa Microscópica',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Pesquisa',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ovos de Parasitas',
                    'opcoes' => 'Não Visualizados;Visualizados'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Quantidade de Ovos',
                    'opcoes' => 'Ausentes;Raros;Poucos;Moderados;Numerosos'
                ],

            ]

        ],

        //==========================================
        // IDENTIFICAÇÃO DOS PARASITAS
        //==========================================

        [
            'nome' => 'Identificação dos Parasitas',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Ascaris lumbricoides',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Trichuris trichiura',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ancylostoma spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Schistosoma mansoni',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Taenia spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hymenolepis nana',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Enterobius vermicularis',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Outro Parasita',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Especificar Outro Parasita',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // QUALIDADE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Qualidade da Amostra',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Artefatos que Dificultam a Análise',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // INTERPRETAÇÃO
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Infecção Parasitária',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Poliparasitismo',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Exames Complementares',
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
    'codigo' => 'PAR005',
    'nome' => 'Exame Parasitológico de Fezes (EPF)',
    'categoria' => 'Laboratório / Parasitologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Fezes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Exame Direto;Hoffman, Pons e Janer (HPJ);Ritchie;Faust;Kato-Katz;Baermann;Willis;Sedimentação Espontânea;Outro'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Número de Amostras',
                    'unidade' => 'amostras',
                    'valor_referencia' => '1 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 10
                ],

            ]

        ],

        //==========================================
        // CARACTERÍSTICAS DA AMOSTRA
        //==========================================

        [
            'nome' => 'Características Macroscópicas',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Consistência das Fezes',
                    'opcoes' => 'Formadas;Pastosas;Líquidas'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cor',
                    'opcoes' => 'Castanha;Amarelada;Esverdeada;Enegrecida;Avermelhada;Outra'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Muco',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sangue Visível',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // PESQUISA PARASITOLÓGICA
        //==========================================

        [
            'nome' => 'Pesquisa de Parasitas',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ovos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Larvas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cistos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Trofozoítos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // IDENTIFICAÇÃO DOS PARASITAS
        //==========================================

        [
            'nome' => 'Parasitas Identificados',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Ascaris lumbricoides',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Trichuris trichiura',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ancylostoma spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Strongyloides stercoralis',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Schistosoma mansoni',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Taenia spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hymenolepis nana',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Enterobius vermicularis',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Giardia lamblia',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Entamoeba histolytica/dispar',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Entamoeba coli',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Endolimax nana',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Iodamoeba bütschlii',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Blastocystis spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Outro Parasita',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Especificar Outro Parasita',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // QUALIDADE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Qualidade da Amostra',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Artefatos que Interferem na Análise',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // INTERPRETAÇÃO
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Infecção Parasitária',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Poliparasitismo',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Exames Complementares',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'COP001',
    'nome' => 'Pesquisa de Leucócitos nas Fezes',
    'categoria' => 'Laboratório / Coprologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Fezes'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Consistência das Fezes',
                    'opcoes' => 'Formadas;Pastosas;Líquidas'
                ],

            ]

        ],

        //==========================================
        // PESQUISA DE LEUCÓCITOS
        //==========================================

        [
            'nome' => 'Pesquisa de Leucócitos',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Pesquisa',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Leucócitos',
                    'opcoes' => 'Ausentes;Raros;Poucos;Moderados;Numerosos'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Predomínio de Neutrófilos',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],

        //==========================================
        // AVALIAÇÃO MICROSCÓPICA
        //==========================================

        [
            'nome' => 'Avaliação Microscópica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemácias',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Muco',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Células Epiteliais',
                    'opcoes' => 'Ausentes;Raras;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Leveduras',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // INTERPRETAÇÃO LABORATORIAL
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Processo Inflamatório Intestinal',
                    'opcoes' => 'Não Evidenciado;Sugestivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Infecção Bacteriana Invasiva',
                    'opcoes' => 'Não Sugestiva;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Cultura de Fezes',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],

        //==========================================
        // QUALIDADE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Qualidade da Amostra',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
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
    'codigo' => 'PAR006',
    'nome' => 'Pesquisa de Parasitas nas Fezes',
    'categoria' => 'Laboratório / Parasitologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Fezes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Exame Direto;Hoffman, Pons e Janer (HPJ);Ritchie;Faust;Kato-Katz;Baermann;Willis;Sedimentação Espontânea;Outro'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Número de Amostras',
                    'unidade' => 'amostras',
                    'valor_referencia' => '1 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 10
                ],

            ]

        ],

        //==========================================
        // PESQUISA DE PARASITAS
        //==========================================

        [
            'nome' => 'Pesquisa Microscópica',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ovos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Larvas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cistos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Trofozoítos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // HELMINTOS
        //==========================================

        [
            'nome' => 'Helmintos Identificados',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Ascaris lumbricoides',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Trichuris trichiura',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ancylostoma spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Strongyloides stercoralis',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Schistosoma mansoni',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Taenia spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hymenolepis nana',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Enterobius vermicularis',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],

        //==========================================
        // PROTOZOÁRIOS
        //==========================================

        [
            'nome' => 'Protozoários Identificados',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Giardia lamblia',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Entamoeba histolytica/dispar',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Entamoeba coli',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Endolimax nana',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Iodamoeba bütschlii',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Blastocystis spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Balantidium coli',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cryptosporidium spp.',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cyclospora cayetanensis',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cystoisospora belli',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Outro Parasita',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Especificar Outro Parasita',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // QUALIDADE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Qualidade da Amostra',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Artefatos que Interferem na Análise',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],

        //==========================================
        // INTERPRETAÇÃO
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Infecção Parasitária',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Poliparasitismo',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Exames Complementares',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'COP002',
    'nome' => 'Pesquisa de Sangue Oculto nas Fezes (PSOF)',
    'categoria' => 'Laboratório / Coprologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Fezes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Imunocromatográfico (FIT);Guáiaco (gFOBT);Outro'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Número de Amostras',
                    'unidade' => 'amostras',
                    'valor_referencia' => '1 - 3',
                    'valor_minimo' => 1,
                    'valor_maximo' => 10
                ],

            ]

        ],

        //==========================================
        // RESULTADO DO EXAME
        //==========================================

        [
            'nome' => 'Resultado da Pesquisa',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemoglobina Fecal Detectada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Hemoglobina Fecal',
                    'unidade' => 'µg Hb/g de fezes',
                    'valor_referencia' => '< 20',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

            ]

        ],

        //==========================================
        // QUALIDADE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Qualidade da Amostra',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Volume da Amostra',
                    'opcoes' => 'Adequado;Insuficiente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],

        //==========================================
        // INTERPRETAÇÃO
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sangramento Gastrointestinal Oculto',
                    'opcoes' => 'Não Evidenciado;Sugestivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Investigação Complementar',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Indicação para Colonoscopia',
                    'opcoes' => 'Não Indicada;Indicada;Avaliação Clínica Necessária'
                ],

            ]

        ],

        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 5,

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
    'codigo' => 'SOR001',
    'nome' => 'Ac. Anti-HCV',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;Outro'
                ],

            ]

        ],

        //==========================================
        // RESULTADO SOROLÓGICO
        //==========================================

        [
            'nome' => 'Resultado Sorológico',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice (S/CO ou COI)',
                    'unidade' => 'Índice',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],

        //==========================================
        // INTERPRETAÇÃO
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Evidência de Contato com o Vírus da Hepatite C',
                    'opcoes' => 'Não Evidenciada;Evidenciada;Inconclusiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Confirmação por HCV-RNA',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Infecção Ativa',
                    'opcoes' => 'Não;Sim;Necessita Confirmação'
                ],

            ]

        ],

        //==========================================
        // CONTROLE DE QUALIDADE
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],

        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 5,

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
    'codigo' => 'SOR002',
    'nome' => 'HIV 1 e 2',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma;Sangue Total'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA de 4ª Geração;Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Teste Rápido Imunocromatográfico;Outro'
                ],

            ]

        ],


        //==========================================
        // TRIAGEM SOROLÓGICA HIV
        //==========================================

        [
            'nome' => 'Resultado da Triagem Sorológica',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado HIV 1 e 2',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Detecção de Antígeno p24',
                    'opcoes' => 'Não Detectado;Detectado;Não Realizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos Anti-HIV 1',
                    'opcoes' => 'Não Reagente;Reagente;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos Anti-HIV 2',
                    'opcoes' => 'Não Reagente;Reagente;Não Avaliado'
                ],

            ]

        ],


        //==========================================
        // VALORES ANALÍTICOS
        //==========================================

        [
            'nome' => 'Dados Analíticos',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Reatividade (S/CO ou COI)',
                    'unidade' => 'Índice',
                    'valor_referencia' => 'Não Reagente conforme ponto de corte do método',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // TESTES COMPLEMENTARES
        //==========================================

        [
            'nome' => 'Confirmação Diagnóstica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Teste Confirmatório',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste Confirmatório Realizado',
                    'opcoes' => 'Não Realizado;Imunoblot;Teste Molecular (HIV-RNA);Outro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Confirmatório',
                    'opcoes' => 'Não Aplicável;Negativo;Positivo;Indeterminado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO LABORATORIAL
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação Final',
                    'opcoes' => 'Amostra Não Reagente para HIV 1 e 2;Amostra Reagente para HIV 1 e 2;Resultado Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Infecção pelo HIV',
                    'opcoes' => 'Não;Sim;Necessita Confirmação'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Orientação para Repetição do Exame',
                    'opcoes' => 'Não Necessária;Recomendada'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'SOR003',
    'nome' => 'Ac. HBc - Anticorpos contra o Antígeno do Core da Hepatite B (Anti-HBc)',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio Competitivo;Outro'
                ],

            ]

        ],


        //==========================================
        // RESULTADO ANTI-HBc TOTAL
        //==========================================

        [
            'nome' => 'Anti-HBc Total',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Anti-HBc Total',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Reatividade (S/CO ou COI)',
                    'unidade' => 'Índice',
                    'valor_referencia' => 'Conforme ponto de corte do método',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // ANTI-HBc IgM
        //==========================================

        [
            'nome' => 'Anti-HBc IgM',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Anti-HBc IgM',
                    'opcoes' => 'Não Reagente;Reagente;Não Realizado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice Anti-HBc IgM',
                    'unidade' => 'Índice',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DA INFECÇÃO
        //==========================================

        [
            'nome' => 'Interpretação Sorológica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Contato Prévio com Vírus da Hepatite B',
                    'opcoes' => 'Não Evidenciado;Evidenciado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Infecção Aguda',
                    'opcoes' => 'Não Sugestiva;Sugestiva;Necessita Avaliação Complementar'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Infecção Passada Resolvida',
                    'opcoes' => 'Não Sugestiva;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Infecção Oculta pelo HBV',
                    'opcoes' => 'Não Sugestiva;Sugestiva;Necessita Investigação'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO COM OUTROS MARCADORES
        //==========================================

        [
            'nome' => 'Correlação com Marcadores da Hepatite B',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar HBsAg',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar Anti-HBs',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar HBV-DNA',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'SOR004',
    'nome' => 'Ac. HBs - Anticorpos contra o Antígeno de Superfície da Hepatite B (Anti-HBs)',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;Outro'
                ],

            ]

        ],


        //==========================================
        // RESULTADO ANTI-HBs
        //==========================================

        [
            'nome' => 'Resultado Anti-HBs',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Não Reagente;Reagente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Anti-HBs',
                    'unidade' => 'mUI/mL',
                    'valor_referencia' => 'Proteção geralmente ≥ 10 mUI/mL',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DA IMUNIDADE
        //==========================================

        [
            'nome' => 'Interpretação Sorológica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Imunidade contra Hepatite B',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Proteção',
                    'opcoes' => 'Sem Proteção;Proteção Adequada;Proteção Elevada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Origem Provável dos Anticorpos',
                    'opcoes' => 'Vacinação;Infecção Prévia Resolvida;Indeterminada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE VACINAÇÃO
        //==========================================

        [
            'nome' => 'Avaliação Vacinal',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico de Vacinação contra Hepatite B',
                    'opcoes' => 'Não Informado;Completo;Incompleto;Não Vacinado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resposta Vacinal Adequada',
                    'opcoes' => 'Não Avaliada;Adequada;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Reforço Vacinal',
                    'opcoes' => 'Não;Sim;Avaliação Clínica Necessária'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO COM OUTROS MARCADORES
        //==========================================

        [
            'nome' => 'Correlação com Marcadores da Hepatite B',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar HBsAg',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar Anti-HBc',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar HBV-DNA',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'SOR004',
    'nome' => 'Ac. HBs - Anticorpos contra o Antígeno de Superfície da Hepatite B (Anti-HBs)',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;Outro'
                ],

            ]

        ],


        //==========================================
        // RESULTADO ANTI-HBs
        //==========================================

        [
            'nome' => 'Resultado Anti-HBs',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Não Reagente;Reagente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Anti-HBs',
                    'unidade' => 'mUI/mL',
                    'valor_referencia' => 'Proteção geralmente ≥ 10 mUI/mL',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DA IMUNIDADE
        //==========================================

        [
            'nome' => 'Interpretação Sorológica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Imunidade contra Hepatite B',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Proteção',
                    'opcoes' => 'Sem Proteção;Proteção Adequada;Proteção Elevada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Origem Provável dos Anticorpos',
                    'opcoes' => 'Vacinação;Infecção Prévia Resolvida;Indeterminada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE VACINAÇÃO
        //==========================================

        [
            'nome' => 'Avaliação Vacinal',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico de Vacinação contra Hepatite B',
                    'opcoes' => 'Não Informado;Completo;Incompleto;Não Vacinado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resposta Vacinal Adequada',
                    'opcoes' => 'Não Avaliada;Adequada;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Reforço Vacinal',
                    'opcoes' => 'Não;Sim;Avaliação Clínica Necessária'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO COM OUTROS MARCADORES
        //==========================================

        [
            'nome' => 'Correlação com Marcadores da Hepatite B',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar HBsAg',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar Anti-HBc',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar HBV-DNA',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'SOR005',
    'nome' => 'Ac. HCV (Hepatite C) - Anticorpos Anti-Hepatite C',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;Teste Rápido;Outro'
                ],

            ]

        ],


        //==========================================
        // RESULTADO ANTI-HCV
        //==========================================

        [
            'nome' => 'Resultado Anti-HCV',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Reatividade (S/CO ou COI)',
                    'unidade' => 'Índice',
                    'valor_referencia' => 'Conforme ponto de corte do método',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO SOROLÓGICA
        //==========================================

        [
            'nome' => 'Interpretação Sorológica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Contato Prévio com Vírus da Hepatite C',
                    'opcoes' => 'Não Evidenciado;Evidenciado;Inconclusivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Infecção pelo HCV',
                    'opcoes' => 'Não Sugestiva;Sugestiva;Necessita Confirmação'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Pesquisa HCV-RNA',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONFIRMAÇÃO MOLECULAR
        //==========================================

        [
            'nome' => 'Confirmação Diagnóstica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'HCV-RNA Realizado',
                    'opcoes' => 'Não Realizado;Realizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado HCV-RNA',
                    'opcoes' => 'Não Aplicável;Não Detectado;Detectado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Infecção Ativa pelo HCV',
                    'opcoes' => 'Não Evidenciada;Evidenciada;Necessita Avaliação'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Avaliação Complementar',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Função Hepática Recomendada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Acompanhamento Médico',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Repetição do Exame',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'SOR006',
    'nome' => 'Ag. HBs - Antígeno de Superfície da Hepatite B (HBsAg)',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;Teste Rápido;Outro'
                ],

            ]

        ],


        //==========================================
        // RESULTADO HBsAg
        //==========================================

        [
            'nome' => 'Resultado do HBsAg',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Reatividade (S/CO ou COI)',
                    'unidade' => 'Índice',
                    'valor_referencia' => 'Conforme ponto de corte do método',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // CONFIRMAÇÃO DO RESULTADO
        //==========================================

        [
            'nome' => 'Confirmação do Resultado',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste Confirmatório Realizado',
                    'opcoes' => 'Não Realizado;Realizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Confirmatório',
                    'opcoes' => 'Não Aplicável;Negativo;Positivo;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'HBsAg Persistente por Mais de 6 Meses',
                    'opcoes' => 'Não Avaliado;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DA INFECÇÃO
        //==========================================

        [
            'nome' => 'Interpretação Sorológica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença do Antígeno de Superfície do HBV',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Infecção Atual pelo HBV',
                    'opcoes' => 'Não;Sim;Necessita Avaliação Complementar'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Fase da Infecção',
                    'opcoes' => 'Não Aplicável;Aguda;Crônica;Indeterminada'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO COM MARCADORES HBV
        //==========================================

        [
            'nome' => 'Correlação com Outros Marcadores da Hepatite B',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar Anti-HBc',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar Anti-HBs',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar HBeAg',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliar HBV-DNA',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'IMU001',
    'nome' => 'IgE Total - Imunoglobulina E Total',
    'categoria' => 'Laboratório / Imunologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);ELISA;Imunoensaio Nefelométrico;Outro'
                ],

            ]

        ],


        //==========================================
        // RESULTADO ANALÍTICO
        //==========================================

        [
            'nome' => 'Dosagem de IgE Total',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'IgE Total',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => 'Conforme idade e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 200
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Normal;Elevado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO IMUNOLÓGICA
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de IgE Total',
                    'opcoes' => 'Dentro da Referência;Acima da Referência'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Atopia',
                    'opcoes' => 'Não Sugestivo;Sugestivo;Necessita Avaliação Clínica'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Associação com Alergia',
                    'opcoes' => 'Não Evidenciada;Possível;Provável'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Correlação Clínica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação para Doença Alérgica',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação para Parasitose',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Pesquisa de IgE Específica',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
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
    'codigo' => 'PAR007',
    'nome' => 'Ag. Malária - Pesquisa de Antígeno da Malária',
    'categoria' => 'Laboratório / Parasitologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Sangue Total'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Teste Rápido Imunocromatográfico;Imunoensaio;Outro'
                ],

            ]

        ],


        //==========================================
        // RESULTADO DA PESQUISA DE ANTÍGENO
        //==========================================

        [
            'nome' => 'Pesquisa de Antígeno da Malária',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral',
                    'opcoes' => 'Negativo;Positivo;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Antígeno HRP-2 (Plasmodium falciparum)',
                    'opcoes' => 'Não Detectado;Detectado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Antígeno pLDH (Plasmodium)',
                    'opcoes' => 'Não Detectado;Detectado;Não Avaliado'
                ],

            ]

        ],


        //==========================================
        // IDENTIFICAÇÃO DO PLASMODIUM
        //==========================================

        [
            'nome' => 'Identificação do Parasita',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Espécie de Plasmodium Identificada',
                    'opcoes' => 'Não Identificado;Plasmodium falciparum;Plasmodium vivax;Plasmodium malariae;Plasmodium ovale;Infecção Mista'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Infecção',
                    'opcoes' => 'Ausente;Única;Mista'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Observação sobre Espécie Identificada',
                    'tamanho_maximo' => 250
                ],

            ]

        ],


        //==========================================
        // DADOS COMPLEMENTARES
        //==========================================

        [
            'nome' => 'Avaliação Complementar',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Pesquisa por Gota Espessa Recomendada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Esfregaço Sanguíneo Realizado',
                    'opcoes' => 'Não Realizado;Realizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Parasitemia Avaliada',
                    'opcoes' => 'Não Avaliada;Avaliada'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Percentual de Parasitemia',
                    'unidade' => '%',
                    'valor_referencia' => 'Ausência de parasitas detectáveis',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO LABORATORIAL
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Infecção por Plasmodium',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Compatível com Malária',
                    'opcoes' => 'Não Sugestivo;Sugestivo;Confirmado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Confirmação Microscópica',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Volume da Amostra',
                    'opcoes' => 'Adequado;Insuficiente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'BAC001',
    'nome' => 'M. Tuberculose - Pesquisa para Mycobacterium tuberculosis',
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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Escarro;Lavado Broncoalveolar;Secreção Traqueal;Líquido Pleural;Líquor;Urina;Fragmento de Tecido;Outro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Baciloscopia (BAAR);Cultura para Micobactérias;Teste Molecular (PCR/NAAT);Teste Rápido Molecular;Outro'
                ],

            ]

        ],


        //==========================================
        // PESQUISA DIRETA DE BAAR
        //==========================================

        [
            'nome' => 'Baciloscopia para BAAR',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Pesquisa de Bacilos Álcool-Ácido Resistentes',
                    'opcoes' => 'Negativa;Positiva;Não Realizada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Quantidade de BAAR Encontrada',
                    'opcoes' => 'Ausente;Raros;1+;2+;3+'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Coloração Utilizada',
                    'opcoes' => 'Ziehl-Neelsen;Auramina-Rodamina;Não Informada'
                ],

            ]

        ],


        //==========================================
        // TESTE MOLECULAR
        //==========================================

        [
            'nome' => 'Teste Molecular para Mycobacterium tuberculosis',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Detecção do Complexo Mycobacterium tuberculosis',
                    'opcoes' => 'Não Detectado;Detectado;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resistência à Rifampicina',
                    'opcoes' => 'Não Detectada;Detectada;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste Molecular Realizado',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CULTURA PARA MICOBACTÉRIAS
        //==========================================

        [
            'nome' => 'Cultura para Mycobacterium',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Cultura',
                    'opcoes' => 'Negativa;Positiva;Em Andamento;Não Realizada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Identificação da Espécie',
                    'opcoes' => 'Mycobacterium tuberculosis;Outra Micobactéria;Não Identificada;Não Aplicável'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo de Crescimento da Cultura',
                    'unidade' => 'dias',
                    'valor_referencia' => 'Sem crescimento significativo',
                    'valor_minimo' => 0,
                    'valor_maximo' => 120
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DA AMOSTRA
        //==========================================

        [
            'nome' => 'Avaliação da Amostra',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Aspecto da Amostra',
                    'opcoes' => 'Adequado;Inadequado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Qualidade da Amostra',
                    'opcoes' => 'Satisfatória;Insatisfatória'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Contaminação da Amostra',
                    'opcoes' => 'Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO LABORATORIAL
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Evidência de Mycobacterium tuberculosis',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Compatível com Tuberculose',
                    'opcoes' => 'Não Sugestivo;Sugestivo;Confirmado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliação Complementar',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'SOR007',
    'nome' => 'Ag. P24 - Antígeno p24 do HIV',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma;Sangue Total'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Teste Rápido;Outro'
                ],

            ]

        ],


        //==========================================
        // PESQUISA DO ANTÍGENO p24
        //==========================================

        [
            'nome' => 'Pesquisa do Antígeno p24',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado do Antígeno p24',
                    'opcoes' => 'Não Detectado;Detectado;Indeterminado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Reatividade (S/CO ou COI)',
                    'unidade' => 'Índice',
                    'valor_referencia' => 'Conforme ponto de corte do método',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // DETECÇÃO COMBINADA HIV
        //==========================================

        [
            'nome' => 'Avaliação Combinada HIV',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos Anti-HIV 1',
                    'opcoes' => 'Não Reagente;Reagente;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos Anti-HIV 2',
                    'opcoes' => 'Não Reagente;Reagente;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste Combinado Ag p24 + Anticorpos HIV',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO LABORATORIAL
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença do Antígeno p24',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Infecção Recente pelo HIV',
                    'opcoes' => 'Não Sugestivo;Sugestivo;Necessita Confirmação'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Teste Confirmatório',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONFIRMAÇÃO DIAGNÓSTICA
        //==========================================

        [
            'nome' => 'Confirmação Diagnóstica',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste Confirmatório Realizado',
                    'opcoes' => 'Não Realizado;Realizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'HIV-RNA Realizado',
                    'opcoes' => 'Não Realizado;Realizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado HIV-RNA',
                    'opcoes' => 'Não Aplicável;Não Detectado;Detectado'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'MOLE001',
    'nome' => 'Carga Viral da Hepatite B (HBV-DNA)',
    'categoria' => 'Laboratório / Biologia Molecular',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Plasma;Soro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'PCR em Tempo Real (qPCR);PCR Quantitativo;Outro'
                ],

            ]

        ],


        //==========================================
        // QUANTIFICAÇÃO DO HBV-DNA
        //==========================================

        [
            'nome' => 'Quantificação da Carga Viral',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Detecção do HBV-DNA',
                    'opcoes' => 'Não Detectado;Detectado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Carga Viral do HBV',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => 'Não Detectável ou conforme acompanhamento clínico',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000000000
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Logaritmo da Carga Viral',
                    'unidade' => 'log10 UI/mL',
                    'valor_referencia' => 'Conforme resultado quantitativo',
                    'valor_minimo' => 0,
                    'valor_maximo' => 12
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Limite de Detecção do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // RESULTADO MOLECULAR
        //==========================================

        [
            'nome' => 'Resultado Molecular',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Final',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Replicação Viral do HBV',
                    'opcoes' => 'Não Detectada;Baixa;Moderada;Elevada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Atividade Viral Significativa',
                    'opcoes' => 'Não Sugestiva;Sugestiva;Necessita Avaliação Clínica'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DA HEPATITE B CRÔNICA
        //==========================================

        [
            'nome' => 'Avaliação da Infecção pelo HBV',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Compatível com Infecção Ativa pelo HBV',
                    'opcoes' => 'Não;Sim;Avaliação Necessária'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Monitorização de Tratamento Antiviral',
                    'opcoes' => 'Não Aplicável;Inicial;Acompanhamento'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resposta ao Tratamento',
                    'opcoes' => 'Não Avaliada;Redução da Carga Viral;Supressão Viral;Sem Resposta'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO COM MARCADORES SOROLÓGICOS
        //==========================================

        [
            'nome' => 'Correlação com Marcadores da Hepatite B',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'HBsAg Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'HBeAg Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anti-HBc Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anti-HBs Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Volume da Amostra',
                    'opcoes' => 'Adequado;Insuficiente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'MOLE002',
    'nome' => 'Carga Viral do HIV (HIV-RNA)',
    'categoria' => 'Laboratório / Biologia Molecular',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Plasma;Sangue Total'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'PCR em Tempo Real (qPCR);Amplificação de Ácido Nucleico (NAT);Outro'
                ],

            ]

        ],


        //==========================================
        // QUANTIFICAÇÃO DO HIV-RNA
        //==========================================

        [
            'nome' => 'Quantificação da Carga Viral',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Detecção do HIV-RNA',
                    'opcoes' => 'Não Detectado;Detectado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Carga Viral do HIV',
                    'unidade' => 'cópias/mL',
                    'valor_referencia' => 'Indetectável conforme limite do método',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100000000
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Carga Viral Logarítmica',
                    'unidade' => 'log10 cópias/mL',
                    'valor_referencia' => 'Conforme resultado quantitativo',
                    'valor_minimo' => 0,
                    'valor_maximo' => 12
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Limite de Detecção do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // RESULTADO MOLECULAR
        //==========================================

        [
            'nome' => 'Resultado Molecular',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Final HIV-RNA',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Replicação Viral',
                    'opcoes' => 'Não Detectável;Baixo;Moderado;Elevado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Viremia Detectável',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // MONITORIZAÇÃO DO TRATAMENTO
        //==========================================

        [
            'nome' => 'Avaliação do Tratamento Antirretroviral',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Situação do Tratamento Antirretroviral',
                    'opcoes' => 'Não Informado;Sem Tratamento;Em Tratamento'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resposta Virológica',
                    'opcoes' => 'Não Avaliada;Supressão Viral;Redução da Carga Viral;Falha Virológica'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Virológico',
                    'opcoes' => 'Adequado;Inadequado;Não Avaliado'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO COMPLEMENTAR
        //==========================================

        [
            'nome' => 'Avaliação Complementar',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Contagem de Linfócitos CD4 Solicitada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste de Resistência Viral Solicitado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Repetição do Exame',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Volume da Amostra',
                    'opcoes' => 'Adequado;Insuficiente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Interferentes na Amostra',
                    'opcoes' => 'Ausentes;Presentes'
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
    'codigo' => 'SOR008',
    'nome' => 'Rubéola IgG - Anticorpos IgG para Rubéola',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE ANTICORPOS IgG
        //==========================================

        [
            'nome' => 'Dosagem de Rubéola IgG',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo IgG',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Anticorpos IgG',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO IMUNOLÓGICA
        //==========================================

        [
            'nome' => 'Interpretação Sorológica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Imunidade contra Rubéola',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Situação Imunológica',
                    'opcoes' => 'Sem Imunidade;Imune;Resultado Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Contato Prévio com o Vírus da Rubéola',
                    'opcoes' => 'Não Evidenciado;Evidenciado'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE IMUNIDADE
        //==========================================

        [
            'nome' => 'Avaliação de Imunidade',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico de Vacinação contra Rubéola',
                    'opcoes' => 'Não Informado;Vacinado;Não Vacinado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resposta Vacinal Detectada',
                    'opcoes' => 'Não Avaliada;Presente;Ausente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Vacinação',
                    'opcoes' => 'Não;Sim;Avaliação Clínica Necessária'
                ],

            ]

        ],


        //==========================================
        // CONTEXTO GESTACIONAL
        //==========================================

        [
            'nome' => 'Avaliação em Gestantes',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestante',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Imunidade Materna contra Rubéola',
                    'opcoes' => 'Ausente;Presente;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliação Complementar',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'SOR009',
    'nome' => 'Toxoplasmose IgG - Anticorpos IgG para Toxoplasmose',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA;Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE ANTICORPOS IgG
        //==========================================

        [
            'nome' => 'Dosagem de Toxoplasmose IgG',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo IgG',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração de Anticorpos IgG',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO SOROLÓGICA
        //==========================================

        [
            'nome' => 'Interpretação Sorológica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Imunidade contra Toxoplasma gondii',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Contato Prévio com Toxoplasma gondii',
                    'opcoes' => 'Não Evidenciado;Evidenciado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Situação Sorológica',
                    'opcoes' => 'Sem Evidência de Infecção Prévia;Infecção Prévia;Resultado Indeterminado'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE INFECÇÃO
        //==========================================

        [
            'nome' => 'Avaliação da Infecção',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Infecção Atual',
                    'opcoes' => 'Não Sugestivo;Sugestivo;Necessita Avaliação Complementar'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Pesquisa de IgM',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Teste de Avidez IgG',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE AVIDEZ
        //==========================================

        [
            'nome' => 'Avidez de IgG',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste de Avidez Realizado',
                    'opcoes' => 'Não Realizado;Realizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Avidez',
                    'opcoes' => 'Não Aplicável;Baixa Avidez;Alta Avidez;Intermediária'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Índice de Avidez IgG',
                    'unidade' => '%',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

            ]

        ],


        //==========================================
        // CONTEXTO GESTACIONAL
        //==========================================

        [
            'nome' => 'Avaliação em Gestantes',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestante',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Risco de Infecção Recente',
                    'opcoes' => 'Não Evidenciado;Possível;Necessita Investigação'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Avaliação Fetal',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'IMU002',
    'nome' => 'Fator Reumatoide (FR)',
    'categoria' => 'Laboratório / Imunologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Nefelometria;Imunoensaio;ELISA;Aglutinação por Látex;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DO FATOR REUMATOIDE
        //==========================================

        [
            'nome' => 'Dosagem do Fator Reumatoide',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Concentração do Fator Reumatoide',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => 'Normalmente inferior ao limite definido pelo laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DO RESULTADO
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença do Fator Reumatoide',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Reatividade',
                    'opcoes' => 'Negativo;Baixa Positividade;Alta Positividade'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Compatível com Autoimunidade',
                    'opcoes' => 'Não Sugestivo;Sugestivo;Necessita Avaliação Clínica'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO COM DOENÇAS REUMÁTICAS
        //==========================================

        [
            'nome' => 'Correlação Clínica',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação para Artrite Reumatoide',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Associação com Doença Autoimune',
                    'opcoes' => 'Não Evidenciada;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Testes Complementares',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // TESTES COMPLEMENTARES
        //==========================================

        [
            'nome' => 'Avaliação Complementar',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Anti-CCP Solicitado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'PCR (Proteína C Reativa) Solicitada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'VHS Solicitado',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'SOR010',
    'nome' => 'P. Leptospirose - Pesquisa para Leptospirose',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Sangue Total;Líquor;Urina'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'ELISA IgM/IgG;MAT (Microaglutinação);Imunocromatografia;PCR;Outro'
                ],

            ]

        ],


        //==========================================
        // PESQUISA DE LEPTOSPIRA
        //==========================================

        [
            'nome' => 'Pesquisa para Leptospirose',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Pesquisa',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Título de Anticorpos (MAT)',
                    'unidade' => 'Título',
                    'valor_referencia' => 'Conforme critério do método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // ANTICORPOS ESPECÍFICOS
        //==========================================

        [
            'nome' => 'Avaliação de Anticorpos',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos IgM contra Leptospira',
                    'opcoes' => 'Não Reagente;Reagente;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos IgG contra Leptospira',
                    'opcoes' => 'Não Reagente;Reagente;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Soroconversão Detectada',
                    'opcoes' => 'Não Avaliada;Ausente;Presente'
                ],

            ]

        ],


        //==========================================
        // IDENTIFICAÇÃO DA ESPÉCIE
        //==========================================

        [
            'nome' => 'Identificação da Leptospira',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sorogrupo Identificado',
                    'opcoes' => 'Não Identificado;Icterohaemorrhagiae;Canicola;Pomona;Grippotyphosa;Outro'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Observação sobre Sorogrupo',
                    'tamanho_maximo' => 250
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Evidência de Infecção por Leptospira',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Fase Provável da Infecção',
                    'opcoes' => 'Não Determinada;Aguda;Convalescente;Infecção Prévia'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Confirmação Complementar',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO COMPLEMENTAR
        //==========================================

        [
            'nome' => 'Avaliação Complementar',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'PCR para Leptospira Solicitado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Nova Coleta Recomendada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Correlação Clínica Necessária',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'BIO001',
    'nome' => 'PCR - Proteína C Reativa',
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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Imunoturbidimetria;Nefelometria;Aglutinação;Quimioluminescência;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DA PCR
        //==========================================

        [
            'nome' => 'Dosagem da Proteína C Reativa',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Proteína C Reativa (PCR)',
                    'unidade' => 'mg/L',
                    'valor_referencia' => 'Inferior a 5 mg/L (conforme método)',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Elevada'
                ],

            ]

        ],


        //==========================================
        // CLASSIFICAÇÃO INFLAMATÓRIA
        //==========================================

        [
            'nome' => 'Classificação do Resultado',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível Inflamatório',
                    'opcoes' => 'Sem Evidência de Inflamação;Elevação Discreta;Elevação Moderada;Elevação Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resposta Inflamatória Sistêmica',
                    'opcoes' => 'Não Sugestiva;Sugestiva;Necessita Avaliação Clínica'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Compatível com Processo Infeccioso',
                    'opcoes' => 'Não Sugestivo;Possível;Sugestivo'
                ],

            ]

        ],


        //==========================================
        // PCR ULTRASSENSÍVEL
        //==========================================

        [
            'nome' => 'Avaliação de Risco Cardiovascular (PCR Ultrassensível)',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'PCR Ultrassensível Realizada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'PCR Ultrassensível',
                    'unidade' => 'mg/L',
                    'valor_referencia' => 'Avaliação conforme risco cardiovascular',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação de Risco Cardiovascular',
                    'opcoes' => 'Baixo Risco;Risco Intermediário;Alto Risco;Não Avaliado'
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
                    'nome' => 'Avaliação de Infecção Aguda',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Doença Inflamatória',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Repetição do Exame',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'IMU003',
    'nome' => 'TASO - Título de Antiestreptolisina O (ASLO)',
    'categoria' => 'Laboratório / Imunologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Imunoensaio;Nefelometria;Turbidimetria;Aglutinação por Látex;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE ANTIESTREPTOLISINA O
        //==========================================

        [
            'nome' => 'Dosagem de Antiestreptolisina O (ASLO)',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Título de Antiestreptolisina O (ASLO)',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => 'Inferior ao limite definido pelo laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DO RESULTADO
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Anticorpos Anti-Estreptolisina O',
                    'opcoes' => 'Não Evidenciada;Evidenciada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Elevação do ASLO',
                    'opcoes' => 'Normal;Elevação Discreta;Elevação Moderada;Elevação Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Evidência de Infecção Estreptocócica Prévia',
                    'opcoes' => 'Não Sugestiva;Sugestiva;Necessita Correlação Clínica'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE INFECÇÃO ESTREPTOCÓCICA
        //==========================================

        [
            'nome' => 'Avaliação de Infecção por Streptococcus',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Suspeita de Infecção Recente',
                    'opcoes' => 'Não;Sim;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Monitorização por Títulos Seriadas',
                    'opcoes' => 'Não Realizada;Realizada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Elevação Progressiva do Título',
                    'opcoes' => 'Não Avaliada;Ausente;Presente'
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
                    'nome' => 'Avaliação para Febre Reumática',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação para Glomerulonefrite Pós-Estreptocócica',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Testes Complementares',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // TESTES ASSOCIADOS
        //==========================================

        [
            'nome' => 'Avaliação Complementar',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'PCR Solicitada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'VHS Solicitado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Cultura de Secreção de Garganta Solicitada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'SOR011',
    'nome' => 'VDRL - Venereal Disease Research Laboratory (Teste para Sífilis)',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma;Líquor'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'VDRL (Floculação);Teste Não Treponêmico;Outro'
                ],

            ]

        ],


        //==========================================
        // RESULTADO VDRL
        //==========================================

        [
            'nome' => 'Resultado do VDRL',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Não Reagente;Reagente;Fraco Reagente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Título do VDRL',
                    'unidade' => 'Diluição',
                    'valor_referencia' => 'Não Reagente',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2048
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // TITULAÇÃO
        //==========================================

        [
            'nome' => 'Titulação do Anticorpo',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Diluição Encontrada',
                    'opcoes' => 'Não Reagente;1:1;1:2;1:4;1:8;1:16;1:32;1:64;Maior que 1:64'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Evolução do Título',
                    'opcoes' => 'Não Avaliada;Redução do Título;Aumento do Título;Estável'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Controle Pós-Tratamento',
                    'opcoes' => 'Não Aplicável;Adequado;Inadequado;Não Avaliado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO PARA SÍFILIS
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Compatível com Sífilis',
                    'opcoes' => 'Não Sugestivo;Sugestivo;Necessita Confirmação'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Atividade da Infecção',
                    'opcoes' => 'Não Evidenciada;Possível Infecção Ativa;Avaliação Necessária'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Teste Treponêmico Confirmatório',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // TESTES COMPLEMENTARES
        //==========================================

        [
            'nome' => 'Testes Complementares para Sífilis',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste Treponêmico Realizado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste Treponêmico',
                    'opcoes' => 'Não Realizado;Não Reagente;Reagente;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'PCR para Treponema pallidum',
                    'opcoes' => 'Não Realizado;Negativo;Positivo'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Avaliação Clínica',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico de Tratamento para Sífilis',
                    'opcoes' => 'Não Informado;Sem Tratamento Prévio;Tratamento Realizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestante',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Sífilis Congênita',
                    'opcoes' => 'Não Aplicável;Indicada;Não Indicada'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'SOR012',
    'nome' => 'WIDAL - Reação de Widal (Diagnóstico de Febre Tifoide)',
    'categoria' => 'Laboratório / Sorologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Aglutinação em Tubos;Aglutinação em Lâmina;Microaglutinação;Outro'
                ],

            ]

        ],


        //==========================================
        // REAÇÃO DE WIDAL
        //==========================================

        [
            'nome' => 'Reação de Widal',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Geral da Reação de Widal',
                    'opcoes' => 'Negativo;Positivo;Indeterminado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Método',
                    'tamanho_maximo' => 150
                ],

            ]

        ],


        //==========================================
        // ANTICORPOS SOMÁTICOS O
        //==========================================

        [
            'nome' => 'Antígeno Somático O (Salmonella Typhi O)',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Título Anti-O',
                    'unidade' => 'Diluição',
                    'valor_referencia' => 'Conforme padrão epidemiológico e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 4096
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Anti-O',
                    'opcoes' => 'Negativo;Reagente;Indeterminado'
                ],

            ]

        ],


        //==========================================
        // ANTICORPOS FLAGELARES H
        //==========================================

        [
            'nome' => 'Antígeno Flagelar H (Salmonella Typhi H)',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Título Anti-H',
                    'unidade' => 'Diluição',
                    'valor_referencia' => 'Conforme padrão epidemiológico e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 4096
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Anti-H',
                    'opcoes' => 'Negativo;Reagente;Indeterminado'
                ],

            ]

        ],


        //==========================================
        // OUTROS ANTÍGENOS SALMONELLA
        //==========================================

        [
            'nome' => 'Outros Antígenos Avaliados',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Salmonella Paratyphi A (PA)',
                    'opcoes' => 'Não Reagente;Reagente;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Salmonella Paratyphi B (PB)',
                    'opcoes' => 'Não Reagente;Reagente;Não Avaliado'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Título Salmonella Paratyphi',
                    'unidade' => 'Diluição',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 4096
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO LABORATORIAL
        //==========================================

        [
            'nome' => 'Interpretação Laboratorial',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Evidência Sorológica de Salmonella',
                    'opcoes' => 'Não Evidenciada;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Compatível com Febre Tifoide',
                    'opcoes' => 'Não Sugestivo;Sugestivo;Necessita Correlação Clínica'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Confirmação por Cultura',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Avaliação Clínica',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sintomas Compatíveis',
                    'opcoes' => 'Não Informado;Febre Prolongada;Sintomas Gastrointestinais;Outros'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso Prévio de Antibióticos',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 9,

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
    'codigo' => 'HORM001',
    'nome' => 'PSA Total - Antígeno Prostático Específico Total',
    'categoria' => 'Laboratório / Marcadores Tumorais',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);ELISA;Imunoensaio;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM PSA TOTAL
        //==========================================

        [
            'nome' => 'Dosagem do PSA Total',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'PSA Total',
                    'unidade' => 'ng/mL',
                    'valor_referencia' => 'Geralmente inferior a 4,0 ng/mL (conforme idade e método)',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Dentro do Referencial;Elevado'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DO PSA
        //==========================================

        [
            'nome' => 'Avaliação do Resultado',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do PSA Total',
                    'opcoes' => 'Baixo;Limítrofe;Elevado;Muito Elevado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Alteração Prostática',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Avaliação Urológica',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // FATORES ASSOCIADOS
        //==========================================

        [
            'nome' => 'Informações Clínicas Associadas',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Idade do Paciente',
                    'unidade' => 'anos',
                    'valor_referencia' => 'Avaliação conforme faixa etária',
                    'valor_minimo' => 0,
                    'valor_maximo' => 120
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico Familiar de Câncer de Próstata',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sintomas Prostáticos',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO COMPLEMENTAR
        //==========================================

        [
            'nome' => 'Avaliação Complementar da Próstata',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'PSA Livre Solicitado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Toque Retal Realizado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ultrassonografia Prostática Solicitada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // RELAÇÃO PSA LIVRE/TOTAL
        //==========================================

        [
            'nome' => 'Relação PSA Livre e Total',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'PSA Livre Disponível',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Percentual PSA Livre/PSA Total',
                    'unidade' => '%',
                    'valor_referencia' => 'Conforme avaliação clínica',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação da Relação PSA Livre/Total',
                    'opcoes' => 'Não Avaliada;Menor Probabilidade;Maior Probabilidade;Avaliação Necessária'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Interferentes Pré-Analíticos',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'HEM001',
    'nome' => 'Eletroforese de Hemoglobina',
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
                    'tipo' => 'lista',
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Sangue Total com EDTA'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Eletroforese em Gel;HPLC (Cromatografia Líquida de Alta Eficiência);Capilar;Outro'
                ],

            ]

        ],


        //==========================================
        // PERFIL HEMOGLOBÍNICO
        //==========================================

        [
            'nome' => 'Perfil Hemoglobínico',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina A (HbA)',
                    'unidade' => '%',
                    'valor_referencia' => '95 - 98%',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina A2 (HbA2)',
                    'unidade' => '%',
                    'valor_referencia' => '1,5 - 3,5%',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina Fetal (HbF)',
                    'unidade' => '%',
                    'valor_referencia' => 'Inferior a 2%',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

            ]

        ],


        //==========================================
        // HEMOGLOBINAS ANORMAIS
        //==========================================

        [
            'nome' => 'Pesquisa de Hemoglobinas Anormais',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemoglobina S (HbS)',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Percentual de Hemoglobina S',
                    'unidade' => '%',
                    'valor_referencia' => 'Ausente',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemoglobina C (HbC)',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Outras Variantes Hemoglobínicas',
                    'opcoes' => 'Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // CLASSIFICAÇÃO DO PADRÃO
        //==========================================

        [
            'nome' => 'Classificação do Padrão Hemoglobínico',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Padrão Encontrado',
                    'opcoes' => 'AA (Normal);AS (Traço Falciforme);SS (Anemia Falciforme);SC;Talassemia;Outro'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação Geral',
                    'opcoes' => 'Normal;Alteração Compatível com Hemoglobinopatia;Necessita Investigação Complementar'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Descrição do Padrão Hemoglobínico',
                    'tamanho_maximo' => 500
                ],

            ]

        ],


        //==========================================
        // TALASSEMIA
        //==========================================

        [
            'nome' => 'Avaliação para Talassemias',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Beta Talassemia',
                    'opcoes' => 'Não Sugestivo;Sugestivo;Necessita Confirmação'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sugestivo de Alfa Talassemia',
                    'opcoes' => 'Não Sugestivo;Sugestivo;Necessita Investigação Molecular'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estudo Molecular Recomendado',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO HEMATOLÓGICA
        //==========================================

        [
            'nome' => 'Correlação Hematológica',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemograma Associado',
                    'opcoes' => 'Não Disponível;Disponível'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina Total',
                    'unidade' => 'g/dL',
                    'valor_referencia' => 'Conforme idade e sexo',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anemia Presente',
                    'opcoes' => 'Não;Sim;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Qualidade da Amostra',
                    'opcoes' => 'Adequada;Inadequada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'HORM002',
    'nome' => 'T3 Livre - Triiodotironina Livre',
    'categoria' => 'Laboratório / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;ELISA;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE T3 LIVRE
        //==========================================

        [
            'nome' => 'Dosagem de Triiodotironina Livre (T3 Livre)',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'T3 Livre',
                    'unidade' => 'pg/mL',
                    'valor_referencia' => 'Conforme faixa etária e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Reduzido;Elevado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO HORMONAL
        //==========================================

        [
            'nome' => 'Interpretação Hormonal',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de T3 Livre',
                    'opcoes' => 'Dentro da Referência;Abaixo da Referência;Acima da Referência'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Tireoidiana Sugestiva',
                    'opcoes' => 'Eutireoidismo;Hipotireoidismo;Hipertireoidismo;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração Hormonal Detectada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO COM TIREOIDE
        //==========================================

        [
            'nome' => 'Avaliação da Função Tireoidiana',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'TSH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'T4 Livre Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos Antitireoidianos Avaliados',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sintomas Tireoidianos',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Hormônio Tireoidiano',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tratamento para Distúrbio da Tireoide',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'HORM003',
    'nome' => 'FSH - Hormônio Folículo-Estimulante',
    'categoria' => 'Laboratório / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);ELISA;Imunoensaio;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DO FSH
        //==========================================

        [
            'nome' => 'Dosagem do Hormônio Folículo-Estimulante (FSH)',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'FSH',
                    'unidade' => 'mUI/mL',
                    'valor_referencia' => 'Variável conforme sexo, idade e fase do ciclo menstrual',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Reduzido;Elevado'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO FEMININA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Feminina',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fase do Ciclo Menstrual',
                    'opcoes' => 'Fase Folicular;Período Ovulatório;Fase Lútea;Menopausa;Não Informada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Reserva Ovariana Avaliada',
                    'opcoes' => 'Não Avaliada;Preservada;Reduzida;Não Determinada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Menopausa/Climatério',
                    'opcoes' => 'Não Indicada;Compatível;Não Compatível;Necessita Avaliação'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO MASCULINA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Masculina',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Gonadal Masculina',
                    'opcoes' => 'Sem Alteração Evidente;Possível Alteração;Necessita Investigação'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Fertilidade',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração da Espermatogênese Sugestiva',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO ENDÓCRINA
        //==========================================

        [
            'nome' => 'Interpretação Endócrina',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de FSH',
                    'opcoes' => 'Dentro da Referência;Abaixo da Referência;Acima da Referência'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Hipofisária/Gonadal',
                    'opcoes' => 'Sem Alteração Evidente;Sugestiva de Hipofunção;Sugestiva de Hiperestimulação'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Avaliação Complementar',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // EXAMES ASSOCIADOS
        //==========================================

        [
            'nome' => 'Avaliação Complementar Hormonal',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'LH Solicitado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estradiol Solicitado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Testosterona Solicitada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Prolactina Solicitada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'AMH (Hormônio Antimülleriano) Solicitado',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'HORM004',
    'nome' => 'T3 - Triiodotironina Total',
    'categoria' => 'Laboratório / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;ELISA;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DO T3 TOTAL
        //==========================================

        [
            'nome' => 'Dosagem de Triiodotironina Total (T3 Total)',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'T3 Total',
                    'unidade' => 'ng/dL',
                    'valor_referencia' => 'Conforme faixa etária e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Reduzido;Elevado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DO RESULTADO
        //==========================================

        [
            'nome' => 'Interpretação Hormonal',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de T3 Total',
                    'opcoes' => 'Dentro da Referência;Abaixo da Referência;Acima da Referência'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Tireoidiana Sugestiva',
                    'opcoes' => 'Eutireoidismo;Hipotireoidismo;Hipertireoidismo;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração Tireoidiana Detectada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DA TIREOIDE
        //==========================================

        [
            'nome' => 'Avaliação da Função Tireoidiana',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'TSH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'T4 Total Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'T4 Livre Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos Antitireoidianos Avaliados',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // PROTEÍNAS TRANSPORTADORAS
        //==========================================

        [
            'nome' => 'Avaliação de Transporte Hormonal',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Interferência por Proteínas Transportadoras',
                    'opcoes' => 'Não Avaliada;Não Sugestiva;Possível'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Medicamentos que Alteram Hormônios Tireoidianos',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Condição Clínica Associada',
                    'opcoes' => 'Não Informada;Gestação;Doença Sistêmica;Outra'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sintomas Tireoidianos',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Doença Tireoidiana Prévia',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Hormônio Tireoidiano',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'HORM005',
    'nome' => 'T4 - Tiroxina Total',
    'categoria' => 'Laboratório / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;ELISA;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DO T4 TOTAL
        //==========================================

        [
            'nome' => 'Dosagem de Tiroxina Total (T4 Total)',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'T4 Total',
                    'unidade' => 'µg/dL',
                    'valor_referencia' => 'Conforme faixa etária e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Reduzido;Elevado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO HORMONAL
        //==========================================

        [
            'nome' => 'Interpretação Hormonal',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de T4 Total',
                    'opcoes' => 'Dentro da Referência;Abaixo da Referência;Acima da Referência'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Tireoidiana Sugestiva',
                    'opcoes' => 'Eutireoidismo;Hipotireoidismo;Hipertireoidismo;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração Tireoidiana Detectada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DA FUNÇÃO TIREOIDIANA
        //==========================================

        [
            'nome' => 'Avaliação da Função Tireoidiana',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'TSH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'T3 Total Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'T4 Livre Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpos Antitireoidianos Avaliados',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // PROTEÍNAS TRANSPORTADORAS
        //==========================================

        [
            'nome' => 'Avaliação de Transporte Hormonal',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Influência de Proteínas Transportadoras',
                    'opcoes' => 'Não Avaliada;Não Sugestiva;Possível'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração por Ligação Proteica',
                    'opcoes' => 'Não Evidenciada;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Condição Clínica Associada',
                    'opcoes' => 'Não Informada;Gestação;Doença Sistêmica;Uso de Medicamentos;Outra'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Sintomas de Alteração Tireoidiana',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico de Doença da Tireoide',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Hormônio Tireoidiano',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'HORM006',
    'nome' => 'Estradiol (E2)',
    'categoria' => 'Laboratório / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;ELISA;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DO ESTRADIOL
        //==========================================

        [
            'nome' => 'Dosagem de Estradiol (E2)',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Estradiol (E2)',
                    'unidade' => 'pg/mL',
                    'valor_referencia' => 'Variável conforme sexo, idade e fase hormonal',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Reduzido;Elevado'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO FEMININA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Feminina',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fase do Ciclo Menstrual',
                    'opcoes' => 'Fase Folicular;Período Ovulatório;Fase Lútea;Menopausa;Não Informada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estado Hormonal Feminino',
                    'opcoes' => 'Pré-Menopausa;Menopausa;Pós-Menopausa;Gestação;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação da Função Ovariana',
                    'opcoes' => 'Preservada;Reduzida;Alterada;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO MASCULINA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Masculina',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Estradiol Masculino',
                    'opcoes' => 'Dentro da Referência;Reduzido;Elevado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Desequilíbrio Estrogênico Sugestivo',
                    'opcoes' => 'Não Sugestivo;Possível;Sugestivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Fertilidade Masculina',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE GESTAÇÃO
        //==========================================

        [
            'nome' => 'Avaliação Gestacional',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestação Informada',
                    'opcoes' => 'Não Informada;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Idade Gestacional Informada',
                    'opcoes' => 'Não Informada;Primeiro Trimestre;Segundo Trimestre;Terceiro Trimestre'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Semanas de Gestação',
                    'tamanho_maximo' => 50
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO HORMONAL
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Complementar',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'FSH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'LH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Progesterona Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Testosterona Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'AMH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
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
    'codigo' => 'HORM007',
    'nome' => 'Progesterona',
    'categoria' => 'Laboratório / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;ELISA;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DA PROGESTERONA
        //==========================================

        [
            'nome' => 'Dosagem de Progesterona',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Progesterona',
                    'unidade' => 'ng/mL',
                    'valor_referencia' => 'Variável conforme sexo, idade, fase do ciclo menstrual e gestação',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Reduzida;Elevada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO FEMININA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Feminina',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fase do Ciclo Menstrual',
                    'opcoes' => 'Fase Folicular;Ovulação;Fase Lútea;Menopausa;Não Informada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Lútea',
                    'opcoes' => 'Preservada;Reduzida;Ausente;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Ovulação',
                    'opcoes' => 'Compatível com Ovulação;Não Sugestiva;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE FERTILIDADE
        //==========================================

        [
            'nome' => 'Avaliação de Fertilidade',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Investigação de Infertilidade',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Reserva/Função Ovariana Avaliada',
                    'opcoes' => 'Não Avaliada;Adequada;Alterada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Monitorização de Ciclo',
                    'opcoes' => 'Não Realizada;Realizada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO GESTACIONAL
        //==========================================

        [
            'nome' => 'Avaliação Gestacional',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestação Informada',
                    'opcoes' => 'Não Informada;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Idade Gestacional',
                    'opcoes' => 'Não Informada;Primeiro Trimestre;Segundo Trimestre;Terceiro Trimestre'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Semanas de Gestação',
                    'tamanho_maximo' => 50
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação da Progesterona na Gestação',
                    'opcoes' => 'Adequada;Reduzida;Elevada;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO MASCULINA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Masculina',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Progesterona Masculina',
                    'opcoes' => 'Dentro da Referência;Reduzido;Elevado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração Hormonal Sugestiva',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO HORMONAL
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Complementar',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Estradiol Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'FSH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'LH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Beta-HCG Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 9,

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
    'codigo' => 'HORM008',
    'nome' => 'Testosterona Total',
    'categoria' => 'Laboratório / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;LC-MS/MS;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DA TESTOSTERONA TOTAL
        //==========================================

        [
            'nome' => 'Dosagem de Testosterona Total',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Testosterona Total',
                    'unidade' => 'ng/dL',
                    'valor_referencia' => 'Variável conforme sexo, idade e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Reduzida;Elevada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO MASCULINA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Masculina',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Testosterona',
                    'opcoes' => 'Dentro da Referência;Abaixo da Referência;Acima da Referência'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Gonadal Masculina',
                    'opcoes' => 'Sem Alteração Evidente;Sugestiva de Hipogonadismo;Sugestiva de Hiperandrogenismo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sintomas Relacionados',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO FEMININA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Feminina',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Testosterona Feminina',
                    'opcoes' => 'Dentro da Referência;Reduzida;Elevada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Hiperandrogenismo',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais Clínicos de Excesso Androgênico',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // TESTOSTERONA LIVRE (QUANDO SOLICITADA)
        //==========================================

        [
            'nome' => 'Testosterona Livre (Quando Solicitada)',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Testosterona Livre Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Testosterona Livre',
                    'unidade' => 'pg/mL',
                    'valor_referencia' => 'Conforme método e laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método de Cálculo da Testosterona Livre',
                    'tamanho_maximo' => 200
                ],

            ]

        ],


        //==========================================
        // PROTEÍNAS TRANSPORTADORAS
        //==========================================

        [
            'nome' => 'Avaliação de Proteínas Transportadoras',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'SHBG Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'SHBG',
                    'unidade' => 'nmol/L',
                    'valor_referencia' => 'Conforme método e sexo',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Influência de Proteínas Transportadoras',
                    'opcoes' => 'Não Avaliada;Não Sugestiva;Possível'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE FERTILIDADE
        //==========================================

        [
            'nome' => 'Avaliação Reprodutiva',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Investigação de Fertilidade',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Espermatograma Associado',
                    'opcoes' => 'Não Realizado;Realizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Eixo Hormonal',
                    'opcoes' => 'Não Avaliada;Avaliada'
                ],

            ]

        ],


        //==========================================
        // EXAMES HORMONAIS ASSOCIADOS
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Complementar',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'LH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'FSH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estradiol Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Prolactina Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 10,

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
    'codigo' => 'HORM009',
    'nome' => 'Cortisol e Prolactina',
    'categoria' => 'Laboratório / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;ELISA;Outro'
                ],

            ]

        ],


        //==========================================
        // CORTISOL
        //==========================================

        [
            'nome' => 'Dosagem de Cortisol',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Cortisol',
                    'unidade' => 'µg/dL',
                    'valor_referencia' => 'Variável conforme horário da coleta e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Horário da Coleta do Cortisol',
                    'opcoes' => 'Manhã;Tarde;Noite;Não Informado'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório para Cortisol',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado do Cortisol',
                    'opcoes' => 'Normal;Reduzido;Elevado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DO CORTISOL
        //==========================================

        [
            'nome' => 'Interpretação do Cortisol',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação do Eixo Adrenal',
                    'opcoes' => 'Sem Alteração Evidente;Sugestivo de Hipocortisolismo;Sugestivo de Hipercortisolismo;Indeterminado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Investigação de Síndrome de Cushing',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Investigação de Insuficiência Adrenal',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // PROLACTINA
        //==========================================

        [
            'nome' => 'Dosagem de Prolactina',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Prolactina',
                    'unidade' => 'ng/mL',
                    'valor_referencia' => 'Variável conforme sexo, idade e condição clínica',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório para Prolactina',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado da Prolactina',
                    'opcoes' => 'Normal;Reduzida;Elevada'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DA PROLACTINA
        //==========================================

        [
            'nome' => 'Interpretação da Prolactina',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Prolactina',
                    'opcoes' => 'Dentro da Referência;Abaixo da Referência;Acima da Referência'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hiperprolactinemia',
                    'opcoes' => 'Ausente;Presente;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Investigação Complementar',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Medicamentos que Podem Alterar Hormônios',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estresse Agudo no Momento da Coleta',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sintomas Relacionados à Prolactina',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alterações do Ciclo Menstrual',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração de Libido/Fertilidade',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // EXAMES COMPLEMENTARES
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Complementar',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'ACTH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'TSH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'LH/FSH Avaliados',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ressonância de Hipófise Solicitada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 9,

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
    'codigo' => 'HORM010',
    'nome' => 'β-HCG - Gonadotrofina Coriônica Humana Beta',
    'categoria' => 'Laboratório / Endocrinologia / Marcadores Gestacionais',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Urina'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;Teste Imunocromatográfico;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DO β-HCG
        //==========================================

        [
            'nome' => 'Dosagem de β-HCG',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'β-HCG Quantitativo',
                    'unidade' => 'mUI/mL',
                    'valor_referencia' => 'Negativo geralmente inferior a 5 mUI/mL (conforme método)',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Negativo;Positivo;Indeterminado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DO RESULTADO
        //==========================================

        [
            'nome' => 'Interpretação do β-HCG',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Não Reagente;Reagente;Zona Cinzenta;Necessita Repetição'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Compatibilidade com Gestação',
                    'opcoes' => 'Não Sugestiva;Sugestiva;Confirmada por Correlação Clínica'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Controle Evolutivo',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO GESTACIONAL
        //==========================================

        [
            'nome' => 'Avaliação Gestacional',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestação Informada',
                    'opcoes' => 'Não Informada;Não;Sim'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Data da Última Menstruação (DUM)',
                    'tamanho_maximo' => 50
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Idade Gestacional Estimada',
                    'unidade' => 'semanas',
                    'valor_referencia' => 'Conforme cálculo obstétrico',
                    'valor_minimo' => 0,
                    'valor_maximo' => 45
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Evolução do β-HCG',
                    'opcoes' => 'Primeira Dosagem;Em Acompanhamento;Aumento Adequado;Aumento Inadequado;Redução'
                ],

            ]

        ],


        //==========================================
        // MARCADOR TUMORAL
        //==========================================

        [
            'nome' => 'Avaliação como Marcador Tumoral',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Investigação Não Gestacional',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Produção Ectópica de β-HCG',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Investigação Especializada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Indicação do Exame',
                    'opcoes' => 'Suspeita de Gestação;Acompanhamento Gestacional;Infertilidade;Monitorização Terapêutica;Investigação Clínica'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sintomas Associados',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tratamento de Fertilidade',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // EXAMES COMPLEMENTARES
        //==========================================

        [
            'nome' => 'Avaliação Complementar',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Ultrassonografia Obstétrica Realizada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Progesterona Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Marcadores Complementares Avaliados',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 9,

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
    'codigo' => 'HORM011',
    'nome' => 'Testosterona Livre',
    'categoria' => 'Laboratório / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Cálculo pela Testosterona Total e SHBG;Diálise de Equilíbrio;Imunoensaio;LC-MS/MS;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DA TESTOSTERONA LIVRE
        //==========================================

        [
            'nome' => 'Dosagem de Testosterona Livre',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Testosterona Livre',
                    'unidade' => 'pg/mL',
                    'valor_referencia' => 'Variável conforme sexo, idade e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Reduzida;Elevada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO ANDROGÊNICA MASCULINA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Masculina',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Testosterona Livre',
                    'opcoes' => 'Dentro da Referência;Abaixo da Referência;Acima da Referência'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Androgênios',
                    'opcoes' => 'Normal;Sugestiva de Deficiência Androgênica;Sugestiva de Excesso Androgênico'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipogonadismo Masculino',
                    'opcoes' => 'Não Sugestivo;Possível;Sugestivo'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO FEMININA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Feminina',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Testosterona Livre Feminina',
                    'opcoes' => 'Dentro da Referência;Reduzida;Elevada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Hiperandrogenismo',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sinais Clínicos de Excesso Androgênico',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // PROTEÍNAS TRANSPORTADORAS
        //==========================================

        [
            'nome' => 'Avaliação de Proteínas Transportadoras',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'SHBG Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'SHBG',
                    'unidade' => 'nmol/L',
                    'valor_referencia' => 'Conforme sexo e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Influência da SHBG no Resultado',
                    'opcoes' => 'Não Avaliada;Sem Influência Evidente;Possível Influência'
                ],

            ]

        ],


        //==========================================
        // RELAÇÃO COM TESTOSTERONA TOTAL
        //==========================================

        [
            'nome' => 'Correlação com Testosterona Total',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Testosterona Total Disponível',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Testosterona Total Associada',
                    'unidade' => 'ng/dL',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Concordância entre Testosterona Total e Livre',
                    'opcoes' => 'Compatível;Discordante;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO DE FERTILIDADE
        //==========================================

        [
            'nome' => 'Avaliação Reprodutiva',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Investigação de Fertilidade',
                    'opcoes' => 'Não Indicada;Indicada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Espermatograma Associado',
                    'opcoes' => 'Não Realizado;Realizado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação do Eixo Hipotálamo-Hipófise-Gonadal',
                    'opcoes' => 'Não Avaliada;Avaliada'
                ],

            ]

        ],


        //==========================================
        // HORMÔNIOS ASSOCIADOS
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Complementar',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'LH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'FSH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estradiol Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Prolactina Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 10,

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
    'codigo' => 'MARC001',
    'nome' => 'PSA - Antígeno Prostático Específico',
    'categoria' => 'Laboratório / Marcadores Tumorais',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DO PSA
        //==========================================

        [
            'nome' => 'Dosagem do Antígeno Prostático Específico (PSA)',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'PSA Total',
                    'unidade' => 'ng/mL',
                    'valor_referencia' => 'Conforme idade, método utilizado e referência do laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Dentro da Referência;Elevado'
                ],

            ]

        ],


        //==========================================
        // CLASSIFICAÇÃO DO RESULTADO
        //==========================================

        [
            'nome' => 'Interpretação do PSA',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do PSA',
                    'opcoes' => 'Baixo;Limítrofe;Elevado;Muito Elevado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Possível Alteração Prostática',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessita Avaliação Urológica',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // PSA LIVRE
        //==========================================

        [
            'nome' => 'PSA Livre e Relação PSA Livre/Total',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'PSA Livre Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'PSA Livre',
                    'unidade' => 'ng/mL',
                    'valor_referencia' => 'Conforme método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Relação PSA Livre/PSA Total',
                    'unidade' => '%',
                    'valor_referencia' => 'Conforme avaliação clínica',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação da Relação PSA Livre/Total',
                    'opcoes' => 'Não Avaliada;Menor Probabilidade de Alteração Maligna;Maior Probabilidade de Alteração Maligna;Necessita Avaliação'
                ],

            ]

        ],


        //==========================================
        // DADOS CLÍNICOS
        //==========================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Idade do Paciente',
                    'unidade' => 'anos',
                    'valor_referencia' => 'Avaliação conforme faixa etária',
                    'valor_minimo' => 0,
                    'valor_maximo' => 120
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sintomas Urinários',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico Familiar de Câncer de Próstata',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Exame de Toque Retal Realizado',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONDIÇÕES QUE PODEM ALTERAR PSA
        //==========================================

        [
            'nome' => 'Fatores Interferentes',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Manipulação Prostática Recente',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Biópsia Prostática Recente',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Infecção/Inflamação Prostática',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Medicamentos que Alteram PSA',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // ACOMPANHAMENTO
        //==========================================

        [
            'nome' => 'Acompanhamento do PSA',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'PSA Anterior Disponível',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'PSA Anterior',
                    'unidade' => 'ng/mL',
                    'valor_referencia' => 'Comparação evolutiva',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Variação do PSA',
                    'opcoes' => 'Estável;Aumento;Redução;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 9,

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
    'codigo' => 'HORM012',
    'nome' => 'Estrogênios',
    'categoria' => 'Laboratório / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma;Urina'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);Imunoensaio;Cromatografia/Espectrometria de Massa;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE ESTROGÊNIOS
        //==========================================

        [
            'nome' => 'Dosagem de Estrogênios',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Estrogênios Totais',
                    'unidade' => 'pg/mL',
                    'valor_referencia' => 'Variável conforme sexo, idade, fase do ciclo menstrual e método utilizado',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Reduzido;Elevado'
                ],

            ]

        ],


        //==========================================
        // TIPOS DE ESTROGÊNIOS AVALIADOS
        //==========================================

        [
            'nome' => 'Tipos de Estrogênios Avaliados',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Estradiol (E2) Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estrona (E1) Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estriol (E3) Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estrogênios Totais Avaliados',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO FEMININA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Feminina',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Fase do Ciclo Menstrual',
                    'opcoes' => 'Fase Folicular;Período Ovulatório;Fase Lútea;Menopausa;Não Informada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Estado Hormonal',
                    'opcoes' => 'Pré-Menopausa;Menopausa;Pós-Menopausa;Gestação;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Ovariana',
                    'opcoes' => 'Preservada;Reduzida;Alterada;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO MASCULINA
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Masculina',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Nível de Estrogênios Masculino',
                    'opcoes' => 'Dentro da Referência;Reduzido;Elevado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Equilíbrio Androgênio/Estrogênio',
                    'opcoes' => 'Adequado;Alterado;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Excesso Estrogênico Sugestivo',
                    'opcoes' => 'Não Sugestivo;Possível;Sugestivo'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO GESTACIONAL
        //==========================================

        [
            'nome' => 'Avaliação Gestacional',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestação Informada',
                    'opcoes' => 'Não Informada;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Trimestre Gestacional',
                    'opcoes' => 'Não Informado;Primeiro Trimestre;Segundo Trimestre;Terceiro Trimestre'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Avaliação de Produção Estrogênica',
                    'opcoes' => 'Adequada;Reduzida;Elevada;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO HORMONAL
        //==========================================

        [
            'nome' => 'Avaliação Hormonal Complementar',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'FSH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'LH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Progesterona Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Testosterona Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Indicação do Exame',
                    'opcoes' => 'Avaliação Hormonal;Infertilidade;Menopausa;Distúrbios Menstruais;Monitorização Terapêutica;Outra'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Terapia Hormonal',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Sintomas Relacionados',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 9,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 10,

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
    'codigo' => 'AUTO001',
    'nome' => 'A/HMA - Anticorpos Anti-Microssomais (Anti-TPO / Anticorpos Anti-Peroxidase Tireoidiana)',
    'categoria' => 'Laboratório / Imunologia / Endocrinologia',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Quimioluminescência (CLIA);Eletroquimioluminescência (ECLIA);ELISA;Imunoensaio;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE ANTI-TPO
        //==========================================

        [
            'nome' => 'Dosagem de Anticorpos Anti-TPO',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Anticorpos Anti-TPO',
                    'unidade' => 'UI/mL',
                    'valor_referencia' => 'Conforme método e referência do laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 10000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Negativo;Positivo;Indeterminado'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DOS ANTICORPOS
        //==========================================

        [
            'nome' => 'Interpretação Imunológica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Presença de Autoanticorpos Tireoidianos',
                    'opcoes' => 'Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Título de Anticorpos',
                    'opcoes' => 'Negativo;Baixo Título;Título Elevado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Significado Imunológico',
                    'opcoes' => 'Sem Evidência de Autoimunidade;Sugestivo de Autoimunidade Tireoidiana;Necessita Correlação Clínica'
                ],

            ]

        ],


        //==========================================
        // DOENÇAS ASSOCIADAS
        //==========================================

        [
            'nome' => 'Doenças Tireoidianas Associadas',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Tireoidite de Hashimoto',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Doença de Graves',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Autoimunidade Tireoidiana',
                    'opcoes' => 'Ausente;Presente;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // CORRELAÇÃO COM FUNÇÃO TIREOIDIANA
        //==========================================

        [
            'nome' => 'Correlação com Função Tireoidiana',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'TSH Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'TSH',
                    'unidade' => 'µUI/mL',
                    'valor_referencia' => 'Conforme laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'T4 Livre Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Tireoidiana Atual',
                    'opcoes' => 'Eutireoidismo;Hipotireoidismo;Hipertireoidismo;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // OUTROS AUTOANTICORPOS
        //==========================================

        [
            'nome' => 'Avaliação de Outros Autoanticorpos',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpo Anti-Tireoglobulina (Anti-Tg)',
                    'opcoes' => 'Não Avaliado;Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Anticorpo Anti-Receptor de TSH (TRAb)',
                    'opcoes' => 'Não Avaliado;Negativo;Positivo'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO CLÍNICA
        //==========================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico de Doença Tireoidiana',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico Familiar de Doença Autoimune',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Hormônio Tireoidiano',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Gestação ou Planejamento Gestacional',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 9,

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
    'codigo' => 'BIOQ001',
    'nome' => 'Amilasemia - Amilase Sérica (Serum Amylase)',
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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Colorimétrico;Enzimático;Cinético;Imunoensaio;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DE AMILASE
        //==========================================

        [
            'nome' => 'Dosagem de Amilase Sérica',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Amilase Sérica',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Conforme método e laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Resultado Qualitativo',
                    'opcoes' => 'Normal;Reduzida;Elevada'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO DO RESULTADO
        //==========================================

        [
            'nome' => 'Interpretação da Amilase',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Dentro da Referência;Aumentada;Diminuída'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Grau de Elevação da Amilase',
                    'opcoes' => 'Normal;Leve Elevação;Elevação Moderada;Elevação Importante'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração Compatível com Envolvimento Pancreático',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva'
                ],

            ]

        ],


        //==========================================
        // AVALIAÇÃO PANCREÁTICA
        //==========================================

        [
            'nome' => 'Avaliação Pancreática',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Suspeita de Pancreatite Aguda',
                    'opcoes' => 'Não Informada;Não Sugestiva;Possível;Sugestiva'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Pancreatite Crônica',
                    'opcoes' => 'Não Sugestiva;Possível;Sugestiva;Não Avaliada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Acompanhamento de Doença Pancreática',
                    'opcoes' => 'Não Indicado;Indicado'
                ],

            ]

        ],


        //==========================================
        // AMILASE PANCREÁTICA E SALIVAR
        //==========================================

        [
            'nome' => 'Avaliação de Isoenzimas',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amilase Pancreática Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Amilase Pancreática',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Conforme laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Amilase Salivar Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Amilase Salivar',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Conforme laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 5000
                ],

            ]

        ],


        //==========================================
        // EXAMES ASSOCIADOS
        //==========================================

        [
            'nome' => 'Avaliação Bioquímica Complementar',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Lipase Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Função Hepática Avaliada',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Triglicerídeos Avaliados',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Ultrassonografia Abdominal Realizada',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // INFORMAÇÕES CLÍNICAS
        //==========================================

        [
            'nome' => 'Informações Clínicas',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Dor Abdominal',
                    'opcoes' => 'Não Informado;Ausente;Presente'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Náuseas ou Vômitos',
                    'opcoes' => 'Não Informado;Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Álcool',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico de Doença Pancreática',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 9,

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
    'codigo' => 'BIOQ002',
    'nome' => 'Colesterol Total (Total Cholesterol)',
    'categoria' => 'Laboratório / Bioquímica / Perfil Lipídico',

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
                    'nome' => 'Material Biológico',
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Método Utilizado',
                    'opcoes' => 'Enzimático Colorimétrico;Espectrofotometria;Automação Laboratorial;Outro'
                ],

            ]

        ],


        //==========================================
        // DOSAGEM DO COLESTEROL TOTAL
        //==========================================

        [
            'nome' => 'Dosagem de Colesterol Total',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol Total',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Valores interpretados conforme diretrizes cardiovasculares e risco individual',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Valor de Referência do Laboratório',
                    'tamanho_maximo' => 150
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do Resultado',
                    'opcoes' => 'Desejável;Limítrofe;Elevado;Muito Elevado'
                ],

            ]

        ],


        //==========================================
        // PERFIL LIPÍDICO ASSOCIADO
        //==========================================

        [
            'nome' => 'Perfil Lipídico Complementar',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'HDL Colesterol Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'HDL Colesterol',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Conforme sexo, idade e risco cardiovascular',
                    'valor_minimo' => 0,
                    'valor_maximo' => 300
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'LDL Colesterol Avaliado',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'LDL Colesterol',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Meta definida conforme risco cardiovascular',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Triglicerídeos Avaliados',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // INTERPRETAÇÃO CARDIOVASCULAR
        //==========================================

        [
            'nome' => 'Avaliação de Risco Cardiovascular',
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Risco Cardiovascular Associado ao Colesterol',
                    'opcoes' => 'Baixo;Intermediário;Alto;Muito Alto;Não Avaliado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Controle Lipídico',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Meta Terapêutica Atingida',
                    'opcoes' => 'Sim;Não;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // CONDIÇÕES CLÍNICAS ASSOCIADAS
        //==========================================

        [
            'nome' => 'Condições Clínicas Associadas',
            'ordem' => 5,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Dislipidemia Conhecida',
                    'opcoes' => 'Não Informada;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Doença Cardiovascular Prévia',
                    'opcoes' => 'Não Informada;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Diabetes Mellitus',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hipertensão Arterial',
                    'opcoes' => 'Não Informada;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Histórico Familiar de Doença Cardiovascular',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // CONDIÇÕES QUE ALTERAM COLESTEROL
        //==========================================

        [
            'nome' => 'Fatores Interferentes',
            'ordem' => 6,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Jejum Informado',
                    'opcoes' => 'Não Informado;Sim;Não'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo de Jejum',
                    'unidade' => 'horas',
                    'valor_referencia' => 'Conforme orientação do laboratório',
                    'valor_minimo' => 0,
                    'valor_maximo' => 48
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Uso de Medicamentos Hipolipemiantes',
                    'opcoes' => 'Não Informado;Não;Sim'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Alteração Alimentar Recente',
                    'opcoes' => 'Não Informada;Não;Sim'
                ],

            ]

        ],


        //==========================================
        // ACOMPANHAMENTO
        //==========================================

        [
            'nome' => 'Acompanhamento Evolutivo',
            'ordem' => 7,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Exame Anterior Disponível',
                    'opcoes' => 'Não;Sim'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol Total Anterior',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Comparação evolutiva',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Evolução do Resultado',
                    'opcoes' => 'Estável;Aumento;Redução;Não Avaliada'
                ],

            ]

        ],


        //==========================================
        // CONTROLE DA AMOSTRA
        //==========================================

        [
            'nome' => 'Controle da Amostra',
            'ordem' => 8,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Amostra Adequada',
                    'opcoes' => 'Sim;Não'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Hemólise ou Interferentes',
                    'opcoes' => 'Ausentes;Presentes'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Necessidade de Nova Coleta',
                    'opcoes' => 'Não;Sim'
                ],

            ]

        ],


        //==========================================
        // OBSERVAÇÕES
        //==========================================

        [
            'nome' => 'Observações',
            'ordem' => 9,

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
    'codigo' => 'LDL001',
    'nome' => 'Colesterol LDL',
    'categoria' => 'Bioquímica / Perfil Lipídico',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol LDL',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 400
                ],

            ]

        ],

        // =====================================
        // CLASSIFICAÇÃO CLÍNICA
        // =====================================

        [
            'nome' => 'Classificação',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do LDL',
                    'opcoes' => 'Ótimo (<100 mg/dL);Próximo do ótimo (100-129 mg/dL);Limítrofe alto (130-159 mg/dL);Alto (160-189 mg/dL);Muito alto (≥190 mg/dL)'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'HDL001',
    'nome' => 'Colesterol HDL',
    'categoria' => 'Bioquímica / Perfil Lipídico',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol HDL',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '≥ 40',
                    'valor_minimo' => 0,
                    'valor_maximo' => 150
                ],

            ]

        ],

        // =====================================
        // CLASSIFICAÇÃO CLÍNICA
        // =====================================

        [
            'nome' => 'Classificação',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação do HDL',
                    'opcoes' => 'Baixo (<40 mg/dL);Desejável (40-59 mg/dL);Protetor (≥60 mg/dL)'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'CRE001',
    'nome' => 'Creatinina',
    'categoria' => 'Bioquímica / Função Renal',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Creatinina Sérica',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '0,6 - 1,3',
                    'valor_minimo' => 0.2,
                    'valor_maximo' => 15
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
                    'nome' => 'Classificação da Creatinina',
                    'opcoes' => 'Baixa;Normal;Elevada'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'ALP001',
    'nome' => 'Fosfatase Alcalina',
    'categoria' => 'Bioquímica / Função Hepática',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Fosfatase Alcalina',
                    'unidade' => 'U/L',
                    'valor_referencia' => '40 - 130',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
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
                    'opcoes' => 'Baixa;Normal;Elevada'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'LIP001',
    'nome' => 'Lipidograma',
    'categoria' => 'Bioquímica / Perfil Lipídico',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADOS DO PERFIL LIPÍDICO
        // =====================================

        [
            'nome' => 'Resultados',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol Total',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 190',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol HDL',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '≥ 40',
                    'valor_minimo' => 0,
                    'valor_maximo' => 150
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol LDL',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 400
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol VLDL',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '5 - 40',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Triglicerídeos',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 150',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

            ]

        ],

        // =====================================
        // CLASSIFICAÇÃO CLÍNICA
        // =====================================

        [
            'nome' => 'Classificação',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Risco Cardiovascular',
                    'opcoes' => 'Baixo;Moderado;Alto;Muito Alto'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Perfil Lipídico Alterado',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];

$dadosExame = [
    'codigo' => 'GGT001',
    'nome' => 'Gama-G-Transpeptidase',
    'categoria' => 'Bioquímica / Função Hepática',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Gama Glutamil Transferase (GGT)',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Homens: 10 - 71 | Mulheres: 6 - 42',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
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
                    'opcoes' => 'Baixa;Normal;Elevada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Sugestivo de Colestase',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'LIP002',
    'nome' => 'Lipidograma',
    'categoria' => 'Bioquímica / Perfil Lipídico',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum de 12 horas',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // PERFIL LIPÍDICO
        // =====================================

        [
            'nome' => 'Perfil Lipídico',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol Total',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 190',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol HDL',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '≥ 40',
                    'valor_minimo' => 0,
                    'valor_maximo' => 150
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol LDL',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 100',
                    'valor_minimo' => 0,
                    'valor_maximo' => 400
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol VLDL',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '5 - 40',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Triglicerídeos',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 150',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1000
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Colesterol Não-HDL',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 130',
                    'valor_minimo' => 0,
                    'valor_maximo' => 500
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
                    'nome' => 'Classificação do Perfil Lipídico',
                    'opcoes' => 'Normal;Limítrofe;Alterado'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Risco Cardiovascular',
                    'opcoes' => 'Baixo;Moderado;Alto;Muito Alto'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Dislipidemia',
                    'texto_sim' => 'Presente',
                    'texto_nao' => 'Ausente'
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
                    'nome' => 'Observações Clínicas',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'PTS001',
    'nome' => 'Proteínas Totais',
    'categoria' => 'Bioquímica / Proteínas Séricas',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Proteínas Totais',
                    'unidade' => 'g/dL',
                    'valor_referencia' => '6,4 - 8,3',
                    'valor_minimo' => 3,
                    'valor_maximo' => 12
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
                    'opcoes' => 'Baixa;Normal;Elevada'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];

$dadosExame = [
    'codigo' => 'AST001',
    'nome' => 'GOT - Transaminase Glutâmico-Oxalacética',
    'categoria' => 'Bioquímica / Função Hepática',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'AST / GOT (Aspartato Aminotransferase)',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Até 40',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2000
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
                    'opcoes' => 'Normal;Levemente Elevada;Moderadamente Elevada;Muito Elevada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Alteração Hepática ou Muscular Sugestiva',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'ALT001',
    'nome' => 'GPT - Transaminase Glutâmico-Pirúvica',
    'categoria' => 'Bioquímica / Função Hepática',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'ALT / GPT (Alanina Aminotransferase)',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Até 41',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2000
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
                    'opcoes' => 'Normal;Levemente Elevada;Moderadamente Elevada;Muito Elevada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Possível Lesão Hepatocelular',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'GLI001',
    'nome' => 'Glicemia em Jejum',
    'categoria' => 'Bioquímica / Metabolismo da Glicose',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo de Jejum',
                    'unidade' => 'horas',
                    'valor_referencia' => '8 - 12',
                    'valor_minimo' => 0,
                    'valor_maximo' => 24
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Glicose Sanguínea em Jejum',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '70 - 99',
                    'valor_minimo' => 20,
                    'valor_maximo' => 700
                ],

            ]

        ],

        // =====================================
        // CLASSIFICAÇÃO GLICÊMICA
        // =====================================

        [
            'nome' => 'Classificação Glicêmica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação da Glicemia',
                    'opcoes' => 'Hipoglicemia (<70 mg/dL);Normal (70-99 mg/dL);Pré-diabetes (100-125 mg/dL);Diabetes (≥126 mg/dL)'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Resultado Alterado',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'GLIPP001',
    'nome' => 'Glicemia Pós-Prandial',
    'categoria' => 'Bioquímica / Metabolismo da Glicose',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo Após Refeição',
                    'unidade' => 'minutos',
                    'valor_referencia' => '120',
                    'valor_minimo' => 30,
                    'valor_maximo' => 360
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Glicemia Pós-Prandial',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 140',
                    'valor_minimo' => 20,
                    'valor_maximo' => 700
                ],

            ]

        ],

        // =====================================
        // CLASSIFICAÇÃO GLICÊMICA
        // =====================================

        [
            'nome' => 'Classificação Glicêmica',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Interpretação da Glicemia Pós-Prandial',
                    'opcoes' => 'Normal (<140 mg/dL);Tolerância Diminuída à Glicose (140-199 mg/dL);Diabetes Mellitus (≥200 mg/dL)'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Resultado Alterado',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'ALT002',
    'nome' => 'TGP - Transaminase Glutâmico-Pirúvica',
    'categoria' => 'Bioquímica / Função Hepática',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'ALT / TGP (Alanina Aminotransferase)',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Até 41',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2000
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
                    'opcoes' => 'Normal;Levemente Elevada;Moderadamente Elevada;Muito Elevada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Possível Lesão Hepática',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'AST002',
    'nome' => 'TGO - Transaminase Glutâmico-Oxalacética',
    'categoria' => 'Bioquímica / Função Hepática',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'AST / TGO (Aspartato Aminotransferase)',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Até 40',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2000
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
                    'opcoes' => 'Normal;Levemente Elevada;Moderadamente Elevada;Muito Elevada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Possível Alteração Hepática ou Muscular',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'TRI001',
    'nome' => 'Triglicerídeos',
    'categoria' => 'Bioquímica / Perfil Lipídico',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Triglicerídeos Séricos',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '< 150',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2000
                ],

            ]

        ],

        // =====================================
        // CLASSIFICAÇÃO
        // =====================================

        [
            'nome' => 'Classificação',
            'ordem' => 3,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação dos Triglicerídeos',
                    'opcoes' => 'Normal (<150 mg/dL);Limítrofe (150-199 mg/dL);Alto (200-499 mg/dL);Muito Alto (≥500 mg/dL)'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Hipertrigliceridemia',
                    'texto_sim' => 'Presente',
                    'texto_nao' => 'Ausente'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'URE001',
    'nome' => 'Ureia',
    'categoria' => 'Bioquímica / Função Renal',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Ureia Sérica',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '15 - 45',
                    'valor_minimo' => 5,
                    'valor_maximo' => 300
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Nitrogênio Ureico Sanguíneo (BUN)',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '7 - 20',
                    'valor_minimo' => 2,
                    'valor_maximo' => 150
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
                    'nome' => 'Classificação da Ureia',
                    'opcoes' => 'Baixa;Normal;Elevada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Possível Alteração da Função Renal',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'FOSAC001',
    'nome' => 'Fosfatase Ácida',
    'categoria' => 'Bioquímica / Enzimologia',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Fosfatase Ácida Total',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Até 6,5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Fosfatase Ácida Prostática',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Até 3,5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
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
                    'opcoes' => 'Normal;Elevada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Fosfatase Ácida Prostática Alterada',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'PAP001',
    'nome' => 'Fosfatase Prostática',
    'categoria' => 'Bioquímica / Marcadores Prostáticos',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Fosfatase Ácida Prostática (PAP)',
                    'unidade' => 'U/L',
                    'valor_referencia' => 'Até 3,5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 50
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
                    'opcoes' => 'Normal;Elevada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Marcador Prostático Alterado',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'BIL001',
    'nome' => 'Bilirrubina Total',
    'categoria' => 'Bioquímica / Função Hepática',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Bilirrubina Total',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => '0,3 - 1,2',
                    'valor_minimo' => 0,
                    'valor_maximo' => 30
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
                    'nome' => 'Classificação da Bilirrubina Total',
                    'opcoes' => 'Normal;Levemente Elevada;Moderadamente Elevada;Muito Elevada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Hiperbilirrubinemia',
                    'texto_sim' => 'Presente',
                    'texto_nao' => 'Ausente'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'BILD001',
    'nome' => 'Bilirrubina Direta',
    'categoria' => 'Bioquímica / Função Hepática',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Soro;Plasma'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Jejum Realizado',
                    'texto_sim' => 'Sim',
                    'texto_nao' => 'Não'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Analítico',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Bilirrubina Direta / Conjugada',
                    'unidade' => 'mg/dL',
                    'valor_referencia' => 'Até 0,3',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
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
                    'nome' => 'Classificação da Bilirrubina Direta',
                    'opcoes' => 'Normal;Elevada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Predomínio de Bilirrubina Direta',
                    'texto_sim' => 'Presente',
                    'texto_nao' => 'Ausente'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'TH001',
    'nome' => 'Tempo de Hemorragia',
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
                    'tipo' => 'data',
                    'nome' => 'Data da Coleta',
                    'permitir_futuro' => false,
                    'permitir_passado' => true
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Tipo de Amostra',
                    'opcoes' => 'Sangue Total'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Tempo de Hemorragia',
                    'unidade' => 'minutos',
                    'valor_referencia' => '2 - 7',
                    'valor_minimo' => 0,
                    'valor_maximo' => 60
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
                    'opcoes' => 'Normal;Prolongado'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Alteração da Hemostasia Primária',
                    'texto_sim' => 'Sugestiva',
                    'texto_nao' => 'Não Sugestiva'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'FAL001',
    'nome' => 'Teste de Falsiformação',
    'categoria' => 'Hematologia / Hemoglobinopatias',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Sangue Total'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO TESTE
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Teste de Falsiformação',
                    'opcoes' => 'Negativo;Positivo'
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
                    'nome' => 'Interpretação do Resultado',
                    'opcoes' => 'Ausência de hemoglobina S detectável;Presença de hemoglobina S sugestiva de traço falciforme;Presença de hemoglobina S sugestiva de doença falciforme'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Alteração Compatível com Hemoglobinopatia',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];



$dadosExame = [
    'codigo' => 'PLG001',
    'nome' => 'Pesquisa de Plasmodium - Gota Espessa',
    'categoria' => 'Parasitologia / Hematologia',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Sangue Total'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DA PESQUISA
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'lista',
                    'nome' => 'Pesquisa de Plasmodium na Gota Espessa',
                    'opcoes' => 'Negativo;Positivo'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Espécie de Plasmodium Identificada',
                    'opcoes' => 'Não identificado;Plasmodium falciparum;Plasmodium vivax;Plasmodium malariae;Plasmodium ovale;Plasmodium spp.'
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Parasitemia',
                    'unidade' => '%',
                    'valor_referencia' => 'Ausente',
                    'valor_minimo' => 0,
                    'valor_maximo' => 100
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
                    'opcoes' => 'Negativo para malária;Infecção por Plasmodium detectada'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Presença de Parasita',
                    'texto_sim' => 'Detectado',
                    'texto_nao' => 'Não Detectado'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'RET001',
    'nome' => 'Reticulócitos',
    'categoria' => 'Hematologia / Avaliação Eritrocitária',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Sangue Total com EDTA'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Contagem de Reticulócitos',
                    'unidade' => '%',
                    'valor_referencia' => '0,5 - 2,5',
                    'valor_minimo' => 0,
                    'valor_maximo' => 20
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Contagem Absoluta de Reticulócitos',
                    'unidade' => 'milhões/mm³',
                    'valor_referencia' => '0,02 - 0,10',
                    'valor_minimo' => 0,
                    'valor_maximo' => 1
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
                    'nome' => 'Classificação dos Reticulócitos',
                    'opcoes' => 'Baixo;Normal;Elevado'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Resposta Medular Aumentada',
                    'texto_sim' => 'Presente',
                    'texto_nao' => 'Ausente'
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
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];


$dadosExame = [
    'codigo' => 'HBP001',
    'nome' => 'Hemoglobina / Plag',
    'categoria' => 'Hematologia / Série Vermelha',

    'parametros' => [

        // =====================================
        // IDENTIFICAÇÃO DO EXAME
        // =====================================

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
                    'opcoes' => 'Sangue Total com EDTA'
                ],

                [
                    'tipo' => 'texto',
                    'nome' => 'Método Utilizado',
                    'tamanho_maximo' => 100
                ],

            ]

        ],

        // =====================================
        // RESULTADO DO EXAME
        // =====================================

        [
            'nome' => 'Resultado',
            'ordem' => 2,

            'subparametros' => [

                [
                    'tipo' => 'numero',
                    'nome' => 'Hemoglobina',
                    'unidade' => 'g/dL',
                    'valor_referencia' => 'Homens: 13,5 - 17,5 | Mulheres: 12 - 15,5',
                    'valor_minimo' => 3,
                    'valor_maximo' => 25
                ],

                [
                    'tipo' => 'numero',
                    'nome' => 'Plag / Plaquetas',
                    'unidade' => 'mil/mm³',
                    'valor_referencia' => '150 - 450',
                    'valor_minimo' => 0,
                    'valor_maximo' => 2000
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
                    'nome' => 'Classificação da Hemoglobina',
                    'opcoes' => 'Baixa;Normal;Elevada'
                ],

                [
                    'tipo' => 'lista',
                    'nome' => 'Classificação das Plaquetas',
                    'opcoes' => 'Trombocitopenia;Normal;Trombocitose'
                ],

                [
                    'tipo' => 'booleano',
                    'nome' => 'Alteração Hematológica Detectada',
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
            'ordem' => 4,

            'subparametros' => [

                [
                    'tipo' => 'textarea',
                    'nome' => 'Observações',
                    'tamanho_maximo' => 1000
                ],

            ]

        ],

    ]
];

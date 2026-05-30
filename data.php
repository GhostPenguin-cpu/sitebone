<?php
/**
 * data.php — Dados do produto e avaliações.
 */

$PRODUTO = [
    'titulo'        => 'Boné Brasil / U.S.A Country Trucker Americano Tela ORIGINAL No Limite Do Laço',
    'indicado'      => true,
    'preco'         => 52.90,
    'preco_antigo'  => 69.90,
    'desconto'      => 24,
    'parcelas'      => 10,
    'parcela_valor' => 6.07,
    'parcelado_max' => 12,
    'vendidos'      => '8mil+',
    'cupom_extra'   => 'Compre R$19 e ganhe 2% off',
    'rank'          => 'No. 1 Mais Vendidos em Boné Trucker Masculino Feminino',

    'frete'         => 11.16,
    'frete_antigo'  => 31.16,
    'frete_gratis'  => 'Frete grátis com cupom comprando R$200,00',

    // Galeria principal — 9 fotos no topo
    'galeria' => [
        'assets/img/produto1.jpg',
        'assets/img/produto2.jpg',
        'assets/img/produto3.jpg',
        'assets/img/produto4.jpg',
        'assets/img/produto5.jpg',
        'assets/img/produto6.jpg',
        'assets/img/produto7.jpg',
        'assets/img/produto8.jpg',
        'assets/img/produto9.jpg',
    ],

    // Variações de cor
    'cores' => [
        ['ref' => 'REF. 01', 'nome' => 'Caramelo',      'img' => 'assets/img/produto1.jpg', 'hot' => true],
        ['ref' => 'REF. 33', 'nome' => 'Preto',         'img' => 'assets/img/produto2.jpg'],
        ['ref' => 'REF. 12', 'nome' => 'Marrom',        'img' => 'assets/img/produto3.jpg'],
        ['ref' => 'REF. 45', 'nome' => 'Azul Marinho',  'img' => 'assets/img/produto4.jpg'],
        ['ref' => 'REF. 46', 'nome' => 'Azul Ciano',    'img' => 'assets/img/produto5.jpg'],
        ['ref' => 'REF. 60', 'nome' => 'Cinza',         'img' => 'assets/img/produto6.jpg'],
        ['ref' => 'REF. 78', 'nome' => 'Verde Militar', 'img' => 'assets/img/produto7.jpg'],
        ['ref' => 'REF. 90', 'nome' => 'Bege',          'img' => 'assets/img/produto8.jpg'],
        ['ref' => 'REF. 95', 'nome' => 'Extra',         'img' => 'assets/img/produto9.jpg'],
    ],

    'guia_tamanhos' => 'Tamanho único ajustável (snapback). Circunferência: 54–60 cm. '
                     . 'Regulagem traseira por fivela. Serve da maioria das cabeças adulto.',

    'especificacoes' => [
        'Marca'          => 'No Limite Do Laço',
        'Material'       => 'Algodão + tela respirável (trucker)',
        'Fechamento'     => 'Snapback ajustável',
        'Gênero'         => 'Masculino / Feminino (unissex)',
        'Estilo'         => 'Country / Americano',
        'Garantia'       => '7 dias contra defeitos',
    ],

    'descricao' => 'Boné trucker estilo country americano com tela respirável na parte '
                 . 'traseira e patch bordado frontal. Acabamento premium, costura reforçada '
                 . 'e regulagem ajustável. Ideal para o dia a dia, lida no campo e looks casuais.',
];

$AVALIACOES_RESUMO = [
    'nota'          => 4.9,
    'total'         => '4,4mil',
    'boa_qualidade' => 99,
];

$AVALIACOES = [

    // 1º COMENTÁRIO
    [
        'usuario'  => 'xo4th_tnwu',
        'data'     => '2026-08-04',
        'util'     => 196,
        'estrelas' => 5,
        'variacao' => 'REF. 33',
        'campos'   => [
            'Visual sugerido' => 'top acabamento e custura',
            'Custo-benefício' => 'preço justo mto top',
            'Adequado'        => 'encaixe meio fundo pois sou cabeçudo so a cor é escura pensei ser mais claro na foto',
        ],
        'midias' => [
            ['tipo' => 'video', 'src' => 'assets/img/rev1_d.mp4'],
            ['tipo' => 'foto',  'src' => 'assets/img/rev1_e.jpg'],
            ['tipo' => 'foto',  'src' => 'assets/img/rev1_f.jpg'],
            ['tipo' => 'foto',  'src' => 'assets/img/rev1_g.jpg'],
            ['tipo' => 'foto',  'src' => 'assets/img/rev1_h.jpg'],
        ],
    ],

    // 2º COMENTÁRIO
    [
        'usuario'  => 'sebastian_wwf',
        'data'     => '2026-28-05',
        'util'     => 45,
        'estrelas' => 5,
        'variacao' => 'REF. 78',
        'campos'   => [
            'Adequado' => 'eu sempre fui exigente para boné mas esse é um dos melhores boné que eu já comprei ultimamente, '
                        . 'a qualidade é excelente e o caimento ficou perfeito na cabeça',
        ],
        'midias' => [
            ['tipo' => 'foto', 'src' => 'assets/img/rev2_a.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev2_b.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev2_c.jpg'],
        ],
    ],

    // 3º COMENTÁRIO
    [
        'usuario'  => 'maria_luiza_shop',
        'data'     => '2026-29-04',
        'util'     => 87,
        'estrelas' => 5,
        'variacao' => 'REF. 01',
        'campos'   => [
            'Visual sugerido' => 'muito bonito, cor fiel à foto',
            'Custo-benefício' => 'chegou rápido e a qualidade é ótima pelo preço',
            'Adequado'        => 'ajustou perfeitamente na cabeça, material bom',
        ],
        'midias' => [
            ['tipo' => 'foto', 'src' => 'assets/img/rev3_a.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev3_b.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev3_c.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev3_d.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev3_e.jpg'],
        ],
    ],

    // 4º COMENTÁRIO
    [
        'usuario'  => 'tiagodutramorais',
        'data'     => '2026-12-09',
        'util'     => 22,
        'estrelas' => 5,
        'variacao' => 'REF. 33',
        'campos'   => [
            'Custo-benefício' => 'Bonito o boné, veio em uma caixa oq evitou ser amassado no transporte, muito bonito, recomendo!',
        ],
        'midias' => [
            ['tipo' => 'foto', 'src' => 'assets/img/rev4_a.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev4_b.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev4_c.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev4_d.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev4_e.jpg'],
        ],
    ],

    // 5º COMENTÁRIO
    [
        'usuario'  => 'maurysantos205',
        'data'     => '2026-30-09',
        'util'     => 0,
        'estrelas' => 5,
        'variacao' => 'REF. 78',
        'campos'   => [
            'Custo-benefício' => 'preco bom pelo q entrega',
            'Adequado'        => 'formato perfeito',
        ],
        'midias' => [
            ['tipo' => 'foto', 'src' => 'assets/img/rev5_a.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev5_b.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev5_c.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev5_d.jpg'],
            ['tipo' => 'foto', 'src' => 'assets/img/rev5_e.jpg'],
        ],
    ],

];

/** Helper: formata preço no padrão R$ 0,00 */
function brl($v) {
    return 'R$' . number_format($v, 2, ',', '.');
}
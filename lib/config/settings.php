<?php
return [
    'frontend_product' => [
        'control_type' => waHtmlControl::RADIOGROUP,
        'value'        => 'block_aux',
        'title'        => /*_wp*/('Отображение на карточке товара'),
        'description'  => /*_wp*/('Выберите место, где счётчик просмотров будет показываться в стандартных точках шаблона. Выберите «Выключить», чтобы использовать хелпер для ручного размещения в шаблоне.'),
        'options'      => [
            ['value' => 'block_aux', 'title' => /*_wp*/('Боковая колонка (frontend_product.block_aux)')],
            ['value' => 'menu',      'title' => /*_wp*/('Область меню продукта (frontend_product.menu)')],
            ['value' => '0',         'title' => /*_wp*/('Выключить')],
        ],
    ]
];
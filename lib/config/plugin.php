<?php
return array(
    'name'     => 'Количество просмотров',
    'img'      => 'img/viewd.png',
    'version'  => '2.0.1',
    'vendor'   => '670917',
    'handlers' =>
        array(
            'backend_product'      => 'hookBackendProduct',
            'backend_prod_content' => 'hookBackendProdContent',
            'frontend_head'        => 'hookFrontendHeadProduct',
            'frontend_product'     => 'hookFrontendProduct'
        ),
    'frontend' => true,
);

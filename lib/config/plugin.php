<?php
return array(
    'name'     => 'Количество просмотров',
    'img'      => 'img/viewd.png',
    'version'  => '1.0.0',
    'vendor'   => '670917',
    'handlers' =>
        array(
            'backend_product'  => 'hookBackendProduct',
            'frontend_head'    => 'hookFrontendHeadProduct',
            'frontend_product' => 'hookFrontendProduct'
        ),
    'frontend' => true,
);

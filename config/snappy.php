<?php

return array(


    'pdf' => array(
        'enabled' => true,
//        'binary'  => '/usr/local/bin/wkhtmltopdf',
        'binary' => base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'),
        'timeout' => false,
        'options' => array(),
        'env' => array(),
    ),
    'image' => array(
        'enabled' => true,
//        'binary'  => '/usr/local/bin/wkhtmltoimage',
        'binary' => base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltoimage-amd64'),
        'timeout' => false,
        'options' => array(),
        'env' => array(),
    ),


);

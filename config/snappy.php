<?php

if (env('APP_ENV') == 'local'){
    return [
        'pdf' => array(
            'enabled' => true,
            'binary' => "/usr/local/bin/wkhtmltopdf",
            'timeout' => false,
            'options' => array(),
            'env' => array(),
        ),
        'image' => array(
            'enabled' => true,
            'binary' => "/usr/local/bin/wkhtmltoimage",
            'timeout' => false,
            'options' => array(),
            'env' => array(),
        ),
    ];
}else{
    return [
        'pdf' => array(
            'enabled' => true,
            'binary' => "/usr/bin/wkhtmltopdf",
            'timeout' => 300,
            'options' => array(),
            'env' => array(),
        ),
        'image' => array(
            'enabled' => true,
            'binary' => "/usr/bin/xvfb-run /usr/bin/wkhtmltoimage",
            'timeout' => 300,
            'options' => array(),
            'env' => array(),
        ),
    ];
}

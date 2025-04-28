<?php

return [
    'pdf' => array(
        'enabled' => true,
        'binary' => env('WKHTML_PDF_BINARY', '/usr/bin/xvfb-run -a /usr/bin/wkhtmltopdf'),
        // 'binary' => '/usr/local/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
        'env' => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary' => env('WKHTML_IMG_BINARY', '/usr/bin/xvfb-run -a /usr/bin/wkhtmltoimage'),
        // 'binary' => '/usr/local/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => array(),
        'env' => array(),
    ),
];

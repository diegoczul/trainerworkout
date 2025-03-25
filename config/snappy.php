<?php

return [
    'pdf' => array(
        'enabled' => true,
        'binary' => env('WKHTML_PDF_BINARY', '/usr/bin/xvfb-run -a /usr/bin/wkhtmltopdf'),
        'timeout' => false,
        'options' => array(),
        'env' => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary' => env('WKHTML_IMG_BINARY', '/usr/bin/xvfb-run -a /usr/bin/wkhtmltoimage'),
        'timeout' => false,
        'options' => array(),
        'env' => array(),
    ),
];

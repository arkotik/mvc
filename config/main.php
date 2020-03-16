<?php
return [
    'env' => ENV_DEV,
    'debug' => true,
    'routes' => [
        '<controller>/<action>' => '<controller>/<action>',
        '<controller>s' => '<controller>/index',
        '<controller>/<action>/<id>' => '<controller>/<action>',
    ]
];
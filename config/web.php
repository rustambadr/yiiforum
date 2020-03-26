<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'baseUrl'=> '',
            'cookieValidationKey' => 'BXmVyCGV23Gf1EhpXSeEA4dW8Y2YhBIA',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'functions' => [
            'class' => 'app\components\Functions'
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
          'enablePrettyUrl' => true,
          'showScriptName' => false,
          'enableStrictParsing' => true,
            'rules' => [
              '/' => 'site/index',

              [
                  'pattern' => 'category/edit/<id:\d+>',
                  'route' => 'category/edit',
                  'defaults' => ['id' => 0],
              ],
              'category/delete/<id:\d+>' => 'category/delete',
              'category/main' => 'category/main',
              'category/<alias:\w+>' => 'category/index',

              [
                  'pattern' => 'thread/edit/<id:\d+>',
                  'route' => 'thread/edit',
                  'defaults' => ['id' => 0],
              ],
              [
                  'pattern' => 'comment/edit/<id:\d+>',
                  'route' => 'thread/commentedit',
                  'defaults' => ['id' => 0],
              ],
              'thread/image-upload' => 'thread/image-upload',
              'thread/list' => 'thread/list',
              'thread/delete/<id:\d+>' => 'thread/delete',
              'thread/<alias:\w+>' => 'thread/index',

              [
                  'pattern' => 'page/edit/<id:\d+>',
                  'route' => 'page/edit',
                  'defaults' => ['id' => 0],
              ],
              'page/list' => 'page/list',
              'page/delete/<id:\d+>' => 'page/delete',
              'page/<alias:\w+>' => 'page/index',

              'user/<id:\d+>' => 'user/index',

              '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

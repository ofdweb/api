<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
      
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error'],
                    'categories' => [
                        'yii\queue\*',
                        'app\models\*'
                     ],
                ],
            ],
        ],
      
        'db' => $db,
    ],
  
    'params' => $params,
    
    'controllerMap' => [
            'migrate' => [
                'class' => 'yii\console\controllers\MigrateController',
               // 'migrationPath' => '@app/migrations/app',
                //'migrationNamespaces' => ['app\migrations\app'],
               // 'migrationTable' => 'migration',
            ],
            'migrate-account' => [
                'class' => 'yii\console\controllers\MigrateController',
                'migrationNamespaces' => ['app\migrations\account'],
                //'migrationTable' => 'migration_app',
                'migrationPath' => '@app/migrations/account',
            ],
    ],
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
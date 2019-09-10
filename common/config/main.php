<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        "db" => [
            'dsn' => 'mysql:host=mysql;dbname=yii2advanced',
            'username' => 'yii2advanced',
            'password' => 'secret',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],


    ],
];

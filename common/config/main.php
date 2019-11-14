<?php

use common\RabbitMqService\RabbitMqService;

return [

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
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

        "rabbitMqService" => [
            "class" => RabbitMqService::class,
            "connection" => [
                "host" => "rbmq",
                "port" => "5672",
                "user" => "user",
                "password" => "my_password"
            ],

            "queue" => [
                "options" => [
                    "passive" => false,
                    "durable" => false,
                    "exclusive" => false,
                    "auto_delete" => false,
                ],
                "consume" => [
                    "consumer_tag" => "",
                    "no_local" => false,
                    "no_ack" => true,
                    "exclusive" => false,
                    "no_wait" => false,
                ],

                "publish" => [
                    "exchange" => ""
                ]
            ]
        ]


    ],
];

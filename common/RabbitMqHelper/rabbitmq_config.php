<?php
namespace common\RabbitMqHelper;

return [
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
?>
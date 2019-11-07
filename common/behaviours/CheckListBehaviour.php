<?php

namespace common\behaviours;

use common\models\CheckList;
use common\models\UserInfo;
use common\RabbitMqHelper\RabbitMqHelper;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Behavior;
use yii\helpers\Json;


class CheckListBehaviour extends Behavior
{

    public function events()
    {
        return [
            CheckList::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            CheckList::EVENT_BEFORE_DELETE => "beforeDelete",
            CheckList::EVENT_AFTER_INSERT => 'afterInsert',
        ];
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function beforeUpdate()
    {
        /** @var CheckList $cl */
        $cl = $this->owner;

        $channel = RabbitMqHelper::connect("cl_update_queue");
        $msg = new AMQPMessage(
            Json::encode(
                [
                    "cl" => $cl->attributes,
                    "old" => $cl->oldAttributes,
                    "dirty" => $cl->getDirtyAttributes()
                ]),
            [
                "delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]
        );
        $channel->publish($msg);
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function afterInsert()
    {
        /** @var CheckList $cl */
        $cl = $this->owner;
        $channel = RabbitMqHelper::connect("cl_insert_queue");
        $msg = new AMQPMessage(
            Json::encode(
                [
                    "cl" => $cl->attributes,
                ]),
            [
                "delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]
        );
        $channel->publish($msg,);
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function beforeDelete()
    {
        /** @var CheckList $cl */
        $cl = $this->owner;
        $channel = RabbitMqHelper::connect("cl_delete_queue");
        $msg = new AMQPMessage(
            Json::encode(
                [
                    "cl" => $cl->attributes,
                ]),
            [
                "delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]
        );
        $channel->publish($msg);

    }


}
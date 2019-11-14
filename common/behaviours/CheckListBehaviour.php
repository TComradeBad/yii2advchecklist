<?php

namespace common\behaviours;

use common\models\CheckList;
use common\RabbitMqService\RabbitMqService;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Behavior;
use yii\helpers\Json;


class CheckListBehaviour extends Behavior
{
    const QUEUE_FOR_UPDATE = "cl_update_queue";
    const QUEUE_FOR_INSERT = "cl_insert_queue";
    const QUEUE_FOR_DELETE = "cl_delete_queue";

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
        /** @var RabbitMqService $rb */
        $rb = \Yii::$app->rabbitMqService;
        $rb->sendMessageToQueue(self::QUEUE_FOR_UPDATE,
            Json::encode(
                [
                    "cl" => $cl->attributes,
                    "old" => $cl->oldAttributes,
                    "dirty" => $cl->getDirtyAttributes()
                ]
            )
        );

    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function afterInsert()
    {
        /** @var CheckList $cl */
        $cl = $this->owner;
        /** @var RabbitMqService $rb */
        $rb = \Yii::$app->rabbitMqService;
        $rb->sendMessageToQueue(self::QUEUE_FOR_INSERT,
            Json::encode(
                [
                    "cl" => $cl->attributes,
                ]
            )
        );

    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function beforeDelete()
    {
        /** @var CheckList $cl */
        $cl = $this->owner;
        /** @var RabbitMqService $rb */
        $rb = \Yii::$app->rabbitMqService;
        $rb->sendMessageToQueue(self::QUEUE_FOR_DELETE,
            Json::encode(
                [
                    "cl" => $cl->attributes,
                ]
            )
        );
    }


}
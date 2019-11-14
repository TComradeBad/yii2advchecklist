<?php


namespace common\behaviours;

use common\models\CheckListItem;
use common\models\UserInfo;
use common\RabbitMqService\RabbitMqService;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Behavior;
use yii\helpers\Json;


class CheckListItemBehaviour extends Behavior
{
    const QUEUE_FOR_UPDATE_ITEM = 'item_update_queue';

    public function events()
    {
        return [
            CheckListItem::EVENT_BEFORE_UPDATE => "beforeUpdate",
        ];
    }

    public function beforeUpdate()
    {
        /** @var CheckListItem $item */
        $item = $this->owner;
        /** @var RabbitMqService $rb */
        $rb = \Yii::$app->rabbitMqService;
        $rb->sendMessageToQueue(self::QUEUE_FOR_UPDATE_ITEM,
            Json::encode(
                [
                    "item" => $item->attributes,
                    "dirty" => $item->getDirtyAttributes(),
                    "old" => $item->oldAttributes,
                ]
            )
        );
    }
}
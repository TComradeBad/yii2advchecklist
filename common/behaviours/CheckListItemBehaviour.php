<?php


namespace common\behaviours;

use common\classes\ConsoleLog;
use common\models\CheckListItem;
use common\models\UserInfo;
use common\RabbitMqHelper\RabbitMqHelper;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Behavior;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use yii\helpers\Json;


class CheckListItemBehaviour extends Behavior
{

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

        $channel = RabbitMqHelper::connect('item_update_queue');
        $msg = new AMQPMessage(
            Json::encode(
                [
                    "item" => $item->attributes,
                    "dirty" => $item->getDirtyAttributes(),
                    "old" => $item->oldAttributes,
                ]),
            [
                "delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]
        );
        $channel->publish($msg);


    }
}
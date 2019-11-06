<?php


namespace common\behaviours;

use common\classes\ConsoleLog;
use common\models\CheckListItem;
use common\models\UserInfo;
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
        try {
            $connection = new AMQPStreamConnection('rbmq', "5672", 'user', 'my_password');
            $channel = $connection->channel();
            $channel->queue_declare('item_update_queue', false, false, false, false);
            $msg = new AMQPMessage(
                Json::encode(
                    [
                        "item" => $item->attributes,
                        "dirty" => $item->getDirtyAttributes(),
                        "old"=>$item->oldAttributes,
                    ]),
                [
                    "delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT
                ]
            );
            $channel->basic_publish($msg, '', 'item_update_queue');
        } catch (\Exception $exception) {
            var_dump($exception);
        }

    }
}
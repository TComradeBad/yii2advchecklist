<?php

namespace common\RabbitMqService;

use common\classes\ConsoleLog;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Component;
use yii\helpers\Json;

/**
 * Class RabbitMqService
 *
 * @property AMQPChannel $channel
 * @property string $queue
 * @property array $cfg
 * @package common\RabbitMqService
 */
class RabbitMqService extends Component
{
    public $channel = null;
    public $queue = null;
    public $cfg = null;

    /**
     * @param string $queue
     * @param string $config
     * @return RabbitMqService
     */
    public function connect(string $queue)
    {
        $cfg = $this->cfg;
        $cnct = $cfg["connection"];
        $q_opt = $cfg["queue"]["options"];
        $connection = new AMQPStreamConnection(
            $cnct["host"],
            $cnct["port"],
            $cnct["user"],
            $cnct["password"]
        );
        $channel = $connection->channel();
        $channel->queue_declare(
            $queue,
            $q_opt["passive"],
            $q_opt["durable"],
            $q_opt["exclusive"],
            $q_opt["auto_delete"]
        );
        $this->channel = $channel;
        $this->queue = $queue;
        return $this;

    }

    public function __construct($config = [])
    {
        $def_conf = self::getDefaultConfig();
        $def_conf["connection"] = array_merge($def_conf["connection"], (array)$config["connection"]);
        $def_conf["queue"]["options"] = array_merge($def_conf["queue"]["options"], (array)$config["queue"]["options"]);
        $def_conf["queue"]["consume"] = array_merge($def_conf["queue"]["consume"], (array)$config["queue"]["consume"]);
        $def_conf["queue"]["publish"] = array_merge($def_conf["queue"]["publish"], (array)$config["queue"]["publish"]);
        $this->cfg = $def_conf;
    }

    public function consume($func, string $queue = null)
    {
        $cfg = $this->cfg["queue"]["consume"];
        $q = $this->queue;
        if (isset($queue)) {
            $q = $queue;
        }
        return $this->channel->basic_consume(
            $q,
            $cfg["consumer_tag"],
            $cfg["no_local"],
            $cfg["no_ack"],
            $cfg["exclusive"],
            $cfg["no_wait"],
            $func
        );
    }

    public function sendMessageToQueue(string $queue, string $message, $delivery_mode = AMQPMessage::DELIVERY_MODE_PERSISTENT)
    {
        $this->connect($queue);
        $message = new AMQPMessage($message, [
            "delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);
        $this->publish($message);
    }

    public function publish(AMQPMessage $message, string $queue = null)
    {
        $cfg = $this->cfg["queue"]["publish"];
        $q = $this->queue;
        if (isset($queue)) {
            $q = $queue;
        }
        return $this->channel->basic_publish($message, $cfg["exchange"], $q);
    }

    public function wait()
    {
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    protected static function getDefaultConfig()
    {
        return [
            "connection" => [
                "host" => "rbmq",
                "port" => "5672",
                "user" => "user",
                "password" => "password"
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
        ];
    }
}
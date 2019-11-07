<?php

namespace common\RabbitMqHelper;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class RabbitMqHelper
 *
 * @property AMQPChannel $channel
 * @property string $queue
 * @property array $cfg
 * @package common\RabbitMqHelper
 */
class RabbitMqHelper
{
    public $channel = null;
    public $queue = null;
    public $cfg = null;

    /**
     * @param string $queue
     * @param string $config
     * @return RabbitMqHelper
     */
    public static function connect(string $queue, $config = __DIR__ . "/rabbitmq_config.php")
    {
        $cfg = include $config;
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
        return new RabbitMqHelper($channel, $queue, $cfg);

    }

    public function __construct(AMQPChannel $channel, string $queue, array $cfg)
    {
        $this->channel = $channel;
        $this->queue = $queue;
        $this->cfg = $cfg;
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
}
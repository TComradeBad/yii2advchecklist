<?php


namespace console\controllers;

use common\models\CheckList;
use common\models\UserInfo;
use common\RabbitMqHelper\RabbitMqHelper;
use common\classes\ConsoleLog;
use PhpAmqpLib\Message\AMQPMessage;
use yii\console\Controller;
use yii\helpers\Json;

class ClStatsController extends Controller
{

    /**
     * Listen queue and manage statistics for items
     * @throws \ErrorException
     */
    public function actionItemsUpdate()
    {
        $channel = RabbitMqHelper::connect("item_update_queue");
        $channel->consume(function ($msg) {
            $this->itemUpdateHandler($msg);
        });
        $channel->wait();
    }

    /**
     * Listen queues and manage ctl
     */
    public function actionChecklistsUpdate()
    {
        $channel = RabbitMqHelper::connect("cl_update_queue");
        $channel->consume(function ($msg) {
            $this->clUpdateHandler($msg);
        });
        $channel->wait();
    }

    /**
     * Work on checklists delete
     */
    public function actionChecklistsDelete()
    {
        $channel = RabbitMqHelper::connect("cl_delete_queue");
        $channel->consume(function ($msg) {
            $this->clDeleteHandler($msg);
        });
        $channel->wait();

    }

    /**
     * Work after creation of new checklist
     */
    public function actionChecklistsInsert()
    {
        $channel = RabbitMqHelper::connect("cl_insert_queue");
        $channel->consume(function ($msg){
            $this->clInsertHandler($msg);
        });
        $channel->wait();
    }

    /**
     * @param AMQPMessage $msq
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function clDeleteHandler(AMQPMessage $msq)
    {
        $data = Json::decode($msq->body);
        $cl_data = $data["cl"];

        $info = UserInfo::findOne(["user_id" => $cl_data["user_id"]]);

        if ($cl_data["done"]) {
            $info->cl_done_count--;
        } else {
            $info->cl_in_process_count--;
        }

        if ($cl_data["soft_delete"]) {
            $info->cl_sd_count--;
        } else {
            $info->cl_good_count--;
        }
        if ($cl_data["pushed_to_review"]) {
            $info->cl_on_review--;
        }
        $info->update();
    }

    /**
     * @param AMQPMessage $msq
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function clInsertHandler(AMQPMessage $msq)
    {
        $data = Json::decode($msq->body);
        $cl_data = $data["cl"];

        $cl = CheckList::findOne(["id" => $cl_data["id"]]);
        $info = UserInfo::findOne(["user_id" => $cl->user_id]);

        $info->cl_in_process_count++;
        $info->cl_good_count++;
        $info->update();
    }

    /**
     * @param AMQPMessage $msq
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function clUpdateHandler(AMQPMessage $msq)
    {
        $data = Json::decode($msq->body);
        $cl_data = $data["cl"];
        $dirty = $data["dirty"];
        $old = $data["old"];

        $info = UserInfo::findOne(["user_id" => $cl_data["user_id"]]);

        if (isset($dirty["done"])) {
            $info = $this->modifyUserInfoDoneAttribute($info, $cl_data, $old, $dirty);
        }

        if (isset($dirty["soft_delete"])) {
            $info = $this->modifyUserInfoSDAttribute($info, $old, $dirty);
        }

        if (isset($dirty["pushed_to_review"])) {
            $info = $this->modifyUserInfoPushedToReview($info, $old, $dirty);
        }
        $info->update();
    }

    public function itemUpdateHandler(AMQPMessage $msq)
    {
        $data = Json::decode($msq->body);
        $item_data = $data["item"];
        $dirty = $data["dirty"];
        $old = $data["old"];
        try {
            $cl = CheckList::findOne(["id" => $item_data["cl_id"]]);
            $info = UserInfo::findOne(["user_id" => $cl->user_id]);
            if (isset($dirty["done"])) {
                if ($old["done"] != $dirty["done"] && $dirty["done"]) {
                    $info->last_task_done_time = $item_data["updated_at"];

                }
            }
            $info->update();
        } catch (\Exception $exception) {
            ConsoleLog::log($exception->getMessage());
            ConsoleLog::log($exception->getTraceAsString());
        }
    }

    /**
     * @param $info UserInfo
     * @param $cl_data array
     * @param $old array
     * @param $dirty array
     * @return UserInfo
     */
    public function modifyUserInfoDoneAttribute($info, $cl_data, $old, $dirty)
    {

        if (!($old["done"] != $dirty["done"])) {
            return $info;
        }
        if ($dirty["done"]) {
            $info->last_cl_done_time = $cl_data["updated_at"];
            $info->cl_done_count++;
            $info->cl_in_process_count = ($info->cl_in_process_count > 0) ? $info->cl_in_process_count - 1 : $info->cl_in_process_count;
        } else {
            $info->cl_done_count = ($info->cl_done_count > 0) ? $info->cl_done_count - 1 : $info->cl_done_count;
            $info->cl_in_process_count++;
        }

        return $info;
    }

    /**
     * @param $info UserInfo
     * @param $old array
     * @param $dirty array
     * @return UserInfo
     */
    public function modifyUserInfoSDAttribute($info, $old, $dirty)
    {
        if (!($old["soft_delete"] != $dirty["soft_delete"])) {
            return $info;
        }
        if ($dirty["soft_delete"]) {
            $info->cl_sd_count++;
            $info->cl_good_count = ($info->cl_good_count > 0) ? $info->cl_good_count - 1 : $info->cl_good_count;
        } else {
            $info->cl_sd_count = ($info->cl_sd_count > 0) ? $info->cl_sd_count - 1 : $info->cl_sd_count;
            $info->cl_good_count++;
        }
        return $info;
    }

    /**
     * @param $info UserInfo
     * @param $old array
     * @param $dirty array
     * @return UserInfo
     */
    public function modifyUserInfoPushedToReview($info, $old, $dirty)
    {
        if (!($old["pushed_to_review"] != $dirty["pushed_to_review"])) {
            return $info;
        }
        if ($dirty["pushed_to_review"]) {
            $info->cl_on_review++;
        } else {
            $info->cl_on_review = ($info->cl_on_review > 0) ? $info->cl_on_review - 1 : $info->cl_on_review;
        }

        return $info;
    }

}
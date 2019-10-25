<?php

namespace common\behaviours;

use common\classes\ConsoleLog;
use common\models\CheckList;
use common\models\CheckListItem;
use common\models\UserInfo;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class CheckListBehaviour extends Behavior
{

    public function events()
    {
        return [
            CheckList::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            CheckList::EVENT_BEFORE_DELETE => "beforeDelete",
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
        $attr = $cl->getDirtyAttributes();
        $info = UserInfo::findOne(["user_id" => $cl->user_id]);

        if ($cl->isAttributeChanged("done")) {
            if ($cl->oldAttributes["done"] != $attr["done"]) {
                if ($attr["done"]) {
                    $info->last_cl_done_time = $cl->updated_at;
                    $info->cl_done_count++;
                    $info->cl_in_process_count--;
                } else {
                    $info->cl_done_count--;
                    $info->cl_in_process_count++;
                }
            }
        }


        if ($cl->isAttributeChanged("soft_delete")) {
            if ($cl->oldAttributes["soft_delete"] != $attr["soft_delete"]) {
                if ($attr["soft_delete"]) {
                    $info->cl_sd_count++;
                    $info->cl_good_count--;
                } else {
                    $info->cl_sd_count--;
                    $info->cl_good_count++;
                }
            }
        }


        if ($cl->isAttributeChanged("pushed_to_review")) {
            if ($cl->oldAttributes["pushed_to_review"] != $attr["pushed_to_review"]) {
                if ($attr["pushed_to_review"]) {
                    $info->cl_on_review++;
                } else {
                    $info->cl_on_review--;
                }
            }
        }
        $info->update();
    }

    public function beforeDelete()
    {
        /** @var CheckList $cl */
        $cl = $this->owner;
        $info = UserInfo::findOne(["user_id" => $cl->user_id]);

        if ($cl->done) {
            $info->cl_done_count--;
        } else {
            $info->cl_in_process_count--;
        }

        if ($cl->soft_delete) {
            $info->cl_sd_count--;
        } else {
            $info->cl_good_count--;
        }
        if ($cl->pushed_to_review) {
            $info->cl_on_review--;
        }
        $info->update();

    }
}
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
        $info = UserInfo::findOne(["user_id" => $cl->user_id]);

        if ($cl->isAttributeChanged("done")) {
            $info = $this->modifyUserInfoDoneAttribute($info, $cl);
        }

        if ($cl->isAttributeChanged("soft_delete")) {
            $info = $this->modifyUserInfoSDAttribute($info, $cl);
        }

        if ($cl->isAttributeChanged("pushed_to_review")) {
            $info = $this->modifyUserInfoPushedToReview($info, $cl);
        }
        $info->update();
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function afterInsert()
    {
        /** @var CheckList $cl */
        $cl = $this->owner;
        $info = UserInfo::findOne(["user_id" => $cl->user_id]);
        $info->cl_in_process_count++;
        $info->update();
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
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

    /**
     * @param $info UserInfo
     * @param $cl CheckList
     * @return UserInfo
     */
    public function modifyUserInfoDoneAttribute($info, $cl)
    {
        $attr = $cl->getDirtyAttributes();
        if ($cl->oldAttributes["done"] != $attr["done"]) {
            if ($attr["done"]) {
                $info->last_cl_done_time = $cl->updated_at;
                $info->cl_done_count++;
                $info->cl_in_process_count = ($info->cl_in_process_count > 0) ? $info->cl_in_process_count - 1 : $info->cl_in_process_count;
            } else {
                $info->cl_done_count = ($info->cl_done_count > 0) ? $info->cl_done_count - 1 : $info->cl_done_count;
                $info->cl_in_process_count++;
            }
        }
        return $info;
    }

    /**
     * @param $info UserInfo
     * @param $cl CheckList
     * @return UserInfo
     */
    public function modifyUserInfoSDAttribute($info, $cl)
    {
        $attr = $cl->getDirtyAttributes();
        if ($cl->oldAttributes["soft_delete"] != $attr["soft_delete"]) {
            if ($attr["soft_delete"]) {
                $info->cl_sd_count++;
                $info->cl_good_count = ($info->cl_good_count > 0) ? $info->cl_good_count - 1 : $info->cl_good_count;
            } else {
                $info->cl_sd_count = ($info->cl_sd_count > 0) ? $info->cl_sd_count - 1 : $info->cl_sd_count;
                $info->cl_good_count++;
            }
        }
        return $info;
    }

    /**
     * @param $info UserInfo
     * @param $cl CheckList
     * @return UserInfo
     */
    public function modifyUserInfoPushedToReview($info, $cl)
    {
        $attr = $cl->getDirtyAttributes();
        if ($cl->oldAttributes["pushed_to_review"] != $attr["pushed_to_review"]) {
            if ($attr["pushed_to_review"]) {
                $info->cl_on_review++;
            } else {
                $info->cl_on_review = ($info->cl_on_review > 0) ? $info->cl_on_review - 1 : $info->cl_on_review;
            }
        }
        return $info;
    }

}
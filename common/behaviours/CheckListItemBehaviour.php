<?php


namespace common\behaviours;


use common\classes\ConsoleLog;
use common\models\CheckListItem;
use common\models\UserInfo;
use Symfony\Component\Yaml\Tests\A;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class CheckListItemBehaviour extends Behavior
{

    public function events()
    {
        return [
            CheckListItem::EVENT_BEFORE_UPDATE => "beforeUpdate"
        ];
    }

    public function beforeUpdate()
    {
        /** @var CheckListItem $item */
        $item = $this->owner;
        $attr = $item->getDirtyAttributes();
        $info = UserInfo::findOne(["user_id" => $item->cl->user_id]);
        if ($item->isAttributeChanged("done")) {
            if ($item->oldAttributes["done"] != $attr["done"]) {
                if ($attr["done"]) {
                    $info->last_task_done_time = $item->updated_at;

                }
            }
        }
        $info->update();

    }
}
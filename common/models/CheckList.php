<?php

namespace common\models;

use common\classes\ConsoleLog;
use common\models\CheckListItem;
use frontend\assets\JsAsset;
use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\Exception;
use yii\helpers\Json;

/**
 * This is the model class for table "checklist".
 *
 * @property int $id
 * @property string $name
 * @property int $done
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 * @property boolean soft_delete
 * @property User $user
 * @property CheckListItem[] $checklistItems
 * @property Problem $problem
 * @property int $pushed_to_review
 */
class CheckList extends \yii\db\ActiveRecord
{
    /**
     * Event's names
     */
    const EVENT_CHECKLIST_DONE = "checklist done";

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['done', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'done' => 'Done',
            'pushed_to_review' => 'Pushed To Review',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return array|false
     */
    public function fields()
    {
        return [
            "id",
            "name",
            "done",
            "user_id",
            'created_at',
            "updated_at",
            "items" => function () {
                return $this->checklistItems;
            },
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChecklistItems()
    {
        return $this->hasMany(ChecklistItem::class, ['cl_id' => 'id']);
    }

    public function getProblem()
    {
        return $this->hasOne(Problem::class, ['cl_id' => 'id']);
    }

    public function behaviors()
    {
        $this->on(self::EVENT_CHECKLIST_DONE, [self::class, "updateClInfo"]);
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @param $data
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function saveItems($data)
    {
        if (!empty($data)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                CheckListItem::updateAll(["to_delete" => "1"], ["cl_id" => $this->id]);
                foreach ($data as $item) {
                    if ($item["item_name"] != "") {
                        if (isset($item["item_id"])) {
                            $cl_item = CheckListItem::findOne(["id" => $item["item_id"]]);
                            $cl_item->name = $item["item_name"];
                            $cl_item->to_delete = "0";
                            $cl_item->update();
                        } else {
                            $cl_item = new CheckListItem();
                            $cl_item->name = $item["item_name"];
                            $cl_item->cl_id = $this->id;
                            $cl_item->to_delete = "0";
                            $cl_item->save();
                        }
                    }
                }
                CheckListItem::deleteAll(["AND", "cl_id" => $this->id, ["to_delete" => "1"]]);
                $this->updateDoneStatus();
                $transaction->commit();
            } catch (Exception $e) {
                ConsoleLog::log($e);
                $transaction->rollBack();
            }
        }
    }

    public function updateDoneStatus()
    {
        $raw = CheckListItem::findAll(["done" => "0", "cl_id" => $this->id]);

        if (empty($raw)) {
            if ($this->done != "1") {
                $this->done = "1";
                $this->trigger(self::EVENT_CHECKLIST_DONE);
                $this->update();
            }
        } else {
            if ($this->done != "0") {
                $this->done = "0";
                $this->update();
            }
        }
    }

    /**
     * Event handlers
     */

    /**
     * @param $event Event
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function updateClInfo($event)
    {
        try {
            ///** @var $cl CheckList */
            $cl = $event->sender;
            $info = $cl->user->userInformation;
            $info->last_cl_done_time = $cl->updated_at;
            $info->update();
        } catch (\Exception $exception) {
            ConsoleLog::log($exception->getMessage());
        }
    }
}

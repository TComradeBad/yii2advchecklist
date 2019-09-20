<?php

namespace common\models;

use common\models\CheckListItem;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "checklist".
 *
 * @property int $id
 * @property string $name
 * @property int $done
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property CheckListItem[] $checklistItems
 */
class CheckList extends \yii\db\ActiveRecord
{
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
        return $this->hasMany(ChecklistItem::className(), ['cl_id' => 'id']);
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}

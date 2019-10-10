<?php

namespace common\models;

use common\models\CheckList;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "checklist_item".
 *
 * @property int $id
 * @property string $name
 * @property int $done
 * @property int $cl_id
 * @property int $created_at
 * @property int $updated_at
 * @property bool $to_delete
 * @property CheckList $cl
 */
class CheckListItem extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklist_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['done', 'cl_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['cl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Checklist::className(), 'targetAttribute' => ['cl_id' => 'id']],
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
            'cl_id' => 'Cl ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCl()
    {
        return $this->hasOne(Checklist::className(), ['id' => 'cl_id']);
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}

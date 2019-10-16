<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cl_problem".
 *
 * @property int $id
 * @property int $cl_id
 * @property string $description
 * @property int $updated_at
 * @property int $created_at
 *
 * @property Checklist $cl
 */
class Problem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cl_problem';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cl_id', 'updated_at', 'created_at'], 'integer'],
            [['description'], 'string'],
            [['updated_at', 'created_at'], 'required'],
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
            'cl_id' => 'Cl ID',
            'description' => 'Description',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
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

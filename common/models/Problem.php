<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cl_problem".
 *
 * @property int $id
 * @property int $cl_id
 * @property string $description
 * @property int $pushed_to_review
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
            [['cl_id', 'pushed_to_review', 'updated_at', 'created_at'], 'integer'],
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
            'pushed_to_review' => 'Pushed To Review',
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
}

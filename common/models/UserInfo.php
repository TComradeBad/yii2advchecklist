<?php

namespace common\models;

use common\behaviours\UserInfoBehaviour;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_information".
 *
 * @property int $id
 * @property int $user_id
 * @property string $last_cl_done_time
 * @property string $last_task_done_time
 * @property integer $cl_done_count
 * @property integer $cl_in_process_count
 * @property integer $cl_on_review
 * @property integer $cl_good_count
 * @property integer $cl_sd_count
 *
 * @property User $user
 */
class UserInfo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_information';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', "cl_done_count","cl_in_process_count","cl_on_review","cl_good_count","cl_sd_count"], 'integer'],
            [['last_cl_done_time', 'last_task_done_time'], 'safe'],
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
            'user_id' => 'User ID',
            'last_cl_done_time' => 'Last Cl Done Time',
            'last_task_done_time' => 'Last Task Done Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


}

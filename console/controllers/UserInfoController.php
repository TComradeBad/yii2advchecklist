<?php


namespace console\controllers;


use common\models\User;
use common\models\UserInfo;
use yii\console\Controller;

class UserInfoController extends Controller
{

    public function actionIndex()
    {
        $users = User::find()->all();
        foreach ($users as $user) {
            $info = new UserInfo();
            $info->user_id = $user->id;
            $info->save();
        }
    }

    public function actionDeleteInfo()
    {
        $users = User::find();
        foreach ($users as $user) {
            $user->userInformation->delete();
        }
    }
}
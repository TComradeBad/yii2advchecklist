<?php

namespace console\controllers;

use common\models\User;
use common\models\UserOptionForm;
use yii\console\Controller;

class AdminSeedController extends Controller
{
    public function actionCreate()
    {
        //super admin
        $user = new User();
        $user->username = "super_admin";
        $user->email = "admin111@mail.ru";
        $user->setPassword("admin_password");
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        $auth = \Yii::$app->authManager;
        $auth->assign($auth->getRole("super_admin"), $user->id);

        //admin
        $user = new User();
        $user->username = "admin";
        $user->email = "admin222@mail.ru";
        $user->setPassword("admin_password");
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        $auth = \Yii::$app->authManager;
        $auth->assign($auth->getRole("admin"), $user->id);

        //moderator
        $user = new User();
        $user->username = "moderator";
        $user->email = "admin333@mail.ru";
        $user->setPassword("admin_password");
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        $auth = \Yii::$app->authManager;
        $auth->assign($auth->getRole("moderator"), $user->id);

        UserInfoController::insertInTable();
    }

    public function actionRemove()
    {
        try {
            User::deleteAll(["username" => "admin"]);
            User::deleteAll(["username" => "super_admin"]);
            User::deleteAll(["username" => "moderator"]);
        } catch (\yii\db\Exception $exception) {
            echo $exception->getMessage();
        }
    }


}
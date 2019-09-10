<?php

namespace frontend\controllers;

use yii\filters\AccessControl;

class AdminController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            "access" => [
                "class" => AccessControl::class,
                "rules" => [
                    [
                        "allow" => true,
                        "roles" => ["admin","moderator","super-admin"]
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}

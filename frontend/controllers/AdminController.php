<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use common\models\User;

class AdminController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            "access" => [
                "class" => AccessControl::class,
                "only" => ["index", "ban"],
                "rules" => [
                    [
                        "allow" => true,
                        "actions" => ["index"],
                        "roles" => ["admin", "moderator", "super-admin"]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['ban'],

                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', ["user" => \Yii::$app->user->identity]);
    }

    public function actionBan($id = null)
    {
        if (\Yii::$app->request->post() && isset($id)) {

            $user = User::findOne($id);
            if (\Yii::$app->user->can("ban_users", ["affected_user" => $user])) {
                $user->banned = !$user->banned;
                $user->update();
            }
            return $this->redirect("/admin/ban");
        }
        return $this->render("ban");
    }

    public function actionDelete($id = null)
    {
        if (\Yii::$app->request->post() && isset($id)) {

            $user = User::findOne($id);
            if (\Yii::$app->user->can("delete_users", ["affected_user" => $user])) {
               $user->delete();
            }
            return $this->redirect("/admin/delete");
        }
        return $this->render("delete");
    }

}

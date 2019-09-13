<?php

namespace frontend\controllers;

use yii\db\Exception;
use yii\filters\AccessControl;
use common\models\User;

class AdminController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            "access" => [
                "class" => AccessControl::class,
                "only" => ["index", "ban", "delete", "set-roles", "set-count"],
                "rules" => [
                    [
                        "allow" => true,
                        "actions" => ["index"],
                        "roles" => ["admin", "moderator", "super-admin"]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['ban'],
                        "roles" => ["ban_users"]

                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        "roles" => ["delete_users"]

                    ],
                    [
                        'allow' => true,
                        'actions' => ['set-roles'],
                        "roles" => ["set_users_role"]
                    ],
                    [
                        "allow" => true,
                        "actions" => ["set-count"],
                        "roles" => ["set_cl_count", "set_cl_item_count"]
                    ]
                ]
            ]
        ];
    }

    /**
     * Admin page
     */
    public function actionIndex()
    {
        return $this->render('index', ["user" => \Yii::$app->user->identity]);
    }

    /**
     * Ban users
     */
    public function actionBan($id = null)
    {
        if (\Yii::$app->request->post() && isset($id)) {

            $user = User::findOne($id);
            if (\Yii::$app->user->can("manage_users", ["affected_user" => $user])) {
                $user->banned = !$user->banned;
                $user->update();
            }
            return $this->redirect("/admin/ban");
        }
        return $this->render("ban");
    }

    /**
     * Delete users
     */
    public function actionDelete($id = null)
    {
        if (\Yii::$app->request->post() && isset($id)) {

            $user = User::findOne($id);
            if (\Yii::$app->user->can("manage_users", ["affected_user" => $user])) {
                $user->delete();
            }
            return $this->redirect("/admin/delete");
        }
        return $this->render("delete");
    }

    /**
     * Manage user's roles
     */
    public function actionSetRoles($id = null, $upd_id = null)
    {
        $post = \Yii::$app->request->post();
        $auth_user = \Yii::$app->user;
        $auth = \Yii::$app->authManager;

        if ($post && isset($id)) {
            $user = User::findOne($id);
            if ($auth_user->can("manage_users", ["affected_user" => $user])) {
                return $this->render("set_user_role", [
                    "user" => $user,
                    "items" => $auth->getRoles()
                ]);
            }
        }
        if ($post && isset($upd_id)) {
            $user = User::findOne($upd_id);
            if ($auth_user->can("manage_users", ["affected_user" => $user])) {
                $auth->revokeAll($user->id);
                $auth->assign($auth->getRole($post["roles"]), $user->id);
                return $this->redirect("/admin/set-roles");
            }
        }
        return $this->render("set_roles");
    }

    /**
     * Set checklist and items count
     */
    public function actionSetCount($id = null, $upd_id = null)
    {
        $post = \Yii::$app->request->post();
        $auth_user = \Yii::$app->user;
        $auth = \Yii::$app->authManager;

        if (isset($id)) {
            $user = User::findOne($id);
            if ($auth_user->can("manage_users", ["affected_user" => $user])) {
                return $this->render("set_cl_count", [
                        "user" => $user]
                );
            }
        }

        if ($post && isset($upd_id)) {
            $user = User::findOne($upd_id);
            if ($auth_user->can("manage_users", ["affected_user" => $user])) {
                $user->user_cl_count = $post["user_cl_count"];
                $user->user_cl_item_count = $post["user_cl_item_count"];
                $user->update();
            }
            return $this->redirect(['admin/set-count', "id" => $user->id], 200);
        }
        return $this->render("set_count");
    }

    /**
     * View Users CheckList
     */
    public function actionViewCl($id = null, $cl_id = null)
    {

    }
}

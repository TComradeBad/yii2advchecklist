<?php

namespace frontend\controllers;

use Yii;
use common\classes\ConsoleLog;
use common\models\CheckList;
use common\models\CheckListItem;
use yii\base\Controller;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\debug\models\timeline\DataProvider;
use yii\filters\AccessControl;
use common\models\User;

class AdminController extends BaseController
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
                    ],
                    [
                        "allow" => true,
                        "actions" => ["view-user-info"],
                        "roles" => ["admin", "moderator", "super-admin"]
                    ],
                    [
                        "allow" => true,
                        "actions" => ["view-cl-info"],
                        "roles" => ["admin", "moderator", "super-admin"]
                    ],
                    [
                        "allow" => true,
                        "actions" => ["soft-delete-cl"],
                        "roles" => ["manage_users_cl"]
                    ],
                    [
                        "allow" => true,
                        "actions" => ["delete-cl"],
                        "roles" => ["manage_users_cl"]
                    ]
                ]
            ]
        ];
    }

    /**
     * Admin page
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            "query" => User::find(),
            "pagination" => [
                "pageSize" => 5
            ]
        ]);
        return $this->render('index', ["dataProvider" => $dataProvider]);
    }

    /**
     * Ban users
     * @param null $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionBan($id = null)
    {
        if (\Yii::$app->request->post() && isset($id)) {

            $user = User::findOne($id);
            if (\Yii::$app->user->can("manage_users", ["affected_user" => $user])) {
                $user->banned = !$user->banned;
                $user->update();
            }

        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Delete users
     * @param null $id
     * @param null $upd_id
     * @return string|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id = null, $upd_id = null)
    {
        $this->layout = false;
        if (isset($id)) {

            $user = User::findOne($id);
            if (\Yii::$app->user->can("manage_users", ["affected_user" => $user])) {
                if (isset($user)) {
                    return $this->render("delete", ["del_id" => $user->id]);
                } else {
                    return $this->render("delete", ["error" => "user not found "]);
                }
            }
        }

        if (isset($upd_id)) {
            $user = User::findOne($upd_id);
            if (\Yii::$app->user->can("manage_users", ["affected_user" => $user])) {
                if (isset($user)) {
                    $user->delete();
                    return $this->redirect(\Yii::$app->request->referrer);
                } else {
                    return $this->render("delete", ["error" => "user not found "]);
                }
            }
        }
        return $this->redirect(null, 404);

    }

    /**
     * Manage user's roles
     * @param null $id
     * @param null $upd_id
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionSetRoles($id = null, $upd_id = null)
    {
        $this->layout = false;
        $post = \Yii::$app->request->post();
        $auth_user = \Yii::$app->user;
        $auth = \Yii::$app->authManager;

        if (isset($id)) {
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
                return $this->redirect(\Yii::$app->request->referrer);
            }
        }
        return $this->redirect(null, 404);
    }

    /**
     * Set checklist and items count
     * @param null $id
     * @param null $upd_id
     * @return string|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionSetCount($id = null, $upd_id = null)
    {
        $this->layout = false;
        $post = \Yii::$app->request->post();
        $auth_user = \Yii::$app->user;

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
            return $this->redirect(\Yii::$app->request->referrer);
        }
        return $this->redirect(null, 404);;
    }

    /**
     * View Users CheckList
     * @param null $id
     * @return string|\yii\web\Response
     */
    public function actionViewUserInfo($id = null, $cl_id = null)
    {
        $layout = $this->layout;
        $this->layout = false;
        if (isset($id)) {
            $user = User::findIdentity($id);
            $dataProvider = new ActiveDataProvider([
                "query" => CheckList::find()->where(["user_id" => $id]),
                "pagination" => [
                    "pageSize" => 10
                ]
            ]);
            if (isset($cl_id)) {
                $cl = CheckList::findOne(["id" => $cl_id]);
                $dataProvider = new ActiveDataProvider([
                    "query" => CheckListItem::find()->where(["cl_id" => $cl_id]),
                    "sort" => ["attributes" => [
                        "id"
                    ]],
                    "pagination" => [
                        "pageSize" => 5
                    ]
                ]);

                return $this->renderAjax("checklist_items", ["dataProvider" => $dataProvider, "cl" => $cl, "user" => $user]);

            }
            $this->layout = $layout;
            return $this->render("user_info", ["dataProvider" => $dataProvider, "user" => $user]);
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }


    /**
     * Setting soft delete of checklist
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionSoftDeleteCl()
    {

        $data = \Yii::$app->request->post();
        if (!empty($data)) {
            $cl = CheckList::findOne(["id" => $data["cl_id"]]);
            if (Yii::$app->user->can("manage_users", ["affected_user" => $cl->user]) or
                Yii::$app->user->can("cl_owner", ["checklist" => $cl])) {
                $cl->soft_delete = ($data["value"] === "true") ? "1" : "0";
                $cl->update();
                return $this->redirect(null, "200");
            }

        }
        return $this->redirect(null, "400");
    }

    /**
     * Delete user cl
     * @param null $id
     * @param null $del_id
     * @return string|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteCl($id = null, $del_id = null)
    {
        $this->layout = false;
        if (isset($del_id)) {
            $cl = CheckList::findone(["id" => $del_id]);
            if (Yii::$app->user->can("manage_users", ["affected_user" => $cl->user]) or
                Yii::$app->user->can("cl_owner", ["checklist" => $cl])) {
                $cl->delete();
                return $this->redirect(\Yii::$app->request->referrer);
            }
            return $this->redirect(null, 400);
        }
        return $this->render("delete_cl", ["del_id" => $id]);

    }
}

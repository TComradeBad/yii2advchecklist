<?php

namespace frontend\controllers;

use common\models\Problem;
use common\models\UserInfo;
use Yii;
use common\classes\ConsoleLog;
use common\models\CheckList;
use common\models\CheckListItem;
use yii\base\Controller;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\Query;
use yii\db\StaleObjectException;
use yii\db\Transaction;
use yii\debug\models\timeline\DataProvider;
use yii\filters\AccessControl;
use common\models\User;
use yii\helpers\Json;
use yii\web\Response;

class AdminController extends BaseController
{
    public function behaviors()
    {
        return [
            "access" => [
                "class" => AccessControl::class,
                "only" => ["index", "ban", "delete", "set-roles", "set-count", "statistics"],
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
                    ],
                    [
                        "allow" => true,
                        "actions" => ["statistics"],
                        "roles" => ["moderator"]

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
     * @return Response
     * @throws \Throwable
     * @throws StaleObjectException
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
     * @return string|Response
     * @throws \Throwable
     * @throws StaleObjectException
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
     * @return string|Response
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
     * @return string|Response
     * @throws \Throwable
     * @throws StaleObjectException
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
     * @param null $id
     * @param null $cl_id
     * @return string|Response
     */
    public function actionViewUserInfo($id = null, $cl_id = null)
    {
        $layout = $this->layout;
        $this->layout = false;
        if (isset($id)) {
            $user = User::findIdentity($id);
            $dataProvider = new ActiveDataProvider([
                "query" => CheckList::find()->where(["user_id" => $id])->with("problem"),
                "pagination" => [
                    "pageSize" => 10
                ]
            ]);
            if (isset($cl_id)) {
                $cl = CheckList::find()->where(["id" => $cl_id])->with("problem")->one();
                $problem = $cl->problem->description;
                $dataProvider = new ActiveDataProvider([
                    "query" => CheckListItem::find()->where(["cl_id" => $cl_id]),
                    "sort" => ["attributes" => [
                        "id"
                    ]],
                    "pagination" => [
                        "pageSize" => 5
                    ]
                ]);

                return $this->renderAjax("checklist_items",
                    [
                        "dataProvider" => $dataProvider,
                        "cl" => $cl,
                        "user" => $user,
                        "cl_problem" => $problem]);

            }
            $this->layout = $layout;
            return $this->render("user_info", ["dataProvider" => $dataProvider, "user" => $user]);
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }


    /**
     * @param null $cl_id
     * @param null $user_id
     * @param false $unset_sd
     * @return string|Response
     * @throws \Throwable
     */
    public function actionSoftDeleteCl($cl_id = null, $user_id = null, $unset_sd = false)
    {
        $data = \Yii::$app->request->post();
        if (!empty($data)) {
            $tr = Yii::$app->db->beginTransaction();

            try {
                $cl = CheckList::find()->where(["id" => $data["cl_id"]])->with("problem")->one();
                if (Yii::$app->user->can("manage_users", ["affected_user" => $cl->user]) or
                    Yii::$app->user->can("cl_owner", ["checklist" => $cl])) {

                    if ($unset_sd) {
                        $cl->soft_delete = "0";
                        $problem = $cl->problem;
                        $cl->pushed_to_review = "0";
                        $problem->description = null;
                        $cl->update();
                        $problem->update();
                        $tr->commit();
                        return $this->redirect(null, 200);
                    }
                    if (!isset($cl->problem)) {
                        $problem = new Problem();
                        $problem->description = $data["description"];
                        $cl->pushed_to_review = "0";
                        $problem->link("cl", $cl);
                        $problem->save();
                    } else {
                        $problem = $cl->problem;
                        $problem->description = $data["description"];
                        $cl->pushed_to_review = "0";
                        $problem->update();
                    }
                    $cl->soft_delete = "1";

                    $cl->update();
                    $tr->commit();
                }
            } catch (\Exception $exception) {
                $tr->rollBack();
                ConsoleLog::log($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
            }
            return $this->redirect(null, "200");

        }
        $this->layout = false;
        return $this->render("soft_delete_set",
            [
                ///Params for url to reload pjax
                "user_id" => $user_id,
                "cl_id" => $cl_id
            ]);
    }

    /**
     * Delete user cl
     * @param null $id
     * @param null $del_id
     * @return string|Response
     * @throws \Throwable
     * @throws StaleObjectException
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

    /**
     * @param null $id
     * @return string
     */
    public function actionStatistics($id = null)
    {
        $layout = $this->layout;
        if (isset($id)) {

            $user = User::findOne(["id" => $id]);
            $this->layout = false;
            $query = new Query();
            $tr = Yii::$app->db->beginTransaction();
            try {
                //Done checklists
                $raw = $query->select(["count(*)"])->from(CheckList::tableName())->where(["done" => "1", "user_id" => $user->id])->one();
                $progress_data["cl_done_count"] = $raw["count(*)"];
                //Checklists in progress
                $raw = $query->select(["count(*)"])->from(CheckList::tableName())->where(["done" => "0", "user_id" => $user->id])->one();
                $progress_data["cl_in_process_count"] = $raw["count(*)"];
                //Soft deleted checklists
                $raw = $query->select(["count(*)"])->from(CheckList::tableName())->where(["soft_delete" => "1", "pushed_to_review" => "0", "user_id" => $user->id])->one();
                $progress_data["cl_sd"] = $raw["count(*)"];
                //Checklists on review
                $raw = $query->select(["count(*)"])->from(CheckList::tableName())->where(["pushed_to_review" => "1", "user_id" => $user->id])->one();
                $progress_data["cl_on_review"] = $raw["count(*)"];
                //Good checklists
                $raw = $query->select(["count(*)"])->from(CheckList::tableName())->where(["soft_delete" => "0", "user_id" => $user->id])->one();
                $progress_data["cl_good"] = $raw["count(*)"];
                //User name
                $progress_data["username"] = $user->username;
                //Time of last done checklist
                $progress_data["last_cl_done"] = Yii::$app->formatter->format($user->userInformation->last_cl_done_time, "datetime");
                //Time of last done task
                $progress_data["last_task_done"] = Yii::$app->formatter->format($user->userInformation->last_task_done_time, "datetime");
                $tr->commit();
            } catch (\Exception $exception) {
                ConsoleLog::log($exception->getMessage());
                $tr->rollBack();
                return $this->redirect(null, 404);
            }
            return $this->asJson($progress_data);
        }

        $dataProvider = new ActiveDataProvider([
            "query" => User::find(),
            "pagination" => [
                "pageSize" => 10
            ]
        ]);
        $this->layout = $layout;
        return $this->render("statistics", ["dataProvider" => $dataProvider]);
    }
}

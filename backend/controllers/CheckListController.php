<?php

namespace backend\controllers;

use common\models\CheckList;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\Response;

class CheckListController extends ActiveController
{
    public $modelClass = CheckList::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions["index"]["prepareDataProvider"] = [$this, 'indexDataProvider'];
        return $actions;
    }

    public function indexDataProvider()
    {
        $query = CheckList::find()->where(["user_id" => \Yii::$app->user->id]);
        return new ActiveDataProvider([
            "query" => $query
        ]);
    }

    public function beforeAction($action)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        $behaviors ["access"] = [
            "class" => AccessControl::class,
            "only" => ["create", "update", "index", "delete"],
            "rules" => [
                [
                    "actions" => ["create", "update", "index", 'delete'],
                    "roles" => ["@"],
                    "allow" => true,
                ],

            ]
        ];
        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        $data = \Yii::$app->getRequest()->getBodyParams();
        $query = \Yii::$app->getRequest()->getQueryParams();
        $user = User::findIdentity(\Yii::$app->user->id);
        $cl = CheckList::findOne(["id"=>$query["id"]]);


        switch ($action) {
            case "create":
                if ($user->user_cl_count <= count($user->checkLists)) {
                    throw new \yii\web\ForbiddenHttpException(sprintf('You reach your limit.', $action));
                }
                if ($user->id != $data["user_id"]) {
                    throw new \yii\web\ForbiddenHttpException(sprintf('Its not yours', $action));
                }
                break;
            case "update":
                if ($cl->user_id != $user->id or $data["user_id"] != $user->id) {
                    throw new \yii\web\ForbiddenHttpException(sprintf('Its not yours.', $action));
                }
                break;
            case "view":
                if ($cl->user_id != $user->id) {
                    throw new \yii\web\ForbiddenHttpException(sprintf('Its not yours.', $action));
                }
                break;
            case "delete":
                if ($cl->user_id != $user->id) {
                    throw new \yii\web\ForbiddenHttpException(sprintf('Its not yours.', $action));
                }
                break;
        }
    }


}

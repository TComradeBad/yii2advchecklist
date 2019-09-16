<?php


namespace backend\controllers;

use common\models\CheckList;
use common\models\CheckListItem;
use common\models\User;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\Response;

class CheckListItemController extends ActiveController
{
    public $modelClass = CheckListItem::class;

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
        $cl_item = CheckListItem::findOne(["id" => $query["id"]]);


        switch ($action) {
            case "create":
                $cl = CheckList::findOne(["id" => $data["cl_id"]]);
                if ($user->user_cl_item_count <= count($cl->checklistItems)) {
                    throw new \yii\web\ForbiddenHttpException(sprintf('You reach your limit.', $action));
                }
                if ($user->id != $cl->user_id) {
                    throw new \yii\web\ForbiddenHttpException(sprintf('Its not yours', $action));
                }
                break;
            case "update":
                $cl = CheckList::findOne(["id" => $cl_item->cl_id]);
                if ($cl->user_id != $user->id or $data["cl_id"]!=$cl_item->cl_id) {
                    throw new \yii\web\ForbiddenHttpException(sprintf('Its not yours.', $action));
                }
                break;
            case "view":
                $cl = CheckList::findOne(["id" => $cl_item->cl_id]);
                if ($cl->user_id != $user->id) {
                    throw new \yii\web\ForbiddenHttpException(sprintf('Its not yours.', $action));
                }
                break;
            case "delete":
                $cl = CheckList::findOne(["id" => $cl_item->cl_id]);
                if ($cl->user_id != $user->id) {
                    throw new \yii\web\ForbiddenHttpException(sprintf('Its not yours.', $action));
                }
                break;
        }
    }


}
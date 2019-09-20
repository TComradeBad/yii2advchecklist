<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\rest\ActiveController;
use yii\rest\Controller;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{
    public $modelClass = User::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions["index"]["prepareDataProvider"] = [$this, 'indexDataProvider'];


        return $actions;
    }

    public function indexDataProvider()
    {
        $query = User::find();
        return new ActiveDataProvider([
            "query" => $query
        ]);
    }


    public function checkAccess($action, $model = null, $params = [])
    {
        $data = Yii::$app->getRequest()->getBodyParams();
        $query = Yii::$app->getRequest()->getQueryParams();
        $user = Yii::$app->user;
        switch ($action){
            case "delete":
                if($user->id != $query["id"]){
                    throw new \yii\web\ForbiddenHttpException(sprintf('Its not you.', $action));
                }
                break;
            case"update":
                if($user->id != $query["id"]){
                    throw new \yii\web\ForbiddenHttpException(sprintf('Its not yous.', $action));
                }
                break;
        }
    }


}
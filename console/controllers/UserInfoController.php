<?php


namespace console\controllers;


use common\classes\ConsoleLog;
use common\models\CheckListItem;
use common\models\User;
use common\models\UserInfo;
use yii\console\Controller;
use yii\db\Query;

class UserInfoController extends Controller
{

    public function actionIndex()
    {
        $this->actionDeleteInfo();

        $sql = "INSERT INTO " . UserInfo::tableName() . " ( user_id )  SELECT id FROM " . User::tableName() . ";";

        \Yii::$app->db->createCommand($sql)->execute();;

    }

    public function actionDeleteInfo()
    {
        UserInfo::deleteAll();
    }
}
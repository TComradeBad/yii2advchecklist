<?php


namespace console\controllers;


use common\classes\ConsoleLog;
use common\models\CheckList;
use common\models\CheckListItem;
use common\models\User;
use common\models\UserInfo;
use yii\console\Controller;
use yii\db\Query;

class UserInfoController extends Controller
{

    /**
     * Fill user info for all users
     * @throws \yii\db\Exception
     */
    public static function insertInTable()
    {
        UserInfo::deleteAll();

        $query = (new Query())->select([
            "user_id" => "user.id",
            "cl_done_count" => "t.cl_done_count",
            "cl_in_process_count" => "t.cl_in_process_count",
            "cl_on_review" => "t.cl_on_review",
            "cl_good_count" => "t.cl_good_count",
            "cl_sd_count" => "t.cl_sd_count",

        ],)->from([User::tableName()])->leftJoin(["(" .
            (new Query())->select([
                "cl_done_count" => "SUM(`done`=1)",
                "cl_in_process_count" => "SUM(`done`=0)",
                "cl_on_review" => "SUM(`pushed_to_review`=1)",
                "cl_good_count" => "SUM(`soft_delete`=0)",
                "cl_sd_count" => "SUM(`soft_delete`=1)",
                "user_id"
            ])->from([CheckList::tableName()])->groupBy("user_id")->createCommand()->getRawSql() . " ) t"
        ], "user.id=t.user_id")->createCommand()->getRawSql();

        \Yii::$app->db->createCommand("INSERT INTO " . UserInfo::tableName() .
            "(user_id,cl_done_count,cl_in_process_count,cl_on_review,cl_good_count,cl_sd_count) " . $query . ";")->execute();


    }

    /**
     * Default Console action
     */
    public function actionIndex()
    {
        self::insertInTable();
    }

    /**
     * Clear table
     */
    public function actionDeleteInfo()
    {
        UserInfo::deleteAll();
    }
}
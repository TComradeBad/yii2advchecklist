<?php


namespace console\controllers;


use common\classes\ConsoleLog;
use yii\console\Controller;
use yii\helpers\Console;

class ClStatsController extends Controller
{
    public function actionIndex()
    {
        ConsoleLog::log("hello");
    }

}
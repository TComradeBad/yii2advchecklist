<?php
/* @var $this yii\web\View */
/* @var  $user User*/

use common\models\User;
use yii\bootstrap\Html;

?>

<h1>
    hello
  <? echo $user->username?>
    <? echo array_key_first((Yii::$app->authManager->getAssignments(Yii::$app->user->id)))?>
</h1>
<a href="<? echo Yii::$app->urlManager->createUrl(["admin/ban"])?>">
    <button>Ban Users</button>
</a><br><br>

<a href="<? echo Yii::$app->urlManager->createUrl(["admin/delete"])?>">
    <button>Delete Users</button>
</a><br><br>

<a href="<? echo Yii::$app->urlManager->createUrl(["admin/set-roles"])?>">
    <button>Set Users roles</button>
</a><br><br>

<a href="<? echo Yii::$app->urlManager->createUrl(["admin/set-count"])?>">
    <button>Set Checklists count</button>
</a><br><br>

<a href="<? echo Yii::$app->urlManager->createUrl(["admin/view-cl"])?>">
    <button>View Users Checklists</button>
</a><br><br>



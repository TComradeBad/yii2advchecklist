<?php
/* @var $this yii\web\View */

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

<a href="<? echo Yii::$app->urlManager->createUrl(["admin/roles"])?>">
    <button>Set Users roles</button>
</a><br><br>

<a href="<? echo Yii::$app->urlManager->createUrl(["admin/cl-count"])?>">
    <button>Set Users roles</button>
</a><br><br>

<a href="<? echo Yii::$app->urlManager->createUrl(["admin/manage-cl"])?>">
    <button>Manage Users Checklists</button>
</a><br><br>



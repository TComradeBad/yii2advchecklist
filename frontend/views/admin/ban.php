<?php
/* @var $this yii\web\View */
/* @var  $user \common\models\User */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\User;
use common\rbac\classes\RoleService as RL;

?>

<? foreach (\common\models\User::find()->all() as $user) : ?>
    <?= $user->username ?>
    <? if (Yii::$app->user->can("manage_users", ["affected_user" => $user])): ?>
        <? if ($user->banned): ?>
            <?= Html::beginForm(["admin/ban", "id" => $user->id], "POST", ['enctype' => 'multipart/form-data']) ?>
            <?= Html::submitButton("Mercy") ?>
            <?= Html::endForm() ?>
        <? else: ?>
            <?= Html::beginForm(["admin/ban", "id" => $user->id], "POST", ['enctype' => 'multipart/form-data']) ?>
            <?= Html::submitButton("Ban") ?>
            <?= Html::endForm() ?>
        <? endif ?>
    <? else: ?>
        <br>
    <? endif ?>


    <br>
<? endforeach; ?>

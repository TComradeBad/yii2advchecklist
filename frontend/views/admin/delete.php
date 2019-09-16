<?php

/* @var $this yii\web\View */

/* @var  $user \common\models\User */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\User;

?>

<? foreach (\common\models\User::find()->all() as $user) : ?>
    <?= $user->username ?>
    <? if (Yii::$app->user->can("manage_users", ["affected_user" => $user])): ?>
        <?= Html::beginForm(["admin/delete", "id" => $user->id], "POST", ['enctype' => 'multipart/form-data']) ?>
        <?= Html::submitButton("Delete") ?>
        <?= Html::endForm() ?>
    <? endif ?>
    <br>
<? endforeach; ?>

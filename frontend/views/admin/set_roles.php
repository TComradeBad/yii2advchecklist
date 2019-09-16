<?php

use common\models\User;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var  $user User */
?>

<? foreach (\common\models\User::find()->all() as $user) : ?>
    <?= $user->username ?>
    <? if (Yii::$app->user->can("manage_users", ["affected_user" => $user])): ?>
        <?= Html::beginForm(["admin/set-roles", "id" => $user->id], "POST", ['enctype' => 'multipart/form-data']) ?>
        <?= Html::submitButton("View") ?>
        <?= Html::endForm() ?>
    <? endif ?>
    <br>
<? endforeach; ?>

<?php

use common\models\User;
use yii\bootstrap\Html;
/* @var $this yii\web\View */
/* @var  $user User*/
?>
<? foreach (\common\models\User::find()->all() as $user) : ?>
    <?= $user->username ?>

    <?= Html::beginForm(["admin/set-count", "id" => $user->id], "POST", ['enctype' => 'multipart/form-data']) ?>
    <?= Html::submitButton("Set count") ?>
    <?= Html::endForm() ?>
    <br>
<? endforeach; ?>
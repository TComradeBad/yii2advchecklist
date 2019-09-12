<?php
/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\User;

?>

<? foreach (\common\models\User::find()->all() as $user) :?>
    <?=$user->username?>
    <? if($user->banned):?>
        <?= Html::beginForm(["admin/ban","id"=>$user->id],"POST",['enctype' => 'multipart/form-data'])?>
            <?=Html::submitButton("Mercy")?>
        <?=Html::endForm()?>
    <?else:?>
        <?= Html::beginForm(["admin/ban","id"=>$user->id],"POST",['enctype' => 'multipart/form-data'])?>
            <?=Html::submitButton("Ban")?>
        <?=Html::endForm()?>
    <? endif ?>


    <br>
<? endforeach;?>

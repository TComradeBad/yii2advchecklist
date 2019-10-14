<?php
/* @var $this View */
/* @var $user User */

/* @var $model UserOptionForm */

use common\models\User;
use common\models\UserOptionForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>

<script>
    document.getElementsByClassName("modal-header") [0].innerHTML = "<h3>Change Password</h3>";
</script>

<? $form = ActiveForm::begin(['options' => ['data-pjax' => true, "id" => "form"]]) ?>

<table class="table table-condensed">
    <tr class="danger">
        <td><?= $form->field($model, "old_password", ["enableAjaxValidation" => true])->passwordInput() ?></td>
    </tr>
    <tr class="danger">
        <td><?= $form->field($model, "new_password")->passwordInput() ?></td>
    </tr>
    <tr class="danger">
        <td><?= $form->field($model, "repeat_password")->passwordInput() ?></td>
    </tr>
</table>
<?= Html::submitButton("Accept", ["class" => " btn-warning", "id" => 'btn-submit']) ?>
<? ActiveForm::end() ?>









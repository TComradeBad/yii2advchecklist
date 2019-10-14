<?php
/* @var $user User */

use common\models\User;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

$this->registerJs("$('button.btnact') . click(function () { $('#modal') . modal('show'). find('#modalContent'). load($(this) . attr('value'));});", View::POS_READY);
$this->registerJs('setTimeout(function(){
$("#successMessage").fadeOut("fast")
},3000);');
?>

<? Modal::begin([
    "id" => "modal",
    "size" => Modal::SIZE_LARGE,
]);
echo "<div id='modalContent'></div>";
Modal::end();
?>
<div class="p-3 mb-2 bg-info text-white text-center"><h3>User info</h3></div><br>
<table class="table table-bordered table-sm">
    <tr>
        <td class="success">User name</td>
        <td class="info"><?= Html::encode($user->username) ?></td>
    </tr>
    <tr>
        <td class="success">Email</td>
        <td class="info"><?= Html::encode($user->email) ?></td>

    <tr>
        <td class="success">Role</td>
        <td class="info"><?= Html::encode($user->primaryRole()) ?></td>
    </tr>
</table>

<div class="p-3 mb-2 bg-info text-white text-center"><h3>User options</h3></div><br>

<table class="table table-bordered table-sm">
    <tr>
        <td class="warning">Change Name</td>
        <td class="danger">
            <?= Html::button("Change Name", [
                "value" => Url::to(["change-name"]),
                "class" => "btnact btn-warning"
            ]); ?>
        </td>
    </tr>
    <tr>
        <td class="warning">Change Password</td>
        <td class="danger">
            <?= Html::button("Change password", [
                "value" => Url::to(["change-password"]),
                "class" => "btnact btn-warning"
            ]); ?>
        </td>
    </tr>
</table>

<? if (Yii::$app->session->hasFlash("success")): ?>
    <div id="successMessage" class="p-3 mb-2 bg-success text-red text-center">
        <h2>
            <?= Yii::$app->session->getFlash("success") ?>
        </h2>
    </div>
<? endif; ?>

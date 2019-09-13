<?php
/* @var $this yii\web\View */

/* @var  $user User */

use common\models\User;
use yii\bootstrap\Html;

?>

<h1>
    <?= $user->username ?><br>
    <?= "Max checklists count " . $user->user_cl_count ?><br>
    <?= "Max checklist's items count " . $user->user_cl_item_count ?><br>
</h1>

<?= Html::beginForm(["admin/set-count", "upd_id" => $user->id], "post") ?>
<?= Html::Input("number", "user_cl_count",10) ?>
<?= Html::Input("number", "user_cl_item_count",10) ?>
<?= Html::submitButton("Set") ?>
<? Html::endForm() ?>

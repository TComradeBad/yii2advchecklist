<?php
/* @var $this yii\web\View */

/* @var  $user User */

use common\models\User;
use yii\bootstrap\Html;

?>
<script>
    document.getElementsByClassName('modal-header')[0].innerHTML ='<h3>Set Checklists and items count</h3>';
</script>
<h3>
    <?= $user->username ?><br>
    <?= "Max checklists count " . $user->user_cl_count ?><br>
    <?= "Max checklist's items count " . $user->user_cl_item_count ?><br>
</h3>
<h2><br></h2>

<?= Html::beginForm(["admin/set-count", "upd_id" => $user->id], "post") ?>
<?= Html::Input("number", "user_cl_count",10) ?>
<?= Html::Input("number", "user_cl_item_count",10) ?>
<?= Html::submitButton("Set") ?>
<? Html::endForm() ?>

<h2><br></h2>
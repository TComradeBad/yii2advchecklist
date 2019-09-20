<?php
/* @var  $user \common\models\User */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\User;
use common\rbac\classes\RoleService as RL;

$roles = array();
$user_role = current(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));
foreach ($items as $item) {
    if (RL::Higher($user_role->name, $item->name))
        $roles [$item->name] = $item->name;
}
?>
<script>
    document.getElementsByClassName('modal-header')[0].innerHTML ='<h3>Set user Role</h3>';
</script>

<h3>
    Username: <?= $user->username ?><br>
    Role: <?=$user->primaryRole()?><br>
</h3>
<h2><br></h2>
<?= Html::beginForm(["admin/set-roles", "upd_id" => $user->id], "post") ?>
<?= Html::dropDownList("roles", "moderator", $roles) ?>
<?= Html::submitButton("Save") ?>
<?= Html::endForm() ?>

<h2><br></h2>
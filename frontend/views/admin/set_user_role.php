<?php
/* @var $this yii\web\View */
/* @var  $user \common\models\User */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\User;
use common\rbac\classes\RoleService as RL;

$roles = array();
$user_role = current(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));
foreach ($items as $item) {
    if (!RL::Higher($item->name, $user_role->name))
        $roles [$item->name] = $item->name;
}
?>

<h1>
    <?= $user->username ?>
</h1>
<?= Html::beginForm(["admin/set-roles", "upd_id" => $user->id], "post") ?>
<?= Html::dropDownList("roles", "moderator", $roles) ?>
<?= Html::submitButton("Save") ?>
<?= Html::endForm() ?>


<?php
/* @var $this yii\web\View */
/* @var  $user User */

/* @var $dataProvider */

use common\models\User;
use common\rbac\classes\RoleService as RL;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\Url;
use yii\web\View;
?>
<?
$this->registerJs("$('button.btnact') . click(function () { $('#modal') . modal('show'). find('#modalContent'). load($(this) . attr('value'));});", View::POS_READY);
?>

<?php

Modal::begin([
    "id" => "modal",
]);

echo "<div id='modalContent'></div>";
Modal::end();
?>

<div class="p-3 mb-2 bg-info text-white text-center"><h2>Admin Panel</h2></div><br>
<?= GridView::widget([
    "dataProvider" => $dataProvider,
    'columns' => [
        ["class" => 'yii\grid\SerialColumn'],
        [
            "class" => ActionColumn::class,
            "template" => "{view}",
            "buttons" => [
                "view" => function ($url, $user) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-screenshot"></span>',["view-user-info","id"=>$user->id]);
                }
            ]
        ],

        [
            'class' => DataColumn::class, // this line is optional
            'attribute' => 'username',
            'format' => 'text',
            'label' => 'Name',
        ],
        [
            'class' => DataColumn::class, // this line is optional
            "label" => "role",
            "value" => function ($user) {
                return $user->primaryRole();
            }
        ],
        [
            'class' => DataColumn::class, // this line is optional
            "label" => "Created at",
            "attribute" => "created_at",
            "format" => "datetime"
        ],
        [
            'class' => DataColumn::class, // this line is optional
            "label" => "Created at",
            "attribute" => "updated_at",
            "format" => "datetime"
        ],
        [
            "label" => "banned",
            "format" => "raw",
            "value" => function ($user) {
                $auth_user = Yii::$app->user;

                if (!$auth_user->can("manage_users", ["affected_user" => $user]) or
                    !$auth_user->can("ban_users")) {
                    return "Unable";
                }
                if ($user->banned) {
                    return
                        Html::beginForm(["admin/ban", "id" => $user->id], "POST", ['enctype' => 'multipart/form-data']) .
                        Html::submitButton("Mercy") .
                        Html::endForm();
                } else {
                    return
                        Html::beginForm(["admin/ban", "id" => $user->id], "POST", ['enctype' => 'multipart/form-data']) .
                        Html::submitButton("ban") .
                        Html::endForm();
                }
            }
        ],
        [
            "label" => "Set-role",
            "format" => "raw",
            "value" => function ($user) {
                $auth_user = Yii::$app->user;
                if (!$auth_user->can("manage_users", ["affected_user" => $user]) or
                    !$auth_user->can("set_users_role")) {
                    return "Unable";
                }
                return Html::button("set roles", [
                    "value" => Url::to(["set-roles", "id" => $user->id]),
                    "class" => "btnact"
                ]);
            }
        ],
        [
            "label" => "set cl count",
            "format" => "raw",
            "value" => function ($user) {
                $auth_user = Yii::$app->user;
                if (!$auth_user->can("manage_users", ["affected_user" => $user]) or
                    !$auth_user->can("set_cl_count") or
                    !$auth_user->can("set_cl_item_count")) {
                    return "Unable";
                }
                return Html::button("set-count", [
                    "value" => Url::to(["set-count", "id" => $user->id]),
                    "class" => "btnact"
                ]);
            }
        ],
        [
            "label" => "Delete",
            "format" => "raw",
            "value" => function ($user) {
                $auth_user = Yii::$app->user;
                if (!$auth_user->can("manage_users", ["affected_user" => $user]) or
                    !$auth_user->can("delete_users")) {
                    return "Unable";
                }
                return Html::button("Delete", [
                    "value" => Url::to(["delete", "id" => $user->id]),
                    "class" => "btnact"
                ]);
            }
        ]
    ]
]) ?>


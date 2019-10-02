<?php
/* @var $this yii\web\View */
/* @var  $user User */

/* @var $dataProvider */

use common\models\CheckList;
use common\models\User;
use yii\bootstrap\Modal;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

Modal::begin([
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


<div class="p-3 mb-2 bg-info text-white text-center"><h3>CheckLists (total <?= count($user->checkLists) ?>)</h3></div>
<br>
<? Pjax::begin(["id" => "grid_view"]) ?>
<? $this->registerJs("$('button.btnact') . click(function () { $('#modal') . modal('show'). find('#modalContent'). load($(this) . attr('value'));});", View::POS_READY); ?>
<?= GridView::widget([
    "dataProvider" => $dataProvider,
    'rowOptions' => function ($model) {
        if ($model->soft_delete) {
            return ["class" => "danger"];
        }
    },
    "columns" => [
        ["class" => SerialColumn::class],
        [
            "label" => "view",
            "format" => "raw",
            "value" => function ($cl) use ($user) {
                return \yii\bootstrap\Html::button("view", [
                    "value" => Url::to(["view-user-info", "id" => $user->id, "cl_id" => $cl->id]),
                    "class" => "btnact btn-info"
                ]);
            }
        ],
        [
            "label" => "task name",
            "attribute" => "name"
        ],
        [
            'label' => "Complete",
            "format" => "raw",
            "value" => function ($cl) {
                if ($cl->done) {
                    return "<div class='text-success'>Done</div>";
                } else {
                    return "<div class='text-danger'>In Process</div>";
                }
            }
        ],
        [
            "label" => "Items count",
            "value" => function (CheckList $cl) {
                return count($cl->checklistItems);
            }
        ],
        [
            "class" => ActionColumn::class,
            "template" => "{delete}",
            "buttons" => [
                "delete" => function ($url, $cl) use ($user) {
                    if (Yii::$app->user->can("manage_users_cl") &&
                        (Yii::$app->user->can("manage_users", ["affected_user" => $user]) or
                        Yii::$app->user->can("cl_owner",["checklist"=>$cl]))) {
                        return \yii\bootstrap\Html::button("delete", [
                            "value" => Url::to(["admin/delete-cl", "id" => $cl->id]),
                            "class" => "btnact"
                        ]);
                    } else {
                        return "<p class='text-danger'>Unable</p>";
                    }

                }
            ]
        ],
    ]
]);
?>
<? Pjax::end() ?>

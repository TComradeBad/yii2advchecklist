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

?>
<?
$this->registerJs("$('button.btnact') . click(function () { $('#modal') . modal('show'). find('#modalContent'). load($(this) . attr('value'));});", View::POS_READY);
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
<?= GridView::widget([
    "dataProvider" => $dataProvider,
    "columns" => [
        ["class" => SerialColumn::class],
        [
            "label" => "view",
            "format" => "raw",
            "value" => function ($cl) {
                return \yii\bootstrap\Html::button("view", [
                    "value" => Url::to(["view-cl-info", "id" => $cl->id]),
                    "class" => "btnact"
                ]);
            }
        ],
        [
            "label" => "task name",
            "attribute" => "name"
        ],
        [
            'label' => "Complete",
            "attribute" => "done"
        ],
        [
            "label" => "Items count",
            "value" => function (CheckList $cl) {
                return count($cl->checklistItems);
            }
        ]
    ]
]);
?>

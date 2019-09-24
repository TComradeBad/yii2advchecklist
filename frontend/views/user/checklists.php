<?php
/* @var $this View */

/* @var $dataProvider ActiveDataProvider $ */

use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
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

<?= GridView::widget([
    "dataProvider" => $dataProvider,
    "columns" => [
        ["class" => SerialColumn::class],
        [
            "label" => "title",
            "attribute" => "name"
        ],
        [
            "label" => "owner",
            "value" => function ($model) {
                return $model->user->username;
            }
        ],
        [
            "label" => "Items count",

            "value" => function ($model) {
                return count($model->checklistItems);
            }
        ],
        [
            "class" => ActionColumn::class,
            "template" => "{view}",
            "buttons" => [
                "view" => function ($usl, $cl) {
                    return \yii\bootstrap\Html::button("view", [
                        "value" => Url::to(["user/checklists", "id" => $cl->id]),
                        "class" => "btnact"
                    ]);
                }

            ]
        ],
    ]
])

?>

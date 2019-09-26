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
use yii\widgets\Pjax;

Modal::begin([
    "id" => "modal",
    "size" => Modal::SIZE_LARGE,
]);
echo "<div id='modalContent'></div>";
Modal::end();
?>
<h2>
    <button class="btnact btn-success" value="<?= Url::to('/user/checklist-form') ?>">Add checklist</button>
</h2>
<div id="pjax_inside">


    <? Pjax::begin(["id" => "grid_view"]) ?>
    <? $this->registerJs("$('button.btnact') . click(function () { $('#modal') . modal('show'). find('#modalContent'). load($(this) . attr('value'));});", View::POS_READY); ?>
    <?= GridView::widget([
        "dataProvider" => $dataProvider,
        "columns" => [
            ["class" => SerialColumn::class],
            [
                "label" => "title",
                "attribute" => "name"
            ],
            [
                "label" => "done",
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
                    "view" => function ($url, $cl) {
                        return \yii\bootstrap\Html::button("view", [
                            "value" => Url::to(["user/my-cl", "id" => $cl->id]),
                            "class" => "btnact"
                        ]);
                    }

                ]
            ],
            [
                "class" => ActionColumn::class,
                "template" => "{delete}",
                "buttons" => [
                    "delete" => function ($url, $cl) {
                        return \yii\bootstrap\Html::button("delete", [
                            "value" => Url::to(["user/delete-cl", "id" => $cl->id]),
                            "class" => "btnact"
                        ]);

                    }

                ]
            ],
        ]
    ])

    ?>
    <? Pjax::end() ?>
</div>
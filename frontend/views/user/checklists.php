<?php
/* @var $this View */

/* @var $dataProvider ActiveDataProvider $ */

use common\models\CheckList;
use frontend\assets\AppAsset;
use frontend\assets\JsAsset;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\Pjax;

echo $this->registerJsFile("@web/js/cl_index_script.js", ["depends" => [JqueryAsset::class]]);
Modal::begin([
    "id" => "modal",
    "size" => Modal::SIZE_LARGE,
]);
echo "<div id='modalContent'></div>";
Modal::end();
?>


<div class="row">
    <div id="create_button_div" class="col-md-4" style="display: inline">
        <h2 style="display: inline">
            <button class="btnact btn-success" value="<?= Url::to('/user/checklist-form') ?>">Add checklist</button>
        </h2>

    </div>
    <div id="search_div" class="col-md-7 text-right form-group " style="display: inline">
        <input type="text" id="search_cl_input" style="display: inline" class="form-text">
        <button type="button" class="btn-success form-text" onclick="searchAction()">Search</button>
    </div>
</div>
<h6><br></h6>

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
                    "view" => function ($usl, $cl) {
                        /** @var  $cl CheckList */
                        $auth_user = Yii::$app->user;
                        if (isset($auth_user) && $cl->user_id == $auth_user->id) {
                            return \yii\bootstrap\Html::button("view", [
                                "value" => Url::to(["user/my-cl", "id" => $cl->id]),
                                "class" => "btnact"
                            ]);
                        } else {
                            return \yii\bootstrap\Html::button("view", [
                                "value" => Url::to(["user/checklists", "id" => $cl->id]),
                                "class" => "btnact"
                            ]);
                        }
                    }

                ]
            ],
        ]
    ])

    ?>
    <? Pjax::end() ?>
</div>


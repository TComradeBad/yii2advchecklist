<?php

use common\models\CheckList;
use common\models\User;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\web\View;

/* @var $this yii\web\View */
/* @var  $cl CheckList */
/* @var $cl_problem */

/* @var $dataProvider */
echo $this->registerJsFile("@web/js/my_cl_items_script.js", ["depends" => [JqueryAsset::class]]);
$this->beginPage();
$this->beginBody();
?>

    <article
            id="vars"
            data-cl-id='<?= $cl->id ?>'
        <?
        echo "data-csrf='" . Yii::$app->request->csrfParam . "'" . PHP_EOL;
        echo "data-token='" . Yii::$app->request->getCsrfToken() . "'" . PHP_EOL;
        ?>>

    </article>


    <div class="p-3 mb-2 bg-info text-white text-center"><h3><?= $cl->name ?></h3></div><br>

<?= GridView::widget([
    "dataProvider" => $dataProvider,
    "id" => "my_cl_grid",
    "columns" => [
        ["class" => SerialColumn::class],
        [
            "label" => "Task",
            "attribute" => "name"
        ],
        [
            "label" => "Complete",
            "format" => "raw",
            "value" => function ($cl_item) {
                if ($cl_item->done) {
                    return "<input type='checkbox' class='active-element'  onchange='submitChange(event,$cl_item->id)' value='true' name='items[$cl_item->id]' checked>";
                } else {
                    return "<input type='checkbox' class='active-element'  onchange='submitChange(event,$cl_item->id)' value='false' name='items[$cl_item->id]'>";
                }
            }
        ],
    ]
])
?>
<?
echo Html::button("Delete", [
    "value" => Url::to(["user/delete-cl", "id" => $cl->id]),
    "class" => "btnact btn-danger active-element",
    "id" => "delete_my_cl"
]);
echo Html::button("Change checklist ", [
    "value" => Url::to(['user/checklist-form', "upd_id" => $cl->id]),
    "class" => "btnact btn-warning active-element",
    "id" => "change_my_cl"
]); ?>
    <div id="cl_problem">
        <h6>
            <?php
            if (isset($cl_problem)) {
                echo '<div class="p-3 mb-2 bg-danger text-white text-center"><h3>Problem</h3></div>';
                echo '<div class="p-3 mb-2 bg-warning" style="background-color: #fff97a"><wbr></div>';
                echo '<div class="p-3 mb-2 bg-danger text-white" style="font-size:15px"><h3></h3>' . $cl_problem . '</div><br>';
            }
            ?>
        </h6>
    </div><br>

    <script>
        document.getElementsByClassName('modal-header')[0].innerHTML = '<h3>Items</h3>';
        $(document).ready(function () {
            InitMyClItem();
        });


    </script>

<?= $this->endBody() ?>
<?= $this->endPage() ?>
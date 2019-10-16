<?php

/* @var $this yii\web\View */

/* @var $dataProvider ActiveDataProvider */

use common\models\CheckList;
use common\models\User;
use phpnt\chartJS\ChartJs;
use phpnt\chartJS\ChartJSAsset;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

ChartJSAsset::register($this);
JqueryAsset::register($this);
$this->registerJsFile("@web/js/admin_stats.js", ["depends" => [JqueryAsset::class]]);

Modal::begin([
    "id" => "modal",
    "size" => Modal::SIZE_LARGE,
    "header" => '<div class=" text-center"
     style="background-color : #0048b5;
     color : white;
     font-weight : bold;
     font-size : 20px;
     text-shadow:5px 5px 15px #004602;
     box-shadow:inset 5px 5px 15px #004602;
     padding: 10px"><div id="user_name" style="display: inline">User</div> statistc
</div><br>',

    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]

]);
?>
<div id='modalContent'>

    <div class="chart-container" style="position: relative; height:100%; width:100%;">
        <table>
            <tr>
                <th><p class="text-center" style="background-color: #4aff51; color: white; text-shadow:5px 5px 15px #004602;
     box-shadow:inset 5px 5px 15px #004602;">User's checklist progress</p></th>
                <th>
                    <p class="text-center" style="background-color: #d1e32b; color: white; text-shadow:5px 5px 15px #004602;
     box-shadow:inset 5px 5px 15px #004602;">Soft Deleted checklists</p>
                </th>
            </tr>


            <tr>
                <td width="600px" height="30%">
                    <canvas id="ProgressChart"></canvas>
                </td>
                <td width="600px" height="30%">
                    <canvas id="SdChart"></canvas>
                </td>
            </tr>


        </table>
    </div>

</div>

<?php
Modal::end();
?>
<style>
    .modal-lg {
        width: 90%;
    }

    .close {
        height: 30px;
        width: 30px;
        font-size: 30px;
    }
</style>
<div class=" text-center"
     style="background-color : #6fff6a;
     color : white;
     font-weight : bold;
     font-size : 30px;
     text-shadow:5px 5px 15px #004602;
     box-shadow:inset 5px 5px 15px #004602;
     padding: 10px">User info
</div><br>

<?= GridView::widget([
    "dataProvider" => $dataProvider,
    'rowOptions' => function ($model) {
        /** @var  $model User */
        if ($model->banned) {

            return ["style" => "background-color:#ffa3a3;color:black"];
        }
        return ["style" => "background-color : #7dff9b"];
    },
    "columns" => [
        ["class" => SerialColumn::class],
        [
            "label" => "name",
            "attribute" => "username"
        ],
        [
            "label" => "last Update",
            "format" => "datetime",
            "attribute" => "updated_at"
        ],
        [
            "label" => "Registration date",
            "format" => "datetime",
            "attribute" => "created_at"
        ],
        [
            "label" => "view statistic",
            "format" => "raw",
            "value" => function ($model) {
                return Html::button("View", [
                    "class" => "btn-info view_stats",
                    "value" => Url::to(["/admin/statistics", "id" => $model->id])
                ]);
            }
        ]
    ]
]) ?>


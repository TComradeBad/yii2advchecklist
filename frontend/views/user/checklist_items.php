<?php

use common\models\CheckList;
use common\models\User;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var  $cl_name CheckList */

/* @var $dataProvider */
?>

<div class="p-3 mb-2 bg-info text-white text-center"><h3><?= $cl_name ?></h3></div><br>
<?= GridView::widget([
    "dataProvider" => $dataProvider,
    "columns" => [
        ["class" => SerialColumn::class],
        [
            "label" => "Task",
            "attribute" => "name"
        ],
        [
            "label" => "Complete",
            "format" => "raw",
            "value" => function ($cl_items) {
                if ($cl_items->done){
                    return "<div class='text-success'>Done</div>";
                }else{
                    return "<div class='text-danger'>In Process</div>";
                }
            }
        ],
    ]
])
?>
<script>
    $(".pagination li a").click(function(){
        $("#modalContent").load($(this).attr('href'));
        return false;
    });
</script>
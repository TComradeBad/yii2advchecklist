<?php

use common\models\CheckList;
use common\models\User;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var  $cl CheckList */
/* @var $user User */
/* @var $dataProvider */
$this->registerJsFile("@web/js/admin_cl_items.js", ["depends" => [JqueryAsset::class]]);
?>

<article
        id="vars"
        data-cl-id='<?= $cl->id ?>'
        data-user-id="<?= $user->id ?>"
    <?php
    echo "data-csrf='" . Yii::$app->request->csrfParam . "'" . PHP_EOL;
    echo "data-token='" . Yii::$app->request->getCsrfToken() . "'" . PHP_EOL;
    ?>>
</article>

<div class="p-3 mb-2 bg-info text-white text-center"><h3>Items</h3></div><br>
<?= GridView::widget([
    "dataProvider" => $dataProvider,
    "columns" => [
        ["class" => SerialColumn::class],
        [
            "label" => "Task",
            "attribute" => "name"
        ],
        [
            "label" => "Done",
            "format" => "raw",
            "value" => function ($cl_items) {
                if ($cl_items->done) {
                    return "<div class='text-success'>Done</div>";
                } else {
                    return "<div class='text-danger'>In Process</div>";
                }
            }
        ]
    ]
])
?>
<h4>
    <?
    if (Yii::$app->user->can("manage_users_cl") &&
        (Yii::$app->user->can("manage_users", ["affected_user" => $cl->user]) or
            Yii::$app->user->can("cl_owner", ["checklist" => $cl]))) {
        if ($cl->soft_delete) {
            echo "<button class='btn-success' value='false' onclick='setSoftDelete(event)'>Enable</button>";
        } else {
            echo "<button class='btn-warning' value='true' onclick='setSoftDelete(event)'>Soft Delete</button>";
        }
    } else {
        echo "<p class='text-danger'>Unable</p>";
    }
    ?>


</h4>


<script>
    $(".pagination li a").click(function () {
        $("#modalContent").load($(this).attr('href'));
        return false;
    });
</script>
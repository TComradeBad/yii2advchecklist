<?php

use common\models\CheckList;
use common\models\User;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var  $cl CheckList */

/* @var $dataProvider */

?>

<article
        id="vars"
        data-cl-id='<?= $cl->id ?>'>
</article>

<script>
    document.getElementsByClassName('modal-header')[0].innerHTML = '<h3>View Checklist</h3>';
    let formData = new FormData();
    let xrf = new XMLHttpRequest();
    let id = document.getElementById("vars").dataset.clId;
    ///Reload pjax on my_cl page
    xrf.onload = function () {
        if (xrf.status == "200") {
            $.pjax.reload({container: "#grid_view", timeout: false});
        }
    };


    function submitChange(event, item_id) {
        let id = document.getElementById("vars").dataset.clId;
        event.preventDefault();
        formData.set('<?=Yii::$app->request->csrfParam?>', '<?=Yii::$app->request->getCsrfToken()?>');
        formData.set("cl_id", id);
        formData.set("item_id", item_id);
        formData.set("value", event.target.checked);
        xrf.open("Post", "/user/my-cl-upd");
        xrf.send(formData);
    }
</script>

<div class="p-3 mb-2 bg-info text-white text-center"><h3><?= $cl->name ?></h3></div><br>

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
            "value" => function ($cl_item) {
                if ($cl_item->done) {
                    return "<input type='checkbox' onchange='submitChange(event,$cl_item->id)' value='true' name='items[$cl_item->id]' checked>";
                } else {
                    return "<input type='checkbox' onchange='submitChange(event,$cl_item->id)' value='false' name='items[$cl_item->id]'>";
                }
            }
        ],
    ]
])
?>
<? echo Html::button("Delete", [
    "value" => Url::to(["user/delete-cl", "id" => $cl->id]),
    "class" => "btnact btn-danger",
    "id" => "delete_my_cl"
])."  "
    .Html::button("Change checklist ", [
    "value" => Url::to(['user/checklist-form',"upd_id"=>$cl->id]),
    "class" => "btnact btn-warning",
    "id" => "change_cl"
]) ;?>

<script>
    $(".pagination li a").click(function () {
        $("#modalContent").load($(this).attr('href'));
        return false;
    });
</script>

<script>
    $(".btnact").click(function () {
        $("#modalContent").load($(this).attr('value'));
        return false;
    });
</script>
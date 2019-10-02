<?php

use common\models\CheckList;
use common\models\User;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var  $cl CheckList */
/* @var $user User */
/* @var $dataProvider */

?>

<article
        id="vars"
        data-cl-id='<?= $cl->id ?>'
        data-user-id="<?= $user->id ?>">
</article>
<script>
    let xrf = new XMLHttpRequest();
    let data = new FormData();
</script>


<script>
    xrf.onload = function () {
        let url = window.location.href;
        let user_id = document.getElementById("vars").dataset.userId;
        let id = document.getElementById("vars").dataset.clId;
        if (xrf.status == "200") {
            $.pjax.reload({container: "#grid_view"});
            $("#modalContent").load("/admin/view-user-info?id=" + user_id + "&cl_id=" + id);

        }
    };

    function setSoftDelete(event) {
        event.preventDefault();
        let id = document.getElementById("vars").dataset.clId;
        data.set('<?=Yii::$app->request->csrfParam?>', '<?=Yii::$app->request->getCsrfToken()?>');
        data.set("cl_id", id);
        data.set("value", event.target.value);
        xrf.open("post", "/admin/soft-delete-cl");
        xrf.send(data);
    }
</script>
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
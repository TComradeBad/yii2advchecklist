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
/* @var $cl_problem */
/* @var $dataProvider */
echo $this->registerJsFile("@web/js/admin_cl_items.js", ["depends" => [JqueryAsset::class]]);
echo $this->beginPage();
echo $this->beginBody();
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
    "id" => "user_cl_items",
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

            echo "<button class='btn-warning' data-status='false' value='" .
                Url::to(["/admin/soft-delete-cl", "cl_id" => $cl->id, "user_id" => $user->id]) .
                "'  id='soft_delete_button'>Soft Delete</button>";

        } else {
            echo "<p class='text-danger'>Unable</p>";
        }
        ?>

    </h4>
    <h6><br></h6>
    <div id="cl_problem">
        <h6>
            <?php
            if (isset($cl_problem)) {
                echo '<div class="p-3 mb-2 bg-danger text-white text-center"><h3>Problem</h3></div>';
                echo '<div class="p-3 mb-2 bg-warning" style="background-color: #fff97a"><wbr></div>';
                echo '<div class="p-3 mb-2 bg-danger text-white" style="font-size:15px"><h3></h3>' . $cl_problem . '</div><br>';
                echo '<h5>' .
                    Html::button("Remove soft delete",
                        [
                            "id" => "unset_sd_button",
                            "class" => "btn-info",
                            "value" => Url::to(["/admin/soft-delete-cl", "unset_sd" => true])
                        ]) .
                    '</h5>';
            }
            ?>
        </h6>
    </div><br>
    <div id="soft_delete_form"></div>

    <script>
        $(document).ready(function () {
            InitAdminClItems();
        });
    </script>

<?php
echo $this->endBody();
echo $this->endPage();
?>
<?php

use common\models\User;
use phpnt\chartJS\ChartJs;
use phpnt\chartJS\ChartJSAsset;
use yii\web\JqueryAsset;
use yii\web\View;

/** @var  $this View */
/** @var $cl_done_count integer */
/** @var $cl_in_process_count integer */
/** @var $progress_data array */

/** @var $user User */
JqueryAsset::register($this);
ChartJSAsset::register($this);
$this->beginPage();
$this->beginBody();
?>
<article id="vars"
         data-username="<?= $user->username ?>"></article>

<script>
    document.getElementById("user_name").innerHTML = document.getElementById("vars").dataset.username;
</script>


<?

if ($progress_data["cl_done_count"] != 0) {
    $data_pie["labels"][] = "Checklist done (" . $progress_data["cl_done_count"] . ")";
    $data_pie['datasets'][0]["data"][] = $progress_data["cl_done_count"];
    $data_pie['datasets'][0]['backgroundColor'][] = "#6df760";
    $data_pie['datasets'][0]["hoverBackgroundColor"][] = "#6df760";
}
if ($progress_data["cl_in_process_count"] != 0) {
    $data_pie["labels"][] = "Checklist in Process (" . $progress_data["cl_in_process_count"] . ")";
    $data_pie['datasets'][0]["data"][] = $progress_data["cl_in_process_count"];
    $data_pie['datasets'][0]['backgroundColor'][] = "#ff4f4f";
    $data_pie['datasets'][0]["hoverBackgroundColor"][] = "#ff4f4f";
}
if ($progress_data["cl_in_process_count"] == 0 and $progress_data["cl_done_count"] == 0) {
    $data_pie["labels"][] = "User dont have checklists";
    $data_pie['datasets'][0]["data"][] = 1;
    $data_pie['datasets'][0]['backgroundColor'][] = "#a89594";
    $data_pie['datasets'][0]["hoverBackgroundColor"][] = "#a89594";
}

echo ChartJs::widget([
    "type" => ChartJs::TYPE_PIE,
    "data" => $data_pie,
    "options" => [
        "responsive" => true,
        "maintainAspectRatio" => false,
        "legend" => [
            "labels" => [
                "fontSize" => 15
            ]
        ]
    ],
]);


?>


<?= $this->endBody() ?>
<?= $this->endPage() ?>



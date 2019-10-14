<?php

/* @var $this View */

/* @var $user User */

/* @var $cl CheckList */

use common\models\CheckList;
use frontend\assets\AppAsset;
use frontend\assets\JsAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\helpers\Json;

echo $this->registerJsFile("@web/js/cl_form_script.js");
$this->beginPage();
$this->beginBody();
?>
<article id="vars"
    <?
    if (isset($cl)) {
        echo "data-items='" . Json::Htmlencode($cl->checklistItems) . "'" . PHP_EOL;
        echo "data-cl-id='" . $cl->id . "'" . PHP_EOL;

    }
    echo "data-max='" . $user->user_cl_item_count . "'" . PHP_EOL;
    echo "data-csrf='" . Yii::$app->request->csrfParam . "'" . PHP_EOL;
    echo "data-token='" . Yii::$app->request->getCsrfToken() . "'" . PHP_EOL;

    ?>>
</article>


<?= Html::beginForm(Url::to(["/user/checklist-form", "upd_id" => $cl->id]), "post", ["id" => "create_form"]) ?>
<div class="p-3 mb-2 bg-info text-warning text-center"><h3>Checklist name</h3></div>
<?= Html::input("text", "name", $cl->name) ?>
<div class="p-3 mb-2 bg-info text-warning text-center"><h3>Checklist items</h3></div>
<div id="container">
</div>
<button type="button" class="btn-warning add-fields active-element">Add item</button>
<button type="button" class="btn-warning remove-fields active-element">Remove item</button>
<button type="button" class="btn-success submit-form active-element">Save</button>
<?= Html::endForm() ?>

<script>
    $(document).ready(function () {
        InitForm();
    })
</script>

<?= $this->endBody() ?>
<?= $this->endPage() ?>

<?php

/* @var $this View */

/* @var $user User */

use common\models\CheckList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\User;

?>
<script type='text/javascript'>
    var i = 0;
    var max = <?=$user->user_cl_item_count?>;

    function addFields() {
        b = i + 1;
        if (b <= max) {
            var container = document.getElementById("container");
            var input = document.createElement("input");
            input.type = "text";
            input.name = "items[" + i + "]";

            container.appendChild(document.createTextNode("Item " + b));
            container.appendChild(input);
            container.appendChild(document.createElement("br"));
            i++;
        }
    }

    function removeFields() {
        var container = document.getElementById("container");
        container.removeChild(container.lastChild);
        container.removeChild(container.lastChild);
        container.removeChild(container.lastChild);
        if (i > 0) {
            i--;
        }

    }
</script>
<?= Html::beginForm([Url::to("/user/checklist-form")]) ?>
<div class="p-3 mb-2 bg-info text-warning text-center"><h3>Checklist name</h3></div>
<?=Html::input("text","name")?>
<div class="p-3 mb-2 bg-info text-warning text-center"><h3>Checklist items</h3></div>
<div id="container">
</div>
<button type="button" class="btn-warning" onclick="addFields()">Add item</button>
<button type="button" class="btn-warning" onclick="removeFields()">Remove item</button>
<?= Html::submitButton() ?>
<?= Html::endForm() ?>


<?php

/* @var $this View */

/* @var $user User */

/* @var $cl CheckList */

use common\models\CheckList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\User;

?>
<article id="vars"
    <?
    if (isset($cl)){
        echo "data-items=".$cl->checklistItems;
    }


    ?>>
</article>

<script type='text/javascript'>
    var i = 0;
    var max = <?=$user->user_cl_item_count?>;
    document.getElementsByClassName('modal-header')[0].innerHTML = '<h3>Add checklist</h3>';

    function addFields(item = null) {
        b = i + 1;
        if (b <= max) {
            var container = document.getElementById("container");
            var input = document.createElement("input");
            input.type = "text";
            input.name = "items[" + i + "]";
            if (item != null) {
                input.value = item.name;
                input.dataset.id = item.id;
            }

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

    $(document).ready(function () {
        let items = document.getElementById("vars").dataset.items;
        alert(items);
        items.forEach(function (element) {
            alert(element);
        })
    });

</script>
<?= Html::beginForm(Url::to(["/user/checklist-form", "upd_id" => $cl->id]), "post", ["id" => "create_form"]) ?>
<div class="p-3 mb-2 bg-info text-warning text-center"><h3>Checklist name</h3></div>
<?= Html::input("text", "name", $cl->name) ?>
<div class="p-3 mb-2 bg-info text-warning text-center"><h3>Checklist items</h3></div>
<div id="container">
</div>
<button type="button" class="btn-warning" onclick="addFields()">Add item</button>
<button type="button" class="btn-warning" onclick="removeFields()">Remove item</button>
<?= Html::submitButton() ?>
<?= Html::endForm() ?>


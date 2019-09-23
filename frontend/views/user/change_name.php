<?php
/* @var $user User */

use common\models\User;
use yii\helpers\Html;


?>

<script>
    document.getElementsByClassName('modal-header')[0].innerHTML ='<h3>Change Name</h3>';
</script>
<?=Html::beginForm(["change-name","upd_id"=>$user->id])?>
<table class="table table-condensed">
    <tr class="warning"><td>Type your new name</td></tr>
    <tr class="danger"><td><?=Html::input("string","new_name")?></td></tr>

</table>
<?=Html::submitButton("Accept",["class"=>"btn-warning"])?>
<?Html::endForm()?>

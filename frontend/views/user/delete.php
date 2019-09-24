<?php

/* @var $this yii\web\View */

/* @var  $del_id integer */

/* @var $error string */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\User;

?>
<script>
    document.getElementsByClassName('modal-header')[0].innerHTML = '<h3>Delete</h3>';
</script>


<?php

    echo Html::beginForm(["delete-cl","del_id"=>$del_id]) .
        "<h1>Are you sure about that?</h1>" .
        "<h2><br></h2>" .
        Html::submitButton("Delete") .
        Html::endForm();


?>

<h2><br></h2>

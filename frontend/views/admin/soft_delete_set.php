<?php
/* @var $this yii\web\View */
/* @var $cl_id integer */
/* @var $user_id integer */

use common\models\CheckList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

$this->registerJsFile("@web/js/soft_delete_form.js",["depends"=>[JqueryAsset::class]]);
echo $this->beginPage();
echo $this->beginBody();
?>
<article
        id="vars"
        data-cl-id='<?= $cl_id ?>'
        data-user-id="<?= $user_id ?>"
    <?php
    echo "data-csrf='" . Yii::$app->request->csrfParam . "'" . PHP_EOL;
    echo "data-token='" . Yii::$app->request->getCsrfToken() . "'" . PHP_EOL;
    ?>>
</article>

<div class="p-3 mb-2 bg-danger text-white text-center"><h3>Description</h3></div><br>
<div class="descr_div">
    <textarea id="problem_description"
              placeholder="Problem Description"
              class="md-textarea form-control"
              style="resize: none;
              border: 3px solid #ff494a;
              background: #ffbbb4;
              color: #217200;
              font-size: 18px;
              box-shadow: rgba(255,100,100,100)"
              rows="4"></textarea>
    <h6><br></h6>

</div>
<button type="button" class="btn-danger" id="problem_submit" value="<?=Url::to(["/admin/soft-delete-cl"])?>">Save</button>
<script>
    $(document).ready(function () {
        InitSoftDeleteForm();
    })
</script>
<?php
echo $this->endBody();
echo $this->endPage();
?>

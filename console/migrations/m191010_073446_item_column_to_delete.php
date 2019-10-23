<?php

use common\models\CheckList;
use common\models\CheckListItem;
use yii\db\Migration;

/**
 * Class m191010_073446_item_column_to_delete
 */
class m191010_073446_item_column_to_delete extends Migration
{

    public function up()
    {
        $this->addColumn(CheckListItem::tableName(), "to_delete", $this->boolean()->defaultValue("0"));
    }

    public function down()
    {
        $this->dropColumn(CheckListItem::tableName(), "to_delete");
    }

}

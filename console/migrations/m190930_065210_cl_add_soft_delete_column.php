<?php

use common\models\CheckList;
use yii\db\Migration;

/**
 * Class m190930_065210_cl_add_soft_delete_column
 */
class m190930_065210_cl_add_soft_delete_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190930_065210_cl_add_soft_delete_column cannot be reverted.\n";

        return false;
    }


    public function up()
    {
        $this->addColumn(CheckList::tableName(),"soft_delete",$this->boolean()->defaultValue("0"));
    }

    public function down()
    {
        $this->dropColumn(CheckList::tableName(),"soft_delete");
    }

}

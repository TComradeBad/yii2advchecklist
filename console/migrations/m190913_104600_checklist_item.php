<?php

use yii\db\Migration;

/**
 * Class m190913_104600_checklist_item
 */
class m190913_104600_checklist_item extends Migration
{

    public function safeDown()
    {
        echo "m190913_104600_checklist_item cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable("checklist_item",[
            "id" => $this->primaryKey(),
            "name" => $this->string()->defaultValue("new task"),
            "done" => $this->boolean()->defaultValue("0"),
            "cl_id"=> $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex("idx-cl_item-cl_id",
            "checklist_item",
            'cl_id');

        $this->addForeignKey("fk-cl_item-cl_id",
            "checklist_item",
            'cl_id',
            'checklist',
            "id",
            "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk-cl_item-cl_id","checklist_item");
        $this->dropIndex("idx-cl_item-cl_id","checklist_item");
        $this->dropTable("checklist_item");
    }

}

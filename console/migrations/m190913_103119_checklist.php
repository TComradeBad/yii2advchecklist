<?php

use yii\db\Migration;

/**
 * Class m190913_103119_checklist
 */
class m190913_103119_checklist extends Migration
{


    public function up()
    {
        $this->createTable("checklist",[
            "id" => $this->primaryKey(),
            "name" => $this->string()->defaultValue("new task"),
            "done" => $this->boolean()->defaultValue("0"),
            "user_id"=> $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex("idx-cl-user_id",
            "checklist",
            'user_id');

        $this->addForeignKey("fk-cl-user_id",
            "checklist",
            'user_id',
            'user',
            "id",
            "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk-cl-user_id","checklist");
        $this->dropIndex("idx-cl-user_id","checklist");
        $this->dropTable("checklist");
    }

}

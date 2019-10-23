<?php

use yii\db\Migration;

/**
 * Class m191017_135005_user_information
 */
class m191017_135005_user_information extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable("user_information", [
            "id" => $this->primaryKey(),
            "user_id" => $this->integer(),
            "last_cl_done_time"=> $this->integer(),
            "last_task_done_time"=>$this->integer(),
        ]);
        $this->createIndex("idx-user_id",
            "user_information",
            "user_id");

        $this->addForeignKey("fk-user_id",
            "user_information",
            "user_id",
            "user",
            "id",
            "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk-user_id","user_information");
        $this->dropIndex("idx-user_id","user_information");
        $this->dropTable("user_information");
    }

}

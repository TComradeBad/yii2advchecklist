<?php

use yii\db\Migration;

/**
 * Class m191010_090301_admin_sof_delete_commentary
 */
class m191010_090301_admin_sof_delete_commentary extends Migration
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
        echo "m191010_090301_admin_sof_delete_commentary cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable("cl_problem", [
            "id" => $this->primaryKey(),
            "cl_id" => $this->integer(),
            "description" => $this->text(),
            "pushed_to_review" => $this->boolean()->defaultValue("0"),
            'updated_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex("idx-cl-problem",
            "cl_problem",
            'cl_id');

        $this->addForeignKey("fk-cl-problem",
            "cl_problem",
            'cl_id',
            'checklist',
            "id",
            "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fk-cl-problem","cl_problem");
        $this->dropIndex("idx-cl-problem","cl_problem");
        $this->dropTable("cl_problem");
    }

}

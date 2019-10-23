<?php

use common\models\CheckList;
use common\models\CheckListItem;
use yii\db\Migration;

/**
 * Class m191003_135521_utf8_db
 */
class m191003_135521_utf8_db extends Migration
{


    public function up()
    {
        $db = Yii::$app->db;
        $db->createCommand("ALTER DATABASE ".$this->getDbName()." CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;")->execute();
        $db->createCommand("ALTER TABLE ".CheckList::tableName()." CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;")->execute();
        $db->createCommand("ALTER TABLE ".CheckListItem::tableName()." CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;")->execute();
    }

    public function down()
    {

        return true;
    }

    private function getDbName()
    {
        preg_match("/dbname=([^;]*)/", Yii::$app->db->dsn, $matches);
        return $matches[1];
    }
}

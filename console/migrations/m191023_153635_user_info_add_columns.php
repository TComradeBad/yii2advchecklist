<?php

use common\models\UserInfo;
use yii\db\Migration;

/**
 * Class m191023_153635_user_info_add_columns
 */
class m191023_153635_user_info_add_columns extends Migration
{


    public function up()
    {
        $this->addColumn(UserInfo::tableName(),"cl_done_count",$this->integer()->defaultValue("0"));
        $this->addColumn(UserInfo::tableName(),"cl_in_process_count",$this->integer()->defaultValue("0"));
        $this->addColumn(UserInfo::tableName(),"cl_on_review",$this->integer()->defaultValue("0"));
        $this->addColumn(UserInfo::tableName(),"cl_good_count",$this->integer()->defaultValue("0"));
        $this->addColumn(UserInfo::tableName(),"cl_sd_count",$this->integer()->defaultValue("0"));
    }

    public function down()
    {
        $this->dropColumn(UserInfo::tableName(),"cl_done_count");
        $this->dropColumn(UserInfo::tableName(),"cl_in_process_count");
        $this->dropColumn(UserInfo::tableName(),"cl_on_review");
        $this->dropColumn(UserInfo::tableName(),"cl_sd_count");

    }
}

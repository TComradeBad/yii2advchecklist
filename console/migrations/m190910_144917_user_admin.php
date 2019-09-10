<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m190910_144917_user_admin
 */
class m190910_144917_user_admin extends Migration
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
        echo "m190910_144917_user_admin cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $user = new User();
        $user->username = "admin";
        $user->email = "admin@mail.ru";
        $user->setPassword("admin_password");
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole("super_admin"),$user->id);
    }

    public function down()
    {
        User::deleteAll(["username"=>"admin"]);

        return false;
    }

}

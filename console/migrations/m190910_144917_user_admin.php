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
        //super admin
        $user = new User();
        $user->username = "super_admin";
        $user->email = "admin111@mail.ru";
        $user->setPassword("admin_password");
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole("super_admin"), $user->id);

        //admin
        $user = new User();
        $user->username = "admin";
        $user->email = "admin222@mail.ru";
        $user->setPassword("admin_password");
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole("admin"), $user->id);

        //moderator
        $user = new User();
        $user->username = "moderator";
        $user->email = "admin333@mail.ru";
        $user->setPassword("admin_password");
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save();
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole("moderator"), $user->id);
    }

    public function down()
    {
        try {
            User::deleteAll(["username" => "admin"]);
            User::deleteAll(["username" => "super_admin"]);
            User::deleteAll(["username" => "moderator"]);
        } catch (\yii\db\Exception $exception) {
            echo $exception->getMessage();
        }

    }

}

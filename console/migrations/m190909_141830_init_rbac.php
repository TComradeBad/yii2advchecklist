<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m190909_141830_init_rbac
 */
class m190909_141830_init_rbac extends Migration
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
        echo "m190909_141830_init_rbac cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        /**
         * Creating Permissions
         */
        //Ban users
        $ban_users = $auth->createPermission("ban_users");
        $auth->add($ban_users);
        //Delete users
        $delete_users = $auth->createPermission("delete_users");
        $auth->add($delete_users);
        //Set user role
        $set_user_role = $auth->createPermission("set_users_role");
        $auth->add($set_user_role);
        //Manage user's checklists count
        $cl_count = $auth->createPermission("set_cl_count");
        $auth->add($cl_count);
        $cl_item_count = $auth->createPermission("set_cl_item_count");
        $auth->add($cl_item_count);
        //Manage user's checklists
        $manage_cl = $auth->createPermission("manage_users_cl");
        $auth->add($manage_cl);

        /**
         * Creating roles
         */
        //User
        $user = $auth->createRole("user");
        $auth->add($user);
        //Moderator
        $moderator = $auth->createRole("moderator");
        $auth->add($moderator);
        $auth->addChild($moderator,$ban_users);
        $auth->addChild($moderator,$manage_cl);
        //Admin
        $admin = $auth->createRole("admin");
        $auth->add($admin);
        $auth->addChild($admin,$moderator);
        $auth->addChild($admin,$cl_count);
        $auth->addChild($admin,$cl_item_count);
        ;
        //Super-admin
        $super_admin = $auth->createRole("super_admin");
        $auth->add($super_admin);
        $auth->addChild($super_admin,$admin);
        $auth->addChild($super_admin,$delete_users);
        $auth->addChild($super_admin,$set_user_role);

        /**
         * Create user
         */




    }

    public function down()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }

}

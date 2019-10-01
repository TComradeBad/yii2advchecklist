<?php

namespace console\controllers;

use common\rbac\rules\AdminRule;
use common\rbac\rules\OwnerRule;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionCreate()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();
        /**
         * Rules
         */
        $rule = new AdminRule();
        $owner_rule = new OwnerRule();
        $auth->add($rule);
        $auth->add($owner_rule);

        /**
         * Creating Permissions
         */
        //Is owner
        $is_owner = $auth->createPermission("cl_owner");
        $is_owner->ruleName = $owner_rule->name;
        $auth->add($is_owner);
        //Manage users and admins
        $manage_users = $auth->createPermission("manage_users");
        $manage_users->ruleName = $rule->name;
        $auth->add($manage_users);
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
        $cl_count = $auth->createPermission("set_cl_count");;
        $auth->add($cl_count);
        //Manage user's checklist items count
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
        $auth->addChild($user,$is_owner);
        //Moderator
        $moderator = $auth->createRole("moderator");
        $auth->add($moderator);
        $auth->addChild($moderator,$user);
        $auth->addChild($moderator, $ban_users);
        $auth->addChild($moderator, $manage_cl);
        $auth->addChild($moderator, $manage_users);
        //Admin
        $admin = $auth->createRole("admin");
        $auth->add($admin);
        $auth->addChild($admin, $moderator);
        $auth->addChild($admin, $cl_count);
        $auth->addChild($admin, $cl_item_count);;
        //Super-admin
        $super_admin = $auth->createRole("super_admin");
        $auth->add($super_admin);
        $auth->addChild($super_admin, $admin);
        $auth->addChild($super_admin, $delete_users);
        $auth->addChild($super_admin, $set_user_role);
    }


    public function actionRemove()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();
    }
}
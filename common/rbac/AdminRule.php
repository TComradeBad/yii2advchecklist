<?php

namespace common\rbac;

use yii\db\Query;
use yii\rbac\Rule;
use common\rbac\classes\RolesLevels as RL;

class AdminRule extends Rule
{
    public $name = 'isGreaterRole';

    public function execute($user, $item, $params)
    {

        if (isset($params["affected_user"])) {
            $roles = \Yii::$app->authManager->getRolesByUser($user);
            $role_1 = current($roles);
            $affected_user_roles = \Yii::$app->authManager->getRolesByUser($params["affected_user"]->id);
            $role_2 = current($affected_user_roles);
            return (RL::getRoleLevel($role_1->name) > (RL::getRoleLevel($role_2->name)));
        }
         return false;
    }


}
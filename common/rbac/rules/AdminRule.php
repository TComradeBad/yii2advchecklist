<?php

namespace common\rbac\rules;

use yii\db\Query;
use yii\rbac\Rule;
use common\rbac\classes\RoleService as RL;

class AdminRule extends Rule
{
    public $name = 'hasGreaterRole';

    public function execute($user, $item, $params)
    {
        if (isset($params["affected_user"])) {
            $role_1 = RL::getUserRole($user);
            $role_2 = RL::getUserRole($params["affected_user"]->id);
            return (Rl::Higher($role_1->name,$role_2->name));
        }
         return false;
    }


}
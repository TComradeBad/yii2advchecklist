<?php

namespace common\rbac\rules;

use common\classes\ConsoleLog;
use common\models\CheckList;
use common\models\User;
use yii\rbac\Rule;

class OwnerRule extends Rule
{

    public $name = 'isOwner';

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        if (isset($params["checklist"])) {
            /** @var $cl CheckList  **/
            $cl = $params["checklist"];

            return ($cl->user_id == $user);
        }
        return false;
    }

}

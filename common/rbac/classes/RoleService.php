<?php

namespace common\rbac\classes;

class RoleService
{
    protected static $levels = [
        "moderator" => 1,
        "admin" => 2,
        "super_admin" => 3,
    ];

    /**
     * @param string $role
     */
    public static function getRoleLevel($role)
    {
        return self::$levels[$role];
    }

    /**
     * @param string $role1
     * @param string $role2
     * @return bool
     */
    public static function Higher($role1, $role2)
    {
        return (self::getRoleLevel($role1) > self::getRoleLevel($role2));

    }

    public static function getUserRole($user_id)
    {
        return current(\Yii::$app->authManager->getRolesByUser($user_id));
    }
}
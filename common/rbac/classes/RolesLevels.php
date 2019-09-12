<?php

namespace common\rbac\classes;

class RolesLevels
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
}
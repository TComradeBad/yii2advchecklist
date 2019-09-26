<?php
namespace common\classes;

class ConsoleLog
{
    /**
     * @param $data string
     */
    public static function log($data)
    {
        $STD = fopen("php://stdout", "w");
        fwrite($STD, $data);
        fclose($STD);
    }
}
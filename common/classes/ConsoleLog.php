<?php
namespace common\classes;

class ConsoleLog
{
    /**
     * @param $data string
     */
    public static function log($data,$php_eol = true)
    {
        $STD = fopen("php://stdout", "w");
        if($php_eol){
            fwrite($STD, $data.PHP_EOL);
        }else{
            fwrite($STD, $data);
        }

        fclose($STD);
    }
}
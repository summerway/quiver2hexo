<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/5
 * Time: 6:59 PM
 */

namespace Quiver2Hexo\Service;

class LogService {
    static protected $queue = [];

    static function insert($message){
        array_push(self::$queue,$message);
    }

    static function error($message){
        var_dump($message);
    }

    static function info($message){
        var_dump($message);
    }

    static function output(){
        if(getenv('SHOW_LOG')){
            while(self::$queue){
                var_dump(array_pop(self::$queue));
            }
        }
    }

}
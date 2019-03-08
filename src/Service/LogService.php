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

    static function getQueue(){
        return self::$queue;
    }

    static function error($message){
        dump($message);
    }

    static function info($message){
        dump($message);
    }

    static function insert($message){
        env('SHOW_LOG',true) && array_push(self::$queue,$message);
    }

    static function output(){
        if(env('SHOW_LOG',true)){
            if(self::$queue){
                while(self::$queue){
                    dump(array_pop(self::$queue));
                }
            }else{
                //dump("nothing changed~");
            }
        }

        self::info("sync success");
    }

}
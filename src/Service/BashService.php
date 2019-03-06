<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/4
 * Time: 11:16 AM
 */

namespace Quiver2Hexo\Service;

class BashService{

    static function pwd($path){
        return trim(shell_exec("cd {$path} && pwd"));
    }

    static function rm($path){
        return shell_exec("rm -rf {$path}");
    }

    static function cp($origin,$destination){
        $origin = FileService::format($origin);
        $destination = FileService::format($destination);

        return shell_exec("cp -R {$origin} {$destination}");
    }

    static function backUp($origin){
        return self::cp($origin,"{$origin}.bak");
    }

    /**
     * @param $origin
     * @return bool
     * @throws \Exception
     */
    static function rollback($origin){
        $bakFile = "{$origin}.bak";
        if(!file_exists($bakFile)){
            throw new \Exception("Can't roll back, {$origin} doesn't have any backup file");
        }

        self::rm($origin);
        rename($bakFile,$origin);
        return true;
    }

    static function mkdir($path){
        return shell_exec("mkdir -p $path");
    }

    static function hexoDeploy(){
        $hexoPath = getenv("HEXO_PATH");
        return shell_exec("cd {$hexoPath} && hexo clean && hexo g -d");
    }

    static function hexoServer(){
        $hexoPath = getenv("HEXO_PATH");
        return shell_exec("cd {$hexoPath} && hexo s --debug");
    }

    static function getProcessId($process){
        return shell_exec("pgrep {$process}");
    }

    static function kill($process){
        return shell_exec("kill -9 {$process}");
    }
}

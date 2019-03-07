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
        if(empty($path)){
            return false;
        }
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

    static function mkdir($path){
        return shell_exec("mkdir -p $path");
    }

    /**
     * @param string $path hexo base path
     * @return string
     */
    static function hexoDeploy($path){
        return shell_exec("cd {$path} && hexo g -d");
    }

    /**
     * @param string $path hexo base path
     * @return string
     */
    static function hexoServer($path){
        if($pid = self::getProcessId("hexo")){
            self::kill($pid);
        }
        return shell_exec("cd {$path} && hexo s --debug");
    }

    static function getProcessId($process){
        return shell_exec("pgrep {$process}");
    }

    static function kill($process){
        return shell_exec("kill -9 {$process}");
    }
}

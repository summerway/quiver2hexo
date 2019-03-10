<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/4
 * Time: 2:43 PM
 */

namespace Quiver2Hexo\Service;

use Quiver2Hexo\Logic\FileLogic;

class FileService {

    static function createFile($name,$content){
        $file = fopen($name,'w');
        fwrite($file,$content);
        fclose($file);
        return true;
    }

    static function readJson($file,$returnArray = false){
        (new FileLogic)->checkExt($file,'json');
        $content = file_get_contents($file);
        return json_decode($content,$returnArray);
    }

    /**
     * Make sure the unique filename
     * @param $name
     * @return string
     */
    static function checkUnique($name){
        return (new FileLogic)->getUniqueName($name);
    }

    static function swapFilename($obj,$sbj){
        rename($obj,"/tmp/tmp");
        rename($sbj,$obj);
        rename("/tmp/tmp",$sbj);
    }

    /**
     * Compare the differences between two dir
     * @param $obj
     * @param $sbj
     * @return array
     */
    static function diffDir($obj,$sbj){
        $lgc = new FileLogic();
        return array_diff($lgc->getDirFiles($obj),$lgc->getDirFiles($sbj));
    }

}
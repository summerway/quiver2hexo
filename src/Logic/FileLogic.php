<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/4
 * Time: 4:26 PM
 */

namespace Quiver2Hexo\Logic;

class FileLogic {

    function getExt($file){
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    function getFilename($file){
        return pathinfo($file,PATHINFO_FILENAME);
    }

    function checkExt($file,$ext){
        return $ext == $this->getExt($file);
    }

    function getUniqueName($name,$index = 0){
        if(file_exists($name)){
            $name = dirname($name)."/".$this->getFilename($name).(++$index).".".$this->getExt($name);
            return $this->getUniqueName($name,$index);
        }
        return $name;
    }

    function getDirFiles($dir){
        $handler = opendir($dir);
        $files = [];
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {
                $files[] = $filename ;
            }
        }
        closedir($handler);

        return $files;
    }
}
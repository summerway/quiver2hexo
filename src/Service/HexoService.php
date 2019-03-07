<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/5
 * Time: 11:19 AM
 */

namespace Quiver2Hexo\Service;

use Exception;

class HexoService {

    static $hexoPath;  // hexo folder path

    static $init = false;  // whether to complete initialization

    /**
     * @param $path
     * @return bool|string
     * @throws Exception
     */
    static function getBasePath($path = ''){
        if(!self::$hexoPath){
            $hexoPath = BashService::pwd(env('HEXO_PATH',""));
            if(!$hexoPath || !file_exists($hexoPath)){
                throw new Exception("hexp path not found~");
            }

            self::$hexoPath = $hexoPath;
        }

        return self::$hexoPath.($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }

    /**
     * @param $path
     * @return string
     * @throws Exception
     */
    static public function getPostPath($path = ''){
        return self::getBasePath("source/_posts") . ($path ?
                DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }

    /**
     * @param $path
     * @return string
     * @throws Exception
     */
    static public function getPostBakPath($path = ''){
        return self::getBasePath("source/._posts.bak"). ($path ?
                DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function initPost(){
        $postPath = $this->getPostPath();
        !file_exists($postPath) && BashService::mkdir($postPath);
        $this->backupPost();
        self::$init = true;
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function rollback() {
        $bakPath = $this->getPostBakPath();
        if(!file_exists($bakPath)){
            throw new \Exception("backup file not found");
        }

        FileService::swapFilename($bakPath,$this->getPostPath());
        $this::removePostBak($bakPath);
        return true;
    }

    /**
     * @throws Exception
     */
    public function deploy() {
        BashService::hexoDeploy($this->getBasePath());
    }

    /**
     * @return string
     * @throws Exception
     */
    public function server(){
        return BashService::hexoServer($this->getBasePath());
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function backupPost(){
        $postPath = $this->getPostPath();
        $postBakPath = $this->getPostBakPath();

        $this->removePostBak($postBakPath);
        rename($postPath,$postBakPath);
        BashService::mkdir($postPath);
        return true;
    }

    /**
     * @param $path
     * @return bool|string
     * @throws Exception
     */
    private function removePostBak($path){
        if($path != $this->getPostBakPath()){
            return false;
        }

        if(!file_exists($path)){
            return true;
        }

        return BashService::rm($path);
    }
}
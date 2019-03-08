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
     * @param $path
     * @return string
     * @throws Exception
     */
    static public function getPostStashPath($path = ''){
        return self::getBasePath("source/._posts.tmp"). ($path ?
                DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function initPost(){
        $postPath = $this->getPostPath();
        !file_exists($postPath) && BashService::mkdir($postPath);
        $this->stashPost();
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function backupPost(){
        if(LogService::getQueue()){
            HexoService::removeBakPost();
            rename($this->getPostStashPath(),$this->getPostBakPath());
        }else{
            HexoService::removeStashPost();
        }
        return true;
    }

    /**
     * @return bool
     */
    public function reset(){
        try{
            $postStashPath = $this->getPostStashPath();
            if(file_exists($postStashPath)){
                FileService::swapFilename($postStashPath,$this->getPostPath());
                $this->removeStashPost();
            }

            return true;
        }catch (Exception $e){
            LogService::error('Reset failed: '.$e->getMessage());
            return false;
        }
    }

    /**
     * @return bool
     */
    public function rollback() {
        try{
            $bakPath = $this->getPostBakPath();
            if(!file_exists($bakPath)){
                throw new \Exception("backup file not found");
            }

            FileService::swapFilename($bakPath,$this->getPostPath());
            $this::removeBakPost();
            LogService::info("Rollback success~");
            return true;
        }catch (Exception $e){
            LogService::error('Rollback failed: '.$e->getMessage());
            return false;
        }
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
    private function stashPost(){
        $postPath = $this->getPostPath();
        rename($postPath,$this->getPostStashPath());
        BashService::mkdir($postPath);

        return true;
    }

    /**
     * @return bool|string
     * @throws Exception
     */
    private function removeBakPost(){
        $path = $this->getPostBakPath();

        if(!file_exists($path)){
            return true;
        }

        return BashService::rm($path);
    }

    /**
     * @return bool|string
     * @throws Exception
     */
    private function removeStashPost(){
        $path = $this->getPostStashPath();

        if(!file_exists($path)){
            return true;
        }

        return BashService::rm($path);
    }
}
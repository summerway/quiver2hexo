<?php
/**
 * Quiver2Hexo - converts quiver notes written by markdown to Hexo blog posts
 *
 * @author Maple Snow <summerweiace@163.com>
 */


namespace Quiver2Hexo;

use Quiver2Hexo\Service\BashService;
use Quiver2Hexo\Service\HexoService;
use Quiver2Hexo\Service\LogService;
use Quiver2Hexo\Service\QuiverService;
use Exception;

/**
 * Class Transform
 * @package Quiver2Hexo
 */
class Quiver{

    protected $quiService;

    protected $hexService;

    public function __construct() {
        $this->quiService = new QuiverService();
        $this->hexService = new HexoService();
    }

    /**
     * init hexo posts directory
     * @return bool
     * @throws Exception
     */
    public function migrate() {
        try{
            $this->hexService->initPost();
            $this->quiService->migrate($this->hexService->getPostPath());
            LogService::output();
        }catch (Exception $e){
            LogService::error('Migrate failed: '.$e->getMessage());
            $this->rollback();
        }
        return true;
    }

    /**
     * 后期修改同步
     * @return bool
     * @throws Exception
     */
    public function sync() {
        try{
            $this->hexService->initPost();
            $this->quiService->sync($this->hexService->getPostPath());
            LogService::output();
        }catch (Exception $e){
            LogService::error('Sync failed: '.$e->getMessage());
            $this->rollback();
        }
        return true;
    }

    /**
     * 回滚
     * @return bool
     * @throws Exception
     */
    public function rollback(){
        return BashService::rollback($this->hexService->getPostPath());
    }
}



<?php
/**
 * Quiver2Hexo - converts quiver notes written by markdown to Hexo blog posts
 *
 * @author Maple Snow <summerweiace@163.com>
 */


namespace Quiver2Hexo;

use Quiver2Hexo\Service\HexoService;
use Quiver2Hexo\Service\LogService;
use Quiver2Hexo\Service\QuiverService;
use Exception;

/**
 * Class Transform
 * @package Quiver2Hexo
 */
class Quiver{

    /**
     * 同步
     * @return bool
     */
    static public function sync() {
        $HexoService = new HexoService();
        try{
            $HexoService->initPost();
            (new QuiverService)->sync();
            $HexoService->backupPost();
        }catch (Exception $e){
            LogService::error('Sync failed: '.$e->getMessage());
            $HexoService->reset();
            return false;
        }

        LogService::output();
        return true;
    }

    /**
     * 回滚
     * @return bool
     */
    static public function rollback(){
        return (new HexoService)->rollback();
    }
}



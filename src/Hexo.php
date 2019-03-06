<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/6
 * Time: 12:14 AM
 */

namespace Quiver2Hexo;

use Quiver2Hexo\Service\HexoService;
use Quiver2Hexo\Service\LogService;
use Exception;

class Hexo {

    static public function server(){
        try{
            $service = new HexoService();
            return $service->server();
        }catch (Exception $e){
            LogService::error('Deploy failed:'.$e->getMessage());
        }
    }

    static public function deploy(){
        try{
            $service = new HexoService();
            $service->deploy();
            LogService::info("deploy success, checkout: ".getenv("BLOG_URI"));
        }catch (Exception $e){
            LogService::error('Deploy failed:'.$e->getMessage());
        }
    }
}
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

    /**
     * 本地服务
     */
    static public function server(){
        try{
            LogService::info("hexo is starting up, waiting 5 seconds then checkout: ".HEXO_LOCAL_SERVER);
            (new HexoService)->server();
        }catch (Exception $e){
            LogService::error('Run hexo error:'.$e->getMessage());
        }
    }

    /**
     * 发布上线
     */
    static public function deploy(){
        try{
            (new HexoService)->deploy();
            LogService::info("deploy success, checkout: ".env("BLOG_URI"));
        }catch (Exception $e){
            LogService::error('Deploy failed:'.$e->getMessage());
        }
    }
}
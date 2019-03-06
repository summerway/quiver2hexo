<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/5
 * Time: 11:19 AM
 */

namespace Quiver2Hexo\Service;

class HexoService {

    protected $basePath;

    public function __construct() {
        $this->basePath = BashService::pwd(getenv('HEXO_PATH'));
    }

    public function initPost(){
        $postPath = $this->getPostPath();
        !file_exists($postPath) && BashService::mkdir($postPath);
        BashService::rm("{$postPath}.bak");
        rename($postPath,"{$postPath}.bak");
        BashService::mkdir($postPath);
        return true;
    }

    public function backupPost(){
        $postPath = $this->getPostPath();
        !file_exists($postPath) && BashService::mkdir($postPath);
        BashService::rm("{$postPath}.bak");
        BashService::backUp($postPath);
        return true;
    }

    public function getPostPath(){
        return "{$this->basePath}/source/_posts";
    }

    public function deploy() {
        BashService::hexoDeploy();
    }

    public function server(){
        BashService::hexoServer();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/4
 * Time: 2:12 PM
 */

require __DIR__.'/../vendor/autoload.php';

$env = Dotenv\Dotenv::create(__DIR__);
$env->load();

use Quiver2Hexo\Quiver;
use Quiver2Hexo\Hexo;

$quiver = new Quiver();

//$quiver->migrate();
$quiver->sync();

Hexo::server();

//$transform->sync();

//$quiver->rollback();

//Hexo::deploy();
<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/4
 * Time: 2:12 PM
 */

require __DIR__.'/../vendor/autoload.php';

$env = Dotenv\Dotenv::create(__DIR__.'/../');
$env->load();

use Quiver2Hexo\Quiver;
use Quiver2Hexo\Hexo;

//Quiver::migrate();
//Quiver::sync();
//Quiver::rollback();

//Hexo::server();
//Hexo::deploy();
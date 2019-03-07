<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/6
 * Time: 6:11 PM
 */

if(!file_exists('.env')){
    echo "Please exec \033[32msh setup.sh\033[0m first~";
    exit;
}

require __DIR__.'/vendor/autoload.php';
$env = Dotenv\Dotenv::create(__DIR__);
$env->load();

use Quiver2Hexo\Quiver;
use Quiver2Hexo\Hexo;

$option = end($argv);

switch ($option){
    case '-s':
    case '--server':
        Quiver::sync();
        Hexo::server();
        break;
    case '-d':
    case '--deploy':
        Quiver::sync();
        Hexo::deploy();
        break;
    case '-r':
    case '--rollback':
        Quiver::rollback();
        break;
    case '-rd':
        Quiver::rollback();
        Hexo::deploy();
        break;
    case '-h':
    case '--help':
        echo "Ps:中文帮助说明请使用php sync.php -hc\n";
        echo "The script will sync QUIVER notes to HEXO. By default, the synced notes will not be deployed.\n\n";
        echo "The options are as follows:\n\n";
        echo "\t-s,--server\tStart the HEXO local server after synchronization finishes, restart the service if the server exists.\n\n";
        echo "\t-d,--deploy\tDeploy after synchronization finishes\n\n";
        echo "\t-r,--rollback\tRollback the last sync operation\n\n";
        echo "\t-rd\tRollback the last deploy operation\n";
        break;
    case '-hc':
        echo "该脚本会将QUIVER中的笔记同步至HEXO中，默认只同步不做发布。\n\n";
        echo "可选参数如下:\n\n";
        echo "\t-s,--server\t同步完成后启动HEXO本地服务，若服务存在则重启服务\n\n";
        echo "\t-d,--deploy\t同步完成后部署网站\n\n";
        echo "\t-r,--rollback\t回滚最近一次的同步操作\n\n";
        echo "\t-rd\t回滚最近一次的发布操作\n";
        break;
    default:
        Quiver::sync();
        break;
}
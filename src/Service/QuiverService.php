<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/5
 * Time: 11:18 AM
 */

namespace Quiver2Hexo\Service;

use Exception;

class QuiverService {

    protected $libraryPath;

    protected $categoryList;    // HEXO category list

    protected $relTag;          // sync articles with this tag

    /**
     * QuiverService constructor.
     * @throws Exception
     */
    public function __construct() {
        $libraryPath = BashService::pwd(env("QUIVER_LIBRARY_PATH",""));
        if(!$libraryPath || !file_exists($libraryPath)){
            throw new Exception("quiver library path not found~");
        }
        $this->libraryPath = $libraryPath;
        $this->relTag = env('QUIVER_RELEASE_TAG','relHexo');
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function sync() {
        $this->categoryList = $this->getCategoryList();
        $this->walkLibrary($this->libraryPath);

        // log
        $diff = FileService::diffDir(HexoService::getPostStashPath(),HexoService::getPostPath());
        foreach($diff as $file){
            LogService::insert("remove {$file}");
        }

        return true;
    }

    public function getCategoryList() {
        if(!$this->categoryList){
            $meta = FileService::readJson("{$this->libraryPath}/meta.json");
            $this->walkCategories($meta->children);
        }

        return $this->categoryList;
    }

    private function walkCategories($categories,$chain = []){
        foreach($categories as $cate){
            $meta = FileService::readJson("{$this->libraryPath}/{$cate->uuid}.qvnotebook/meta.json");
            array_push($chain,$meta->name);

            isset($cate->children) ?
                $this->walkCategories($cate->children,$chain) : $this->categoryList[$meta->uuid] = $chain;

            array_pop($chain);
        }
    }

    /**
     * @param $path
     * @param string $parentId
     * @throws Exception
     */
    private function walkLibrary($path,$parentId = ""){
        foreach(scandir($path) as $file){
            if(in_array($file,['.','..','Inbox.qvnotebook','Trash.qvnotebook']))
                continue;

            if(is_dir($path.'/'.$file)){
                $meta = FileService::readJson("{$path}/meta.json");
                $this->walkLibrary($path.'/'.$file,$meta->uuid);
            }else{
                if('content.json' == $file){
                    $this->doSync($path,$file,$parentId);
                }
            }
        }
    }

    /**
     * @param $path
     * @param $file
     * @param $parentId
     * @return bool
     * @throws Exception
     */
    private function doSync($path,$file,$parentId){
        $markdown = $this->convertMarkdown($path,$file,$parentId);
        if(!$markdown){
            return false;
        }

        list($filename,$content,$updatedAt,$categories) = $markdown;
        $bakFile = HexoService::getPostStashPath($filename);
        if(file_exists($bakFile)){
            $name = HexoService::getPostPath($filename);
            $lastUpdatedAt = filemtime($bakFile);
            if($updatedAt > $lastUpdatedAt){
                FileService::createFile($name,$content);
                $message = "modify ".implode("|",$categories)."|".$filename;
                LogService::insert($message);
            }else{
                copy($bakFile,$name);
            }
        }else{
            $name = FileService::checkUnique(HexoService::getPostPath($filename));
            FileService::createFile($name,$content);
            $message = "migrate ".implode("|",$categories)."|".$filename;
            LogService::insert($message);
        }

        return true;
    }

    /**
     * Parsing quiver json, get markdown info [filename,content,updatedAt,categories]
     * @param $path
     * @param $file
     * @param $parentId
     * @return array|bool
     */
    private function convertMarkdown($path,$file,$parentId) {
        $metaFile = "{$path}/meta.json";
        $meta = FileService::readJson($metaFile);
        $title = $meta->title;
        $date = date('Y-m-d H:i:s',$meta->created_at);
        if(!in_array($this->relTag,$meta->tags)){
            return false;
        }
        // filter release hexo tag
        $quiTags = array_diff($meta->tags,[$this->relTag]);
        $tags = implode(",",$quiTags);

        $categories = implode(",",$this->categoryList[$parentId]);
        $mdMeta = "---\ntitle: {$title}\ndate: {$date}\ncategories: [{$categories}]\ntags: [{$tags}]\n---\n\n";

        $content = FileService::readJson("{$path}/{$file}");
        $mdData = "";
        foreach($content->cells as $cell){
            switch ($cell->type){
                case 'text':
                    // todo text ...
                    break;
                case 'code':
                    // todo code ...
                    break;
                case 'markdown':
                default:
                    $mdData = $cell->data;
                    break;
            }
        }

        $filename = "{$meta->title}.md";
        $updatedAt = filemtime($metaFile);
        return [$filename,$mdMeta.$mdData,$updatedAt,$this->categoryList[$parentId]];
    }
}
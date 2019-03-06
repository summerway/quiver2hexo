<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/5
 * Time: 11:18 AM
 */

namespace Quiver2Hexo\Service;

class QuiverService {

    protected $libraryPath;

    protected $categoryList;    // hexo category list

    protected $relTag;          // sync articles with this tag

    protected $migratePath;

    protected $init = false;

    public function __construct() {
        $this->libraryPath = BashService::pwd(getenv("QUIVER_LIBRARY_PATH"));
        $this->relTag = getenv('QUIVER_RELEASE_TAG');
    }

    public function migrate($destination) {
        $this->init = true;
        $this->migratePath = $destination;
        $this->categoryList = $this->getCategoryList();
        $this->walkLibrary($this->libraryPath);
    }

    public function sync($destination) {
        $this->init = false;
        $this->migratePath = $destination;
        $this->categoryList = $this->getCategoryList();
        $this->walkLibrary($this->libraryPath);

        $diff = FileService::diffDir("{$destination}.bak",$destination);
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

    private function walkLibrary($path,$parentId = ""){
        foreach(scandir($path) as $file){
            if(in_array($file,['.','..','Inbox.qvnotebook','Trash.qvnotebook']))
                continue;

            if(is_dir($path.'/'.$file)){
                $meta = FileService::readJson("{$path}/meta.json");
                $this->walkLibrary($path.'/'.$file,$meta->uuid);
            }else{
                if('content.json' == $file){
                    $this->init ?
                        $this->doMigrate($path,$file,$parentId) : $this->doSync($path,$file,$parentId);
                }
            }
        }
    }

    private function doMigrate($path,$file,$parentId){

        $markdown = $this->convertMarkdown($path,$file,$parentId);
        if(!$markdown){
            return false;
        }
        list($filename,$content,,$categories) = $markdown;
        $name = FileService::checkUnique("{$this->migratePath}/{$filename}");
        $res =  FileService::createFile($name,$content);
        $message = "migrate ".implode("|",$categories)."|".$filename;
        LogService::insert($message);
        return $res;
    }

    private function doSync($path,$file,$parentId){
        $markdown = $this->convertMarkdown($path,$file,$parentId);
        if(!$markdown){
            return false;
        }

        list($filename,$content,$updatedAt,$categories) = $markdown;
        $bakFile = "{$this->migratePath}.bak/{$filename}";
        $name = "{$this->migratePath}/{$filename}";
        if(file_exists($bakFile)){
            $originUpdatedAt = filemtime($bakFile);
            if($updatedAt > $originUpdatedAt){
                FileService::createFile($name,$content);
                $message = "modify ".implode("|",$categories)."|".$filename;
                LogService::insert($message);
            }else{
                BashService::cp($bakFile,$name);
            }
        }else{
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
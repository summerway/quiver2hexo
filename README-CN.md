# 概述
**Quiver**笔记  => `quiver2hexo`同步 => **Hexo** posts => 网站发布  
[演示demo](http://markdown.zengtuo.net/quiver2hexo-demo.mp4)
## 初始化迁移
`quiver2hexo`  将**Quiver** markdown记录的笔记迁移到 **Hexo** _posts文件夹中 

## 同步
将那些在迁移完成后被修改的文件同步至**Hexo** 

## 日志
终端中输出笔记迁移变动信息

## 模拟
同步完成后启动HEXO本地服务，查看同步效果

## 发布部署
在同步完成后部署网站

## 回滚
- 支持回滚最近一次的同步操作
- 支持回滚最近一次的发布操作

Ps
- **Inbox** 和 **Trash** 笔记不会被同步
- 笔记的资源文件不会被同步，推荐将资源放至云端 [Markdown快速插入图片工具](https://github.com/summerway/markdown-image-alfred)

# 依赖
- [Quiver](http://happenapps.com/#quiver) mac上很棒的笔记应用
- [Hexo](https://hexo.io/zh-cn/) 快速、简洁且高效的博客框架
- [hexo-deployer-git](https://github.com/hexojs/hexo-deployer-git)

  ```bash
  npm install hexo-deployer-git --save
  ```

## quiver文件准备
- 给发布笔记加上特定标签，默认是`relHexo`,当然该标签不会出现在hexo。
- 将文章加上`<!-- more -->`来精确控制文章的摘要预览,比如这篇文章就是在这个段落的末尾添加了该标志，所以本文在首页的预览就会显示到这个段落为止。

# 用法
## 安装
```bash
# download
git clone https://github.com/summerway/quiver2hexo.git

# setup
cd quiver2hexo && sh setup.sh
```

## 基本用法
| Command      | Description  |
| :------:  | :-----:  |
| `php sync.php`  | 将QUIVER中修改的内容同步至HEXO |
| `php sync.php -s` | 同步完成后启动HEXO本地服务 <br/> 若服务存在则重启服务 |
| `php sync.php -d` | 同步完成后部署网站 |
| `php sync.php -r` | 回滚最近一次的同步操作 |
| `php sync.php -rd` | 回滚最近一次的发布操作 |
| `php sync.php -h` | 帮助文档 |
| `php sync.php -hc` | 中文帮助文档 |


# todo
- [ ] support `code`,`text` cell
- [ ] alfred workflow
- [ ] multi-lang


# 其他
**Quiver**修改标签和分类是不会更新`updated_at`的，所以读取更新时间得获取`meta.json`的最后修改时间

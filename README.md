
## quiver文件准备
- 将要发布的加上特定标签，默认是`relHexo`,也可在配置文件自行定义,该标签不会应用到hexo。
- 将文章加上`<!-- more -->`来精确控制文章的摘要预览,比如这篇文章就是在这个段落的末尾添加了该标志，所以本文在首页的预览就会显示到这个段落为止。

# 配置准备
- 

# todo
- migrate 
- sync
- rollback
- log
- auto deploy
- alfred workflow

# 备注
quiver 修改标签和分类是不会更新`updated_at`的，所以读取更新时间得获取`meta.json`的最后修改时间
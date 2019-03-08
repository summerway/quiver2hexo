# Overview
**Quiver** notes  => `quiver2hexo` sync => **Hexo** posts => Deploy blog website  
[demo video](http://markdown.zengtuo.net/quiver2hexo-demo.mp4)


## Synchronization
- First sync，migrating **Quiver** notes written by markdown to **Hexo** blog posts.
- Sync there modified notes to **Hexo** blog posts after migration finished.

## Log
The terminal will output note migration information.

## Simulation
Start the **Hexo** local server after synchronization finishes, you can view sync effects locally.

## Deploy
Deploy website after synchronization finishes.

## Rollback
- rollback the last sync operation
- rollback the last deploy operation

Ps
- **Inbox** and **Trash** will not be migrated
- Notes resource files will not be migrated.Recommend to have resources on the cloud.

# Dependency
- [Quiver](http://happenapps.com/#quiver):The Programmer's Notebook
- [Hexo](https://hexo.io/):A fast, simple & powerful blog framework
- [hexo-deployer-git](https://github.com/hexojs/hexo-deployer-git):Git deployer plugin for **Hexo**.

  ```bash
  npm install hexo-deployer-git --save
  ```

#  Preparation
- Import the markdown file in `hexo_path/source/_posts` into the Quiver, or make a backup. **The setup script will clear the directory**.
- Add a specific tag to the note to be posted, the default is `relHexo`, which of course don't appear in **Hexo**.
- Please use `<!-- more -->` in the note to control excerpt accurately.

# Usage
## Setup
```bash
# download
git clone https://github.com/summerway/quiver2hexo.git

# setup
cd quiver2hexo && sh setup.sh
```

## Configuration
Once the setup finishes, an `.env` config file is generated.You can change config manually with it.

## Basic Usage
| Command      | Description  |
| :------:  | :-----:  |
| `php sync.php`  | Sync the modified QUIVER notes to HEXO|
| `php sync.php -s` | Start the HEXO local server after synchronization finishes <br/> Restart the service if the server exists.|
| `php sync.php -d` | Deploy after synchronization finishes|
| `php sync.php -r` | Rollback the last sync operation|
| `php sync.php -rd` | Rollback the last deploy operation|
| `php sync.php -h` | help document|


# todo
- [ ] support `code`,`text` cell
- [ ] alfred workflow

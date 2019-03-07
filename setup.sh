#! /bin/bash
# Author: mapleSnow
echo Start installing the dependencies... ;

./composer.phar install ;

./composer.phar dumpautoload ;

echo "\n\033[44;37m Welcome to the quiver2hexo config generator \033[0m" ;

echo "This command will guide you creating your .env file" ;

echo "QUIVER library path(checkout quiver preferences -> sync -> current library):\c" ;
read quiver_library_path ;
while [ "$quiver_library_path" = "" ]
do
	echo "quiver library path is required:\c" ;
	read quiver_library_path ;
done
echo ";QUIVER library\nQUIVER_LIBRARY_PATH=\"$quiver_library_path\"\n" > .env;

echo "HEXO folder path:\c" ;
read hexo_path ;
while [ "$hexo_path" = "" ]
do
	echo "hexo path is required:\c" ;
	read hexo_path ;
done
echo ";HEXO folder path\nHEXO_PATH=\"$hexo_path\"\n" >> .env;

echo "your blog site url:\c" ;
read blog_uri ;
echo ";your blog site url\nBLOG_URI=\"$blog_uri\"\n" >> .env;

echo "sync articles with this tag [\033[32mrelHexo\033[0m]:\c" ;
read quiver_release_tag ;
if [ "$quiver_release_tag" = "" ]; then
	quiver_release_tag=relHexo
fi
echo ";sync articles with this tag\nQUIVER_RELEASE_TAG=\"$quiver_release_tag\"\n" >> .env;

echo "output the migrate log:[\033[32mtrue\033[0m]\c" ;
read show_log ;
if [ "$show_log" = "" ]; then
	show_log=true
fi
echo ";output the migrate log\nSHOW_LOG=$show_log\n" >> .env;

echo "\n\033[44;37m Config success~ \033[0m\n" ;

echo "Prepare to migrate QUIVER notes, \033[31m $hexo_path/source/_post will be emptied \033[0m, whether to continue? [y/n]:\c" ;
read response ;
while [[ "$response" != 'y' ]] && [[ "$response" != 'n' ]]
do
	echo "whether to continue? type y or n:\c" ;
	read response ;
done

if [ "$response" = 'y' ]
then
   php sync.php ;
   echo "\n\033[44;37m Migration success~~ \033[0m\n" ;
else
   echo "Execute \033[32mphp sync.php \033[0mif you need to sync QUIVER notes"
fi

echo "Execute \033[32mphp sync.php -h\033[0m checkout sync and deploy instructions  \n" ;






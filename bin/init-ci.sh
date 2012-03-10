#/bin/sh

curl -s http://getcomposer.org/installer | php
php composer.phar install

mkdir -p vendor
wget -dv http://ezcomponents.org/files/downloads/ezcomponents-2009.2.1-lite.tar.bz2
tar -xjf ezcomponents-2009.2.1-lite.tar.bz2 -C vendor
mv vendor/ezcomponents-2009.2.1 vendor/ezc
rm ezcomponents-2009.2.1-lite.tar.bz2

git clone git://github.com/ezyang/htmlpurifier.git vendor/htmlpurifier
php -f vendor/htmlpurifier/maintenance/generate-standalone.php

git clone git://github.com/svenax/ZendFramework.git vendor/zf1

git clone git://github.com/doctrine/doctrine1.git vendor/doctrine1

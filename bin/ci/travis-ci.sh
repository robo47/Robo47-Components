mkdir -p vendor
wget -dv http://ezcomponents.org/files/downloads/ezcomponents-2009.2.1-lite.tar.bz2
tar -xzvf ezcomponents-2009.2.1-lite.tar.bz2 -c vendor
mv vendor/ezcomponents-2009.2.1-lite vendor/ezc
rm ezcomponents-2009.2.1-lite.tar.bz2

git clone git://github.com/ezyang/htmlpurifier.git vendor/htmlpurifier
php -f vendor/htmlpurifier/maintenance/generate-standalone.php

git clone git://github.com/svenax/ZendFramework.git vendor/zf1

git clone git://github.com/doctrine/doctrine1.git vendor/doctrine1
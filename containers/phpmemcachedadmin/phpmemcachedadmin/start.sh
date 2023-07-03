#!/bin/bash

if [ ! -d "/phpmemcachedadmin/Config" ]; then
    mv /usr/share/nginx/html/Config /phpmemcachedadmin
    ln -s /phpmemcachedadmin/Config /usr/share/nginx/html/
else
    rm -fr /usr/share/nginx/html/Config
    ln -s /phpmemcachedadmin/Config /usr/share/nginx/html/
fi

/usr/sbin/php5-fpm -D && /usr/sbin/nginx &

cd /usr/share/nginx/html 
ln -sf . memcachedadmin
cd -
tail -f /dev/null
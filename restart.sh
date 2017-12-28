#!/bin/bash


PIDFILE="/tmp/auto_close_box.pid"

echo 'searching proccess...'

count=`ps -ef | grep "AutoCloseBox" | grep -v "grep AutoCloseBox" | wc -l`

echo 'finding process count:'${count}

kill `cat $PIDFILE`
rm -f $PIDFILE

echo 'proccess shutdown...'

/usr/local/php7/bin/php /data/web/anxinchong/artisan AutoCloseBox

echo 'proccess start...'
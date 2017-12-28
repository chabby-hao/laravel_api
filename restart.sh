#!/bin/bash


PIDFILE="/tmp/Daemon_artisian.pid"

cmd=`ps -ef | grep "AutoCloseBox" | grep -v "grep AutoCloseBox"| wc -l`

echo $cmd

kill `cat $PIDFILE`
rm -f $PIDFILE

echo 'proccess shutdown'

cmd2=`/usr/local/php7/bin/php /data/web/anxinchong/artisan AutoCloseBox`

echo $cmd2

echo 'proccess start'
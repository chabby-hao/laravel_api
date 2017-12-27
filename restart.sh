#!/bin/bash


PIDFILE="/tmp/Daemon_artisian.pid"

cmd=`ps -ef | grep "cli.php main" | grep -v "grep cli.php main"| wc -l`

echo $cmd

kill `cat $PIDFILE`
rm -f $PIDFILE

echo 'proccess exit(0)'
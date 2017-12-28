#!/bin/bash


PIDFILE="/tmp/auto_close_box.pid"

cmd=`ps -ef | grep "AutoCloseBox" | grep -v "grep AutoCloseBox"| wc -l`

echo $cmd

kill `cat $PIDFILE`
rm -f $PIDFILE

echo 'proccess shutdown'




echo 'proccess exit(0)'
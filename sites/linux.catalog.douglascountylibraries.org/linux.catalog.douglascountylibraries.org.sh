#!/bin/sh
# set local configuration for starting Solr and then start solr
#Replace {servername} with your server name and save in sites/{servername} as {servername.sh} 
export VUFIND_HOME=/var/www/VuFind-Plus/sites/vufind-linux.dcl.lan
export JETTY_HOME=/var/www/VuFind-Plus/sites/vufind-linux.dcl.lan/solr/jetty
export SOLR_HOME=/var/www/VuFind-Plus/sites/vufind-linux.dcl.lan/solr     
export JETTY_PORT=8080
#Max memory should be at least he size of all solr indexes combined. 
export JAVA_OPTIONS="-server -Xms1024m -Xmx6144m -XX:+UseParallelGC -XX:NewRatio=5"
export JETTY_LOG=/var/www/VuFind-Plus/sites/vufind-linux.dcl.lan/logs/jetty

exec /var/www/VuFind-Plus/sites/default/vufind.sh $1 $2

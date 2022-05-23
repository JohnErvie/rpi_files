#!/bin/sh
# mysql-backup.sh: Dump MySQL databases.
# Note: Test only on RHEL/CentOS/Debian/Ubuntu Linux.
# Author: nixCraft <www.cyberciti.biz> Under GPL v2.0+
# -----------------------------------------------------
NOW=$(date +"%d-%m-%Y")
BAK="/root/mysql-files/$NOW"
 
##################
## SET ME FIRST ##
##################
MUSER="admin"
MPASS="password"
MHOST="localhost"
 
MYSQL="$(which mysql)"
MYSQLDUMP="$(which mysqldump)"
GZIP="$(which gzip)"
 
if [ ! -d $BAK ]; then
  mkdir -p $BAK
else
 :
fi
 
DBS="$($MYSQL -u $MUSER -h $MHOST -p$MPASS -Bse 'show databases')"
echo -n "Dumping...${THISDB}..."
for db in $DBS
do
 echo -n "$db "
 FILE=$BAK/mysql-$db.$NOW-$(date +"%T").gz
 $MYSQLDUMP -u $MUSER -h $MHOST -p$MPASS $db | $GZIP -9 > $FILE
done
echo -n  "...Done @ ${BAK} directory."
echo ""

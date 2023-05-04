/usr/bin/mysqld_safe &
sleep 5
mysql -u root -e "CREATE DATABASE mydb;"
mysql -u root mydb < /tmp/import.sql

sleep 1

mysql -u root -e "CREATE USER 'admin'@'%' IDENTIFIED BY 'password';GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%' WITH GRANT OPTION;FLUSH PRIVILEGES;"
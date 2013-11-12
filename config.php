<?php
$chanName = "nullChan";
$db_server="localhost";
$db_user="root";
$db_pass="pass";
$db="chan";
$url = "http://vartan.net46.net/chan/";

mysql_connect($db_server,$db_user,$db_pass) or die(mysql_error('test'));
mysql_select_db($db) or die(mysql_error());
?>
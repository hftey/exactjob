<?php

$dbFile = '/var/www/html/raymond/exact/exactjob/Backup/exactjob'.date('Ymd').'.sql.gz';
$dbHost = 'localhost'; // Database Host
$dbUser = 'root'; // Database Username
$dbPass = 'qwe098poi'; // Database Password
exec( 'mysqldump --host="'.$dbHost.'" --user="'.$dbUser.'" --password="'.$dbPass.'" --add-drop-table "exact_job" | gzip > "'.$dbFile.'"' );

?>

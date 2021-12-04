<?php
$serverName = "192.168.11.200"; //serverName\instanceName
$connectionInfo = array( "Database"=>"ESELIVE", "UID"=>"mob", "PWD"=>"P@$$mob");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
 
if( $conn ) {
echo "Connection established.<br />";
}else{
echo "Connection could not be established.<br />";
die( print_r( sqlsrv_errors(), true));
}
?>
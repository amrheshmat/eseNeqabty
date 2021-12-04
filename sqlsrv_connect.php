<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$serverName = "192.168.11.200";
$connectionOptions = array(
    "Database" => "ESELIVE",
    "Uid" => "mob",
    "PWD" => "P@$$mob"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);
if($conn === false )
{ print "Working"; } else { print "Error"; }

?>
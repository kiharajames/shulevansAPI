<?php
$servername = "localhost";
$username = "shulevan_begress";
$password = "Begress123#";
$dbname = "shulevan_foxx-tours-v1";

$conn = new PDO('mysql:host='. $servername.'; dbname='.$dbname.'', $username, $password);
//Set some db attributes
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

define('appname', 'SHULEVANS REST API');

?>

<?php

function getDatabaseConnection()
{
$host = "localhost";
$dbname = "anto1513";
//your otterid
$username = "anto1513";
$password = "s3cr3t";

//creates connection to database
$dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
// Setting Errorhandling to Exception
$dbConn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

return $dbConn;
}

function getDataBySQL($sql)
{
	global $conn;
	$statement = $conn -> prepare($sql);
	$statement -> execute();
	$records = $statement -> fetchAll(PDO::FETCH_ASSOC);
	
	return $records;
}
?>
<?php

include 'includes/database.inc.php';

$conn = getDatabaseConnection();
//gets database connection

if (isset($_GET['name'])) {
	$name =$_GET['name'];
	$sql = "SELECT phone, email,name
    FROM gp_Customer WHERE name = :name" ;
		$namedParameters[":name"] = $name;
		$statement = $conn -> prepare($sql);
		$statement -> execute($namedParameters);
		$records = $statement -> fetchAll(PDO::FETCH_ASSOC);
	foreach ($records as $record) {
		echo "Phone: " . $record['phone'] . "<br />";
		echo "Email: " . $record['email'] . "<br />";
	}
}
?>

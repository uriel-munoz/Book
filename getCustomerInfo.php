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
		echo "<div class='productName'><a class='title'>NAME</a></div>";
		echo "<div style='text-align: center; font-size: 20px; color: green; font-weight: 600; height: 30px;'>" . $record['name'] . "</div>" . "<br />";
		
		echo "<div class='productName'><a class='title'>PHONE</a></div>";
		echo "<div style='text-align: center; font-size: 20px; color: green; font-weight: 600; height: 30px;'>" . $record['phone'] . "</div>" . "<br />";
		
		echo "<div class='productName'><a class='title'>E-MAIL</a></div>";
		echo "<div style='text-align: center; font-size: 20px; color: green; font-weight: 600; height: 30px;'>" . $record['email'] . "</div>" . "<br />";
	}
}
?>

<link href="css/styles.css" rel="stylesheet">

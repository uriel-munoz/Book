<?php

include 'includes/database.inc.php';

$conn = getDatabaseConnection();
//gets database connection

if (isset($_GET['name'])) {
	$sql = "SELECT phone, email
    FROM gp_Customer WHERE name = " . $_GET['name'];
	$records = getDataBySQL($sql);
	foreach ($records as $record) {
		echo "Phone: " . $record['phone'] . "<br />";
		echo "Email: " . $record['email'] . "<br />";
	}
}
?>

<?php

include 'includes/database.inc.php';

$conn = getDatabaseConnection();
//gets database connection

if (isset($_GET['ISBN'])) {
	$sql = "SELECT ISBN, summary,title
    FROM gp_Books WHERE ISBN = " . $_GET['ISBN'];
	$records = getDataBySQL($sql);
	foreach ($records as $record) {
		echo "<div class='productName'><a class='title'>TITLE</a></div>";
		echo "<br/>";
		echo "<div style='text-align: center; font-size: 20px; color: green; font-weight: 600; height: 70px;'>" . $record['title'] . "</div>" . "<br />";
		
		echo "<div class='productName'><a class='title'>SUMMARY</a></div>";
		echo "<br/>";
		echo "<div style='text-align: center; font-size: 15px; color: green; font-weight: 600'>" . $record['summary'] . "</div>" . "<br />";
	}
}
?>
<link href="css/styles.css" rel="stylesheet">
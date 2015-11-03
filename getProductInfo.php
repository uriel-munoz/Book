<?php

include 'includes/database.inc.php';

$conn = getDatabaseConnection();
//gets database connection

if (isset($_GET['ISBN'])) {
	$sql = "SELECT ISBN, summary,title
    FROM gp_Books WHERE ISBN = " . $_GET['ISBN'];
	$records = getDataBySQL($sql);
	foreach ($records as $record) {
		echo "Title: " . $record['title'] . "<br />";
		echo "Summary: " . $record['summary'] . "<br />";
	}
}
?>

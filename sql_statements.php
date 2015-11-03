<?php


function displayAuthor() {
	$sql = "SELECT DISTINCT author
        FROM gp_Books WHERE 1";
	$records = getDataBySQL($sql);
	foreach ($records as $record) {
		echo "<option value = '" . $record['author'] . "'>" . $record['author'] . "</option>";
	}
}

function sortByAuthor() {
	$author = $_GET['Author'];
	
	$sql = "SELECT title, author, summary
			FROM gp_Books
			WHERE author = " . $author;
			
	$records = getDataBySQL($sql);
	return $records;
}


function orderByTitle() {
	
	$sql = "SELECT 	a.title, a.author
FROM 	gp_Books a, gp_Customer b, gp_Order c
WHERE 	b.cust_Id = c.cust_Id
AND	c.ISBN = a.ISBN
GROUP BY	a.title
ORDER BY	a.title ASC";

			
	$records = getDataBySQL($sql);
	return $records;
}

function orderByAuthor() {
	
	$sql = "SELECT 	a.title, a.author
FROM 	gp_Books a, gp_Customer b, gp_Order c
WHERE 	b.cust_Id = c.cust_Id
AND	c.ISBN = a.ISBN
GROUP BY	a.author
ORDER BY	a.author ASC;";

			
	$records = getDataBySQL($sql);
	return $records;
}


?>
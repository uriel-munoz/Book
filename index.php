<?php
include 'includes/database.inc.php';

$conn = getDatabaseConnection();
//gets database connection

function displayAuthor() {
	$sql = "SELECT DISTINCT author
        FROM gp_Books WHERE 1";
	$records = getDataBySQL($sql);
	foreach ($records as $record) {
		echo "<option value = '" . $record['author'] . "'>" . $record['author'] . "</option>";
	}
}

function displayAllProducts() {
	$sql = "SELECT title, author, name, quantity,ISBN
			FROM gp_Customer
			NATURAL JOIN gp_Order
			NATURAL JOIN gp_Books";

	$records = getDataBySQL($sql);
	return $records;
	/*
	 foreach ($records as $record) {
	 echo $record['productName'] . "-" . $record['price'] . "<br>";
	 }*/

}

function filterProducts() {
	global $conn;
	if (isset($_GET['searchAuthor'])) {

		$author = $_GET['Author'];

		$sql = "SELECT title, author, summary,ISBN
				FROM gp_Books 
				WHERE author = :author";
		$namedParameters[":author"] = $author;
		$statement = $conn -> prepare($sql);
		$statement -> execute($namedParameters);
		$records = $statement -> fetchAll(PDO::FETCH_ASSOC);
		return $records;
	} else if (isset($_GET['searchForm'])) {//user submitted the filter form

		$sort = $_GET['orderBy'];

		if ($sort == "Title") {
			$records = orderByTitle();
		} else {
			$records = sortByAuthor();

		}
		return $records;
		$author = $_GET['Author'];

		$sql = "SELECT title, author, summary,ISBN, name,quantity
				FROM gp_Books NATURAL JOIN gp_Customer NATURAL JOIN gp_Order
				WHERE author = :author";
		//using Named Parameters (prevents SQL injection)

		$namedParameters = array();
		$namedParameters[":author"] = $author;
		$statement = $conn -> prepare($sql);
		$statement -> execute($namedParameters);
		$records = $statement -> fetchAll(PDO::FETCH_ASSOC);
		return $records;
	}

}

function sortByAuthor() {

	$sql = "SELECT title, author, summary,ISBN
			FROM gp_Books
			WHERE 1 ORDER BY author";

	$records = getDataBySQL($sql);
	return $records;
}

function orderByTitle() {

	$sql = "SELECT 	a.title, a.author, a.summary, a.ISBN
			FROM 	gp_Books a, gp_Customer b, gp_Order c
			WHERE 	b.cust_Id = c.cust_Id
			AND	c.ISBN = a.ISBN
			GROUP BY	a.title
			ORDER BY	a.title ASC";

	$records = getDataBySQL($sql);
	return $records;
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<style>
			@import url(css/styles.css);
		</style>
		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>Books</title>
		<meta name="description" content="">
		<meta name="author" content="anto1513">
		<meta name="viewport" content="width=device-width; initial-scale=1.0">

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico">
		<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	</head>

	<body>
		<div id="wrapper">
			<div id="header">
				<h1>BOOKS FOR ALL</h1>
			</div>
			<div id="menu">
				<div id="selectionPart_A">
					<form method = "get" action = "index.php">
						<span style="font-weight: 900">SELECT AUTHOR :&nbsp;</span>
						<select name= "Author" style="width: 180px;">
							<!--this data will come from the database-->
							<?=displayAuthor() ?>
						</select>

						<input type="submit" value="Search by Author" name="searchAuthor" style="width: 120px; font-weight: 600"/>
						<br/>

						<span style="font-weight: 900; margin-right: 2.8px;">ORDER BY &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</span>
						<select name="orderBy" style="width: 180px;">
							<option value="Title">Title</option>
							<option value="Author">Author</option>
						</select>

						<input type="submit" value="Sort Books" name="searchForm" style="width: 120px; font-weight: 600"/>
				</div>
				<div id="selectionPart_B">
					<input type="submit" value="Display All" name="Di" style="width:100px; height: 44px; margin-top: 2px; font-weight: 600"/>
				</div>
				<div style="clear:both"></div>
				</form>
			</div>
			<hr>

			<div id="contents">
				<div id="tableShowed">
					<?php

					//Displays all products by default
					if (!isset($_GET['searchForm']) || !isset($_GET['searchAuthor'])) {
						$records = displayAllProducts();

					} else {

						$records = filterProducts();
					}

					if (isset($_GET['searchAuthor']) || isset($_GET['searchForm'])) {
						$records = filterProducts();

						echo "<table border = 0>";
						echo "<tr>";
						echo "<td>";
						echo "<span class='title'>TITLE<span>";
						echo "</td>";
						echo "<td>";
						echo "<span class='title'>AUTHOR<span>";
						echo "</td>";
						echo "</tr>";

						foreach ($records as $record) {
							echo "<tr>";
							echo "<td>";
							echo "<a target = 'getProductIframe' href='getProductInfo.php?ISBN=" . $record['ISBN'] . "'>";
							echo $record['title'];
							echo "</a>";
							echo "</td>";
							echo "<td>";
							echo "<span>";
							echo $record['author'];
							echo "</span>";
							echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
					} else {
						echo "<table border = 0>";
						echo "<tr>";
						echo "<td>";
						echo "<span class='title'>TITLE<span>";
						echo "</td>";
						echo "<td>";
						echo "<span class='title'>AUTHOR<span>";
						echo "</td>";
						echo "<td>";
						echo "<span class='title'>CUSTOMER<span>";
						echo "</td>";
						echo "<td>";
						echo "<span class='title'>QUANTITY<span>";
						echo "</td>";
						echo "</tr>";

						foreach ($records as $record) {
							echo "<tr>";
							echo "<td>";
							echo "<a target = 'getProductIframe' href='getProductInfo.php?ISBN=" . $record['ISBN'] . "'>";
							echo $record['title'];
							echo "</a>";
							echo "</td>";
							echo "<td>";
							echo "<span>";
							echo $record['author'];
							echo "</span>";
							echo "</td>";
							echo "<td>";
							echo "<a target = 'getCustomerIframe' href='getCustomerInfo.php?name=" . $record['name'] . "'>";
							echo "" . $record['name'];
							echo "</td>";
							echo "<td>";
							echo "<span>";
							echo $record['quantity'];
							echo "</span>";
							echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
					}
					?>
				</div>
				<div id="informationShowed">
					<div id="productInfo">
						<iframe src="getProductInfo.php" name="getProductIframe" width="280" height="400" frameborder="0"/>
						</iframe>
					</div>
					<hr>
					<div id="customerInfo">
						<iframe src="getCustomerInfo.php" name="getCustomerIframe" width="280" height="258" frameborder="0"/>
						</iframe>
					</div>
				</div>
				<div style="clear: both"></div>

			</div>

			<div id="footer">
				<hr/>
				Disclaimer: The information included in this page might not be accurate. It was developed as part of the CST336 class.
				<br />
				&copy; Anthony, Daniel, Uriel, Yoo, 2015
				<br />
				<img src="../../labs/lab1/img/csumb-logo.png" /><!--using relative path-->
			</div>
		</div>
	</body>
</html>
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

		$maxPrice = $_GET['maxPrice'];

		if (!empty($maxPrice)) {//the user entered a max price value in the form

			//$sql = $sql . " ";
			$sql .= " AND price <= :price";
			//using named parameters
			$namedParameters[":price"] = $maxPrice;

		}
		if (isset($_GET['healthyChoice'])) {
			$sql .= " AND healthyChoice = 1";
		}

		$orderByFields = array("price", "productName");
		$orderByIndex = array_search($_GET['orderBy'], $orderByFields);

		//$sql .= " ORDER BY " . $_GET['orderBy'];

		$sql .= " ORDER BY " . $orderByFields[$orderByIndex];

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
		<div>
			<header>
				<h1>Books for All</h1>
			</header>

			<form method = "get" action = "index.php">
				Select Author:
				<select name= "Author">
					<!--this data will come from the database-->

					<?=displayAuthor() ?>
					<!--
					<option value = "1">Soft Drinks</option>
					<option value = "2"> Snacks</option>
					<option value = "3"> Sandwiches </option>-->

				</select>
				<input type="submit" value="Search by Author" name="searchAuthor" />
				<br/>

				<!--
				Customer:
				<input type="text" name="customer" value="<?=$_GET['customer'] ?>">
				<input type="submit" value="Search Customer" name="searchCustomer" />
				<br/>-->

				OrderBy:
				<select name="orderBy">
					<option value="Title">Title</option>
					<option value="Author">Author</option>

				</select>

				<input type="submit" value="Sort Books" name="searchForm" />
				<br/>
				<input type="submit" value="Display All" name="Di" />
			</form>

			<hr>
			<br />
			<div style="float:left">
				<?php

				//Displays all products by default
				if (!isset($_GET['searchForm']) || !isset($_GET['searchAuthor'])) {
					$records = displayAllProducts();

				} else {

					$records = filterProducts();
				}

				if (isset($_GET['searchAuthor']) || isset($_GET['searchForm'])) {
					$records = filterProducts();

					echo "<table border = 1>";
					echo "<tr>";
					echo "<td id = 'colTitle'>";
					echo "Title";
					echo "</td>";
					echo "<td id = 'colTitle'>";
					echo "Author";
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
						echo "" . $record['author'];
						echo "</td>";
						echo "</tr>";
					}
					echo "</table>";
				} 
				else {
					echo "<table border = 1>";
					echo "<tr>";
					echo "<td id = 'colTitle'>";
					echo "Title";
					echo "</td>";
					echo "<td id = 'colTitle'>";
					echo "Author";
					echo "</td>";
					echo "<td id = 'colTitle'>";
					echo "Customer";
					echo "</td>";
					echo "<td id = 'colTitle'>";
					echo "quantity";
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
						echo "" . $record['author'];
						echo "</td>";
						echo "<td>";
						echo "<a target = 'getCustomerIframe' href='getCustomerInfo.php?name=" . $record['name'] . "'>";
						echo "" . $record['name'];
						echo "</td>";
						echo "<td>";
						echo "" . $record['quantity'];
						echo "</td>";
						echo "</tr>";
					}
					echo "</table>";
				}
				?>
			</div>
			<div style="float:left">

				<iframe src="getProductInfo.php" name="getProductIframe" width="250" height="300" frameborder="0"/>
				</iframe>

				<iframe src="getCustomerInfo.php" name="getCustomerIframe" width="250" height="300" frameborder="0"/>
				</iframe>

			</div>

		</div>

		<footer style="clear:left">
			<hr>
			<br />

			<br />
			<img src="../../img/csumb-logo.png" alt="California State University Monterey Bay Logo" />
		</footer>
		</div>
	</body>

	</body>
</html>
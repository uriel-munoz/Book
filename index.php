<?php

include 'includes/database.inc.php';

$conn = getDatabaseConnection();
//gets database connection

function displayCategories() {
	$sql = "SELECT DISTINCT author
        FROM gp_Books WHERE 1";
	$records = getDataBySQL($sql);
	foreach ($records as $record) {
		echo "<option value = '" . $record['author'] . "'>" . $record['author'] . "</option>";
	}
}

function displayAllProducts() {
	$sql = "SELECT title, author FROM gp_Books";

	$records = getDataBySQL($sql);
	return $records;
	/*
	 foreach ($records as $record) {
	 echo $record['productName'] . "-" . $record['price'] . "<br>";
	 }*/

}

function filterProducts() {
	global $conn;
	if (isset($_GET['searchForm'])) {//user submitted the filter form

		$categoryId = $_GET['categoryId'];

		//This is the WRONG way to create queries because it allows SQL injection
		/*
		 $sql = "SELECT productName, price
		 FROM oe_product
		 WHERE categoryId = '" . $categoryId . "'" ;
		 */

		$sql = "SELECT productName, price, productId 
                FROM oe_product
                WHERE categoryId = :categoryId";
		//using Named Parameters (prevents SQL injection)

		$namedParameters = array();
		$namedParameters[":categoryId"] = $categoryId;

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

function isHealthyChoiceChecked() {

	if (isset($_GET['healthyChoice'])) {
		return "checked";
	}

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

		<title>Lab 5</title>
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
				<h1>Otter Experess-Select Product</h1>
			</header>

			<form method = "get" action = "index.php">
				Select Author:
				<select name= "categoryId">
					<!--this data will come from the database-->

					<?=displayCategories() ?>
					<!--
					<option value = "1">Soft Drinks</option>
					<option value = "2"> Snacks</option>
					<option value = "3"> Sandwiches </option>-->

				</select>

				Max price:
				<input type="number" min="0"  name="maxPrice" value="<?=$_GET['maxPrice'] ?>">

				<input type="checkbox" name="healthyChoice" id="healthyChoice"  <?=isset($_GET['healthyChoice']) ? "checked" : "" ?> />
				<label for="healthyChoice">Healthy Choice</label>

				OrderBy:
				<select name="orderBy">
					<option value="price">Price</option>
					<option value="productName">Name</option>

				</select>
				<br />
				<input type="submit" value="Search Products" name="searchForm" />
			</form>

			<hr>
			<br />
			<div style="float:left">
				<?php

				//Displays all products by default
				if (!isset($_GET['searchForm'])) {
					$records = displayAllProducts();

				} else {
					$records = filterProducts();
				}

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
					//echo "<a target = 'getProductIframe' href='getProductInfo.php?productId=" . $record['productId'] . "'>";
					echo $record['title'];
					echo "</a>";
					echo "</td>";
					echo "<td>";
					echo "" . $record['author'];
					echo "</td>";
					echo "</tr>";
				}
				echo "</table>";
				?>
			</div>
			<div style="float:left">

				<iframe src="getProductInfo.php" name="getProductIframe" width="250" height="300" frameborder="0"/>
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
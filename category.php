<?php
	require_once("support.php");
	session_start();

	$body = "";

	if (isset($_SESSION['email']) && isset($_SESSION['categoryName'])) {
		$body .= <<<PAGE
<script>
	document.getElementById("rightNavBar").innerHTML = "<form action=\"/Terpslist/index.php\" method=\"POST\"><input type=\"submit\" class=\"btn btn-default\" name=\"logout\" id=\"logout\" value=\"Logout\"></form>";
</script>
PAGE;

	$body .= categoryContent();
	} else {
		header("Location: index.php");
	}


	echo createPage($body, "categories");


	function categoryContent () {
		$currCategoryName = ucfirst($_SESSION['categoryName']);
		$categoryContent = <<<PAGE
		<div class="well" id="mainContent">
			<div class="row" id="rowContent">
				<h2>{$currCategoryName}</h2>
PAGE;
		require_once("db_login.php");
		$host = "localhost";
		$user = "dbuser";
		$password = "goodbyeWorld";
		$database = "terpslistaccounts";
		$db = connectToDB($host, $user, $password, $database);

		$query = sprintf("SELECT * FROM `posts` WHERE category= '%s' ORDER BY date DESC", $_SESSION['categoryName']);

		$result = mysqli_query($db, $query);

		if ($result) {

			while ($recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$curr_posting_title = $recordArray["posting_title"];
				$curr_price = $recordArray["price"];
				$curr_desc = $recordArray["description"];
				$curr_path = $recordArray["image_path"];

				$categoryContent .= <<<PAGE
<div class="col-md-4" id="panelContainer">
	<div id="frontPanels">
		<div class="panel panel-default">
			<div class="panel-heading"><a href="index.php?postName={$curr_posting_title}"><h4>{$curr_posting_title}</h4></a> Price: {$curr_price}</div>
			<div class="panel-body" id="panelBody"><a href="index.php?postName={$curr_posting_title}"><img src="{$curr_path}" class="img-rounded" alt="{$curr_posting_title}" id="stream_img"></a><br>{$curr_desc}</div>
		</div>
	</div>
</div>
PAGE;
			}


		} else {	
			$categoryContent .= "<h3>No content!</h3>";
		}



	
		$categoryContent .= <<<PAGE
			</div>
		</div>

<script>
			document.getElementById("rightNavBar").innerHTML = "<form action=\"index.php\" method=\"POST\"><input type=\"submit\" class=\"btn btn-default\" name=\"logout\" id=\"logout\" value=\"Logout\"></form>";
</script>

PAGE;

		return $categoryContent;
	}
?>
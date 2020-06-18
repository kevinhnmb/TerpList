<?php
	require_once("support.php");
	session_start();

	$body = "";

	if (isset($_SESSION['email']) && !isset($_GET['categoryName'])) {

		$body .= <<<PAGE
<script>
	document.getElementById("rightNavBar").innerHTML = "<form action=\"/Terpslist/index.php\" method=\"POST\"><input type=\"submit\" class=\"btn btn-default\" name=\"logout\" id=\"logout\" value=\"Logout\"></form>";
</script>
PAGE;

		$body .= <<<PAGE
<div class="well" id="mainContent">
	<div class="row" id="rowContent">
	<h2>Categories</h2>

		<div class="col-md-4" id="panelContainer">
			<div id="frontPanels">
				<div class="panel panel-default">
					<div class="panel-heading"><a href="categories.php?categoryName=electronics"><h4>Electronics</h4></a></div>
					<div class="panel-body" id="panelBody">
						<center><a href="categories.php?categoryName=electronics"><img src="images/electronics.jpg" class="img-rounded" alt="Electronics" id="stream_img"></a><center>
						<br>Laptops, cellphones, computer parts and more!<br>&nbsp;&nbsp;<br>&nbsp;&nbsp;</div>
				</div>
			</div>
		</div>

		<div class="col-md-4" id="panelContainer">
			<div id="frontPanels">
				<div class="panel panel-default">
					<div class="panel-heading"><a href="categories.php?categoryName=textbooks"><h4>Textbooks</h4></a></div>
					<div class="panel-body" id="panelBody">
						<center><a href="categories.php?categoryName=textbooks"><img src="images/textbooks.jpg" class="img-rounded" alt="Textbooks" id="stream_img"></a></center>
						<br>Cheap textbooks for school!</div>
				</div>
			</div>
		</div>

		<div class="col-md-4" id="panelContainer">
			<div id="frontPanels">
				<div class="panel panel-default">
					<div class="panel-heading"><a href="categories.php?categoryName=clothes"><h4>Clothes</h4></a></div>
					<div class="panel-body" id="panelBody">
						<center><a href="categories.php?categoryName=clothes"><img src="images/clothes.jpg" class="img-rounded" alt="Clothes" id="stream_img"></a></center>
						<br>Sell or donate any of your unwanted clothes!</div>
				</div>
			</div>
		</div>

		<div class="col-md-4" id="panelContainer">
			<div id="frontPanels">
				<div class="panel panel-default">
					<div class="panel-heading"><a href="categories.php?categoryName=athletic"><h4>Athletic Equipment</h4></a></div>
					<div class="panel-body" id="panelBody"><a href="categories.php?categoryName=athletic">
						<center><img src="images/athletic.jpg" class="img-rounded" alt="Athletic Equipment" id="stream_img"></a></center>
						<br>Hocket, Soccer, Football, sports...<br>&nbsp;&nbsp;</div>
				</div>
			</div>
		</div>

		<div class="col-md-4" id="panelContainer">
			<div id="frontPanels">
				<div class="panel panel-default">
					<div class="panel-heading"><a href="categories.php?categoryName=furniture"><h4>Furniture</h4></a></div>
					<div class="panel-body" id="panelBody"><a href="categories.php?categoryName=furniture">
						<center><img src="images/furniture.jpg" class="img-rounded" alt="Furniture" id="stream_img"></a></center>
						<br>Desks, desk chairs, beds and more!</div>
				</div>
			</div>
		</div>

		<div class="col-md-4" id="panelContainer">
			<div id="frontPanels">
				<div class="panel panel-default">
					<div class="panel-heading"><a href="categories.php?categoryName=tickets"><h4>Tickets</h4></a></div>
					<div class="panel-body" id="panelBody"><a href="categories.php?categoryName=tickets">
						<center><img src="images/tickets.jpg" class="img-rounded" alt="Tickets" id="stream_img"></a></center>
						<br>Tickets to sport events, concerts, etc...</div>
				</div>
			</div>
		</div>
	</div>
</div>
PAGE;

	} else if (isset($_SESSION['email']) && isset($_GET['categoryName'])) {
		$_SESSION['categoryName'] = $_GET['categoryName'];
		header("Location: category.php");
	} else {
		header("Location: index.php");
	}


	echo createPage($body, "categories");

?>
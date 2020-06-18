<?php
	session_start(); 

	require_once("support.php");
	$body = "";

	if (!isset($_SESSION['email']) && !isset($_POST['submitLogin'])) {
		$body .= displayLoginForm();
	} else if (isset($_POST['submitLogin'])) {
		$body .= validateLogin();
	}  else if (isset($_POST['logout'])) {
		session_destroy();
		header("Location: index.php");
	} else if (isset($_GET['postName'])) {	
		$_SESSION['postingName'] = $_GET['postName'];	
		header("Location: single_post.php");
	} else {
		$body .= loggedInFrontPage();
	}

	function displayLoginForm () {
		$form = <<<PAGE
<div class="well well-sm" id="loginForm">
	<form action="index.php" method="POST">
		<div class="form-group">
			<center><p><h4>Log in to Terp's List!</h4></p></center>

			<label for="email">Email:</label>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		      	<input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
		    </div>
			<label for="pass">Password:</label>
			<div class="input-group">
		      	<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
		      	<input type="password" class="form-control" id="pass" placeholder="Enter password" name="pass">
		    </div>
		    <br>
			<input type="submit" class="btn btn-default" name="submitLogin" id="submitLogin" value="Login">
		</div>
	</form>
	<a href="register.php"><button class="btn btn-link">Register</button></a>
	<br><br>
	<div id="alertWarning">
	</div>
</div>

<script>
	window.onsubmit=validateLogin;

	function validateLogin () {
		let username = document.getElementById("email").value;
		let password = document.getElementById("pass").value;

		let umdEmail = username.split("@")[1];

		if ((String(username).length !== 0 || String(password).length !== 0) && (umdEmail === "umd.edu")) {
			return true;
		} else {
			document.getElementById("alertWarning").innerHTML = "<div class=\"alert alert-warning alert-dismissable\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Please enter valid email and password.</div>";
			return false;
		}
	}
</script>

PAGE;

	return $form;
}

	function validateLogin () {
		$validation = "";
		require_once("db_login.php");
		$db = connectToDB($host, $user, $password, $database);

		$query = sprintf("select * from users where email = '%s'", htmlentities($_POST['email']));
		$result = mysqli_query($db, $query);
		$recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

		if (!empty($recordArray) && password_verify(htmlentities($_POST['pass']), $recordArray['password'])) {
			$_SESSION['email'] = $_POST['email'];
			$validation .= loggedInFrontPage();
		} else {
			$validation .= displayLoginForm();
			$validation .= <<<PAGE
<script>
	document.getElementById("alertWarning").innerHTML = "<div class=\"alert alert-warning alert-dismissable\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Please enter valid email and password.</div>";
</script>
PAGE;
		}

		return $validation;
	}

	function loggedInFrontPage () {
		require_once("post.php");
		$frontPage = <<<PAGE
		<div class="well" id="mainContent">
			<div class="row" id="rowContent">
				<h2>Most Recent Activity</h2>
PAGE;
		require_once("db_login.php");
		$host = "localhost";
		$user = "dbuser";
		$password = "goodbyeWorld";
		$database = "terpslistaccounts";
		$db = connectToDB($host, $user, $password, $database);

		$query = sprintf("SELECT * FROM `posts` ORDER BY date DESC");

		$result = mysqli_query($db, $query);

		if ($result) {

			while ($recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

				$currPost = new Post($recordArray['posting_title'], $recordArray['price'], $recordArray['image_path'], $recordArray['description'], $recordArray['user_email'], $recordArray['category']);

				$frontPage .= <<<PAGE
<div class="col-md-4" id="panelContainer">
	<div id="frontPanels">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a href="index.php?postName={$currPost->getPostingTitle()}">
				<h4>{$currPost->getPostingTitle()}</h4></a> Price: {$currPost->getPrice()}
			</div>
			<div class="panel-body" id="panelBody">
				<center><a href="index.php?postName={$currPost->getPostingTitle()}"><img src="{$currPost->getImagePath()}" class="img-rounded" alt="{$currPost->getPostingTitle()}" id="stream_img"></a></center>
				<br>{$currPost->getDescription()}
				</div>
		</div>
	</div>
</div>
PAGE;
			}


		} else {	
			$frontPage .= "<h3>No content!</h3>";
		}

		$frontPage .= <<<PAGE
			</div>
		</div>

<script>
			document.getElementById("rightNavBar").innerHTML = "<form action=\"index.php\" method=\"POST\"><input type=\"submit\" class=\"btn btn-default\" name=\"logout\" id=\"logout\" value=\"Logout\"></form>";
</script>

PAGE;

		return $frontPage;
	}


	echo createPage($body, "index");

?>
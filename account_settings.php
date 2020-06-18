<?
	require_once("support.php");
	session_start();

	$body = "";

	if (isset($_SESSION['email']) && isset($_POST['updateAccount'])) {
		$body .= validateUpdate();
		$body .= <<<PAGE
<script>
	document.getElementById("rightNavBar").innerHTML = "<form action=\"/Terpslist/index.php\" method=\"POST\"><input type=\"submit\" class=\"btn btn-default\" name=\"logout\" id=\"logout\" value=\"Logout\"></form>";
</script>
PAGE;
	} else if (isset($_SESSION['email'])) {
		$body .= <<<PAGE
<script>
	document.getElementById("rightNavBar").innerHTML = "<form action=\"/Terpslist/index.php\" method=\"POST\"><input type=\"submit\" class=\"btn btn-default\" name=\"logout\" id=\"logout\" value=\"Logout\"></form>";
</script>
PAGE;

	$body .= createUpdateForm();
	$body .= <<<PAGE
<script>
	document.getElementById("updateTitle").innerHTML = "Update Account Settings";
</script>
PAGE;

	} else {
		header("Location: index.php");
	}






	echo createPage($body, "account_settings");

	function createUpdateForm () {
		require_once("db_login.php");
		$host = "localhost";
		$user = "dbuser";
		$password = "goodbyeWorld";
		$database = "terpslistaccounts";
		$db = connectToDB($host, $user, $password, $database);

		$query = sprintf("SELECT * FROM `users` WHERE email='%s'", $_SESSION['email']);

		$result = mysqli_query($db, $query);

		$recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

		$form = <<<PAGE
<div class="well well-sm" id="updateForm" onsubmit="return validateRegistration()">
	<h3 id="updateTitle"></h3>
	<form method="POST" id="former">

		<label for="email">Email:</label>
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	      	<input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="{$recordArray['email']}">
	    </div>

	    <label for="firstName">Firstname:</label> 
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			<input type="text" class="form-control" id="firstName" placeholder="Firstname" name="firstName" value="{$recordArray['firstName']}">
		</div>


		<label for="lastName">Lastname:</label>
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			<input type="text" class="form-control" id="lastName" placeholder="Lastname" name="lastName" value="{$recordArray['lastName']}">
		</div>

	    <label for="pass">Previous Password:</label>
		<div class="input-group">
	      	<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	      	<input type="password" class="form-control" id="prev_pass" placeholder="Enter previous password" name="prev_pass">
	    </div>

	    <label for="pass">New Password/Confirm Password:</label>
		<div class="input-group">
	      	<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	      	<input type="password" class="form-control" id="pass" placeholder="Enter password" name="pass">
	    </div>
	    <br>
		<input type="submit" class="btn btn-default" id="updateAccount" name="updateAccount" value="Update">
	</form>

	<a href="manage_posts.php"><button class="btn btn-link">Manage my posts.</button></a>
	<br><br>
	<div id="alertWarning">
	</div>
</div>

<script>
	function validateRegistration () {
		let firstName = document.getElementById("firstName").value;
		let lastName = document.getElementById("lastName").value;
		let email = document.getElementById("email").value;
		let previousPassword = document.getElementById("prev_pass").value;
		let newPassword = document.getElementById("pass").value;

		if (String(firstName.trim()).length !== 0 && String(lastName.trim()).length !== 0 && String(email.trim()).length !== 0 && String(previousPassword).length !== 0 && String(newPassword).length !== 0) {

			if (email.split("@")[1] !== "umd.edu") {
				document.getElementById("alertWarning").innerHTML = "<div class=\"alert alert-warning alert-dismissable\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Please enter UMD email.</div>";
				return false;
			} else if (String(previousPassword).length < 8 || String(newPassword).length < 8) {
				document.getElementById("alertWarning").innerHTML = "<div class=\"alert alert-warning alert-dismissable\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Please enter password with a minimum of 8 characters.</div>";
				return false;
			}
			
		} else {
			document.getElementById("alertWarning").innerHTML = "<div class=\"alert alert-warning alert-dismissable\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Please enter all fields.</div>";
			return false;	
		}
	}
</script>
PAGE;
		return $form;
	}

	function validateUpdate () {
		$validation = "";
		require_once("db_login.php");
		$db = connectToDB($host, $user, $password, $database);

		$query = sprintf("select * from users where email = '%s'", htmlentities($_POST['email']));
		$result = mysqli_query($db, $query);
		$recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

		if (!empty($recordArray) && password_verify(htmlentities($_POST['prev_pass']), $recordArray['password'])) {
			$options_array = ['cost' => 11];
			$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT, $options_array);

			$query = sprintf("UPDATE `users` SET `password` = '%s' where `users`. `email` = '%s'", $pass, $_SESSION['email']);

			$result = mysqli_query($db, $query);

			$query = sprintf("UPDATE `users` SET `firstName` = '%s' where `users`. `email` = '%s'", $_POST['firstName'], $_SESSION['email']);

			$result = mysqli_query($db, $query);

			$query = sprintf("UPDATE `users` SET `lastName` = '%s' where `users`. `email` = '%s'", $_POST['lastName'], $_SESSION['email']);

			$result = mysqli_query($db, $query);

			$query = sprintf("UPDATE `users` SET `email` = '%s' where `users`. `email` = '%s'", $_POST['email'], $_SESSION['email']);

			$result = mysqli_query($db, $query);



			$validation .= createUpdateForm();
			$validation .= <<<PAGE
<script>
	document.getElementById("updateTitle").innerHTML = "Account updated!";
</script>
PAGE;

		} else {
			$validation .= createUpdateForm();
			$validation .= <<<PAGE
<script>
	document.getElementById("updateTitle").innerHTML = "Account was not updated.";
</script>
PAGE;

			$validation .= <<<PAGE
<script>
	document.getElementById("alertWarning").innerHTML = "<div class=\"alert alert-warning alert-dismissable\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Please enter correct previous password.</div>";
</script>
PAGE;
		}

		return $validation;

	}

?>
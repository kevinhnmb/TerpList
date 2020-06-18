<?php
	require_once("support.php");
	session_start();

	$body = "";

	if (isset($_POST['submitRegistration'])) {
		$body .= generateConfirmationNotification();
	} else if (isset($_SESSION['email'])) {
		header("Location: index.php");
	} else {
		$body .= createRegisterForm();
	}

	function createRegisterForm () {
		$form = <<<PAGE
<div class="well well-sm" id="registerForm">
	<form action="register.php" method="POST">
		<center><p><h4>Register to Terp's List!</h4></p></center>

		<label for="firstName">Firstname:</label> 
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			<input type="text" class="form-control" id="firstName" placeholder="Firstname" name="firstName">
		</div>


		<label for="lastName">Lastname:</label>
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			<input type="text" class="form-control" id="lastName" placeholder="Lastname" name="lastName">
		</div>

		<label for="email">Email:</label>
		<br>
		(Only UMD emails will be accepted.)
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
			<input type="email" class="form-control" id="email" placeholder="Email" name="email">
		</div>

		<label for="pass">Password</label> 
		<br>(Password must contain 8 characters minimum.)

		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
			<input type="password" class="form-control" id="pass" placeholder="Password" name="pass">
		</div>
		<br>
		<input type="submit" class="btn btn-default" name="submitRegistration" id="submitRegistration" value="Register">
	</form>
	<a href="index.php"><button class="btn btn-link">Login</button></a>
	<br><br>
	<div id="alertWarning">
	</div>
</div>

<script>
	window.onsubmit=validateRegistration;

	function validateRegistration () {
		let firstName = document.getElementById("firstName").value;
		let lastName = document.getElementById("lastName").value;
		let email = document.getElementById("email").value;
		let password = document.getElementById("pass").value;


		if (String(firstName.trim()).length !== 0 && String(lastName.trim()).length !== 0 && String(email.trim()).length !== 0 && String(password).length !== 0) {
			
			if (email.split("@")[1] !== "umd.edu") {
				document.getElementById("alertWarning").innerHTML = "<div class=\"alert alert-warning alert-dismissable\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Please enter UMD email.</div>";
				return false;
			} else if (String(password).length < 8) {
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

	function generateConfirmationNotification () {
		require_once("db_login.php");
		$db = connectToDB($host, $user, $password, $database);

		$options_array = ['cost' => 11];
		$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT, $options_array);

		$query = sprintf("insert into users (firstName, lastName, email, password) values('%s', '%s', '%s', '%s')", trim($_POST['firstName']), trim($_POST['lastName']), trim($_POST['email']), $pass);

		$result = mysqli_query($db, $query);

		$notification = "";

		if ($result) {
			$notification = <<<PAGE
<div class="well well-sm" id="registerForm">
	<center><h2>Thank you for registering!</h2></center><br>

	<center><h3>Please click <a href="index.php">here</a> to sign in!</h3></center>

</div>
PAGE;
		} else {
			$notification .= <<<PAGE
<script>alert("Please use your own unique UMD email.");</script>
PAGE;
			$notification .= createRegisterForm();
		}


		return $notification;
	}

	echo createPage($body, "register");
?>
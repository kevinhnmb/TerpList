<?php
	session_start();
	require_once("support.php");

	$body = "";

	if (isset($_SESSION['email']) && !isset($_POST['uploadContent'])) {
		$body .= generateUploadForm();
	} else if (isset($_SESSION['email']) && isset($_POST['uploadContent'])) {
		$image_file = "images/".basename($_FILES["imageToUpload"]["name"]);
		$uploadFlag = 1;

		$imgType = pathinfo($image_file, PATHINFO_EXTENSION);

		if (!getimagesize($_FILES["imageToUpload"]["tmp_name"]) || $imgType != "jpg") {
			//do not upload file.
			$body .= generateUploadForm();
			$body .= <<<PAGE
<script>
document.getElementById("postingTitle").value = "{$_POST['postingTitle']}";
document.getElementById("price").value = "{$_POST['price']}";
document.getElementById("description").value = "{$_POST['description']}";
document.getElementById("alertWarning").innerHTML = "<div class=\"alert alert-warning alert-dismissable\" id=\"errorUpload\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Please upload an image file. (Extensions: .jpg)</div>";
</script>
PAGE;
		} else {
			if (move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $image_file)) {
		        //UPLOAD CONTENT TO DATEBASE.
				require_once("db_login.php");
				$db = connectToDB($host, $user, $password, $database);

				$query = sprintf("INSERT INTO `posts` (`user_email`, `posting_title`, `price`, `description`, `category`, `image_path`, `date`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", $_SESSION['email'], trim($_POST['postingTitle']), trim($_POST['price']), trim($_POST['description']), trim($_POST['category']), $image_file, date('Y-m-d H:i:s'));

				$result = mysqli_query($db, $query);

				$_SESSION['postingName'] = trim($_POST['postingTitle']);

				header("Location: single_post.php");


		    } else {
		        $body .= generateUploadForm();
		        $body .= <<<PAGE
<script>
document.getElementById("postingTitle").value = "{$_POST['postingTitle']}";
document.getElementById("price").value = "{$_POST['price']}";
document.getElementById("description").value = "{$_POST['description']}";
document.getElementById("alertWarning").innerHTML = "<div class=\"alert alert-warning alert-dismissable\" id=\"errorUpload\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> There was an error uploading your image.</div>";
</script>
PAGE;
		    }

		}

	} else {
		header("Location: index.php");
	}


	echo createPage($body, "upload");


	function generateUploadForm () {
		$form = <<<PAGE
<script>
	document.getElementById("rightNavBar").innerHTML = "<form action=\"index.php\" method=\"POST\"><input type=\"submit\" class=\"btn btn-default\" name=\"logout\" id=\"logout\" value=\"Logout\"></form>";
</script>

<div class="well" id="mainContent">
	<div class="row" id="rowContent">
				<h2>Submit a Post</h2>
	</div>
	<form onSubmit="return validateUploadForm()" action="upload.php" method="POST" enctype="multipart/form-data">
		<center>
		<table>
			<tr>
				<td><label for="postingTitle">Posting Title:</td>
				<td></td>
				<td><label for="price">Price:</label></td>
			</tr>
			<tr>
				<td><input type="text" class="form-control" id="postingTitle" name="postingTitle" placeholder="Please enter a descriptive name." required></td>
				<td><label>&nbsp;&nbsp;&nbsp;$&nbsp;</label></td>
				<td><input type="text" class="form-control" id="price" name="price" placeholder="0.00" required></td>
				<td><label>&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
				<td>
					<select class="form-control" name="category" id="category">
					  <option value="electronics">Electronics</option>
					  <option value="tickets">Tickets</option>
					  <option value="furniture">Furniture</option>
					  <option value="textbooks">Text Books</option>
					  <option value="clothes">Clothes</option>
					  <option value="athletic">Athletic Gear</option>
					</select>
				</td>
			</tr>
		</table><br>
		</center>
		<textarea id="description" name="description" class="form-control" rows="15" placeholder="Enter any addtional information about the item."></textarea>
		<div id="bottomForm">
			<input type="file" name="imageToUpload" id="imageToUpload">
			<br>
	    	<input type="submit" class="btn btn-default" name="uploadContent" id="uploadContent" value="Submit">
	    	</form>
	    	<br>
	    	<br>
			<div id="alertWarning">
	    	</div>
	</div>
</div>

<script>
	function validateUploadForm () {
		let postTitle = document.getElementById("postingTitle").value.trim();
		let price = document.getElementById("price").value.trim();

		if (String(postTitle).length === 0 || String(price).length === 0 || (price.split(".").length !== 2) || isNaN(price.split(".")[0]) || isNaN(price.split(".")[1])) {
			document.getElementById("alertWarning").innerHTML = "<div class=\"alert alert-warning alert-dismissable\" id=\"errorUpload\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Please enter a posting title and a valid price. (Price format: 10.00)</div>";
			return false;
		} else {
			true;
		}
	}
</script>
PAGE;
	return $form;
	}
?>
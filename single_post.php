<?php
	require_once("support.php");
	require_once("post.php");
	session_start(); 
	$body = "";

	if (isset($_GET['user_email'])) {
		$_SESSION['user_email'] = $_GET['user_email'];
		header("Location: user_posts.php");

	} else if (isset($_SESSION['email']) && isset($_SESSION['postingName'])) {
		$body .= <<<PAGE
<script>
	document.getElementById("rightNavBar").innerHTML = "<form action=\"/Terpslist/index.php\" method=\"POST\"><input type=\"submit\" class=\"btn btn-default\" name=\"logout\" id=\"logout\" value=\"Logout\"></form>";
</script>
PAGE;
		$body .= <<<PAGE
<div class="well well-sm" id="mainContent">
PAGE;
		require_once("db_login.php");
		$db = connectToDB($host, $user, $password, $database);

		$query = sprintf("SELECT * FROM `posts` WHERE posting_title = '%s'", $_SESSION['postingName']);

		$result = mysqli_query($db, $query);

		if ($result) {
			$recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

			$currPost = new Post($recordArray['posting_title'], $recordArray['price'], $recordArray['image_path'], $recordArray['description'], $recordArray['user_email'], $recordArray['category']);

			$body .= <<<PAGE
			<div id="single_post_content">
				<h3>{$currPost->getPostingTitle()}</h3>
				<h4>Price: {$currPost->getPrice()}</h4>
				<img src="{$currPost->getImagePath()}" class="img-rounded" alt="{$currPost->getPostingTitle()}" id="single_img">
				<br><br>
				<p>{$currPost->getDescription()}</p>
				<p><span class="glyphicon glyphicon-envelope"></span> <a href="mailto:{$currPost->getUserEmail()}">Contact email.</a></p>
				<p><span class="glyphicon glyphicon-search"></span> <a href="single_post.php?user_email={$currPost->getUserEmail()}">More posts by same user.</a></p>
			</div>

PAGE;
		} else {
			$body .= <<<PAGE
<script>
	document.getElementById("mainContent").innerHTML = "<div class=\"alert alert-warning alert-dismissable\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Warning!</strong> Could not find post!</div>";
</script>
PAGE;
		}

		$body .= "</div>";
	} else {
		header("Location: index.php");
	}

	echo createPage($body, "singlePost");

?>
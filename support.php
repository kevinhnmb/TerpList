<?php
	function createPage($body, $pageName, $title="Terp's List") {
		$page = "";
		
		$page .= <<<PAGE
<!DOCTYPE html>
<html lang="en">
<head>
  <title>{$title}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body id="mainBody">
	<nav class="navbar navbar-inverse navbar-fixed-top" id="topBar">
		<div class="navbar-header">
      		<a class="navbar-brand" href="index.php">Terp's List</a>
    	</div>
    	<div id="menuSystem">
    	</div>
    	<div class="nav navbar-nav navbar-right" id="rightNavBar">
    	</div>
	</div>
	$body
</body>
</html>

PAGE;

if (isset($_SESSION['email'])) {
	$page_index = "";
	$page_upload = "";
	$page_categories = "";
	$page_settings = "";

	if ($pageName === "index") {
		$page_index .= "class=\\\"active\\\"";
	} else if ($pageName === "upload") {
		$page_upload .= "class=\\\"active\\\"";
	} else if ($pageName === "categories") {
		$page_categories .= "class=\\\"active\\\"";
	} else if ($pageName === "account_settings") {
		$page_settings = "class=\\\"active\\\"";
	}

	$page .= <<<PAGE
<script>
	document.getElementById("menuSystem").innerHTML = "<ul class=\"nav navbar-nav\"><li {$page_index}><a href=\"/Terpslist/index.php\">Home</a></li><li {$page_upload}><a href=\"/Terpslist/upload.php\">Post Item</a></li><li {$page_categories}><a href=\"/Terpslist/categories.php\">Categories</a></li><li {$page_settings}><a href=\"account_settings.php\">Account Settings</a></li></ul>";
</script>
PAGE;
		}

	return $page;
	}
?>

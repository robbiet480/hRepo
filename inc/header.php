<!DOCTYPE html>
<html>
	<head>
		<title>hRepo</title>
		
		<link rel="stylesheet" type="text/css" href="static/css/fonts.css" />
		<link rel="stylesheet" type="text/css" href="static/css/hrepo.css" />
	</head>
	<body>
		<div id="top">
			<div class="gutter clear">
				<h1><a href="index.php">hRepo</a></h1>
				<ul id="nav.php">
					<li <?php if($pagetitle == "browse"){echo 'class="active"'; } ?>><a href="browse.php">Browse</a></li>
					<li <?php if($pagetitle == "about"){echo 'class="active"'; } ?>><a href="about.php">About</a></li>
					<li <?php if($pagetitle == "faq"){echo 'class="active"'; } ?>><a href="faq.php">FAQ</a></li>
					<li <?php if($pagetitle == "login"){echo 'class="active"'; } ?>><a href="login.php">Login</a></li>
					<li <?php if($pagetitle == "contact"){echo 'class="active"'; } ?>><a href="contact.php">Contact</a></li>
				</ul>
			</div>
		</div>

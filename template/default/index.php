<!DOCTYPE html>
<html>
	<head>
		<title><?php echo pagetitle(); ?> &lsaquo; hRepo</title>
		
		<link rel="stylesheet" type="text/css" href="<?php echo HR_TEMPLATE_PUB_ROOT; ?>css/fonts.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo HR_TEMPLATE_PUB_ROOT; ?>css/hrepo.css" />
	</head>
	<body>
		<div id="top">
			<div class="gutter clear">
				<h1><a href="<?php url("/") ?>">hRepo</a></h1>
				<?php echo nav(); ?>
			</div>
		</div>
		<div id="wrapper">
			<div class="gutter clear">
				<div id="content">
					<div class="gutter clear">
						<?php echo content(); ?>
					</div>
				</div>
				
				<div id="sidebar">
					<div class="gutter clear">
					<?php
					foreach(Sidebar::$blocks as $v) {
						echo "<h3>".$v['title']."</h3>".$v['content'];
					}
					?>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<div class="gutter clear">
				<p>&copy; <?php echo date('Y'); ?> the hRepo Team</p>
			</div>
		</div>
		
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	</body>
</html>
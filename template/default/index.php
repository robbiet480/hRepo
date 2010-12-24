<!DOCTYPE html>
<html>
	<head>
		<title><?php echo pagetitle(); ?> &lsaquo; hRepo</title>
		
		<link rel="stylesheet" type="text/css" href="<?php echo HR_TEMPLATE_PUB_ROOT; ?>css/fonts.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo HR_TEMPLATE_PUB_ROOT; ?>css/hrepo.css" />
<?php
foreach (Content::$additionalCSS as $addssheet) {
	echo '		<link rel="stylesheet" type="text/css" href="'.HR_TEMPLATE_PUB_ROOT.'css/'.$addssheet.'" />
';
}
?>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<?php
foreach (Content::$additionalJS as $addjs) {
        echo '          <script type="text/javascript" src="'.HR_TEMPLATE_PUB_ROOT.'js/'.$addjs.'"></script>
';
} 
?>
	</head>
	<body>
		<div id="top">
			<div class="gutter clear">
				<h1><a href="/">hRepo</a></h1>
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
						echo "<h3>".$v['title']."</h3><div class='block'>".$v['content']."</div>";
					}
					?>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<div class="gutter clear">
				<p>&copy; <?php echo date('Y'); ?> the hRepo Team.</p>
				<p>Powered by <a href="http://hostiio.com">Hostiio</a> and <a href="http://aws.amazon.com/s3">Amazon S3</a>.</p>
				<p>Git Revision: <a href="http://github.com/robbiet480/hRepo/commit/<?php echo trim(shell_exec('/usr/bin/git --git-dir=/var/www/hRepo/.git rev-parse --short HEAD')); ?>"><?php echo trim(shell_exec('/usr/bin/git --git-dir=/var/www/hRepo/.git rev-parse --short HEAD')); ?></a></p>
			</div>
		</div>
		
	</body>
		</html>
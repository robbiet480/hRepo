<?php

$nav['create'] = array('url' => '/create', 'slug' => 'create', 'name' => 'Create New Plugin', 'loggedInOnly' => 1, 'minRole' => 0, 'weight' => 4, 'extrapre' => '', 'extrapost' => ''); // 1 for only logged in
if ($slug == 'create')
{
	if (User::$role == -1)
	{
		$httpError = 403;
	}
	else
	{
		$message = $pname = $pdesc = $preqs = $pmysql = $ismyplugin = '';

		if (User::$role < 1)
		{
			$message .= Message::notice('As you are not registered as a developer, you won\'t be able to control any plugins you upload here, and they will not be marked as yours.<br />Once you have been moved to the Developers group, you will gain access to edit your plugin.'); // default message
			$ismyplugin = 'disabled="disabled';
		}

		if (isset($_POST['submit']) && $_POST['submit'] == 'Create!')
		{
			if (User::$role < 1)
			{
				$_POST['ismyplugin'] = ''; // force no :D
			}
			
			// stuff happens here.
			$newPlugin = new Plugin();
			$newPlugin->desc = htmlentities($_POST['pdesc']);
			$newPlugin->name = htmlentities($_POST['pname']);
			$newPlugin->reqs = htmlentities($_POST['preqs']);
			$newPlugin->requires_mysql = ($_POST['pmysql'] == 'yes');
			$newPlugin->author_id = ($_POST['ismyplugin'] == 'yes') ? User::$uid : -1;
			if ($newPlugin->saveData()) {
				redirect('/upload/' . $newPlugin->getID());
			}
		}
		Content::setContent(<<<EOT
	<h1>Create a New Plugin</h1>	
	<h3>Step 1 of 2</h3>
			$message
		<form action="/create/" method="POST">
			<div class="form-row">
				<label for="pname">Plugin Name</label>
				<span><input type="text" name="pname" id="pname" value="$pname" /></span>
			</div>
			<div class="form-row">
				<label for="pdesc">Description (<a href="/markdown/">Markdown</a> formatted)</label>
				<span><textarea name="pdesc" id="pdesc">$pdesc</textarea></span>
			</div>
			<div class="form-row">
				<label for="preqs">Requirements</label>
				<span><input type="text" name="preqs" id="preqs" value="$preqs" /></span>
			</div>
			<div class="form-row">
				<label for="pmysql">Requires MySQL?</label>
				<span><input type="checkbox" name="pmysql" id="pmysql" value="yes" $pmysql /></span>
			</div>
			<div class="form-row">
				<label for="ismyplugin">Is My Own Plugin?</label>
				<span><input type="checkbox" name="ismyplugin" id="ismyplugin" value="yes" $ismyplugin /></span>
			</div>
			<div class="form-row form-row-last">
				<span><input type="submit" name="submit" id="submitBtn" value="Create!" /></span>
			</div>
		</form>
EOT
		);
	}
}
<?php

$nav['create'] = array('url' => '/create', 'slug' => 'create', 'name' => 'Create New Plugin', 'loggedInOnly' => 1, 'minRole' => User::ROLE_MEMBER, 'weight' => 4, 'extrapre' => '', 'extrapost' => ''); // 1 for only logged in
if ($slug == 'create')
{
/**/	if (User::$role < User::ROLE_MEMBER)
	{
		Content::setContent('This site isn\'t ready yet! Please wait until everything is ready!');
	}
	else /**/ if (User::$role == User::ROLE_GUEST)
	{
		$httpError = 403;
	}
	else
	{
		Content::addAdditionalJS('plugincreateform.js');
		$message = $pname = $pdesc = $preqs = $pmysql = $ismyplugin = $pauthorname = $pauthornameVis = '';

		if (User::$role < User::ROLE_DEVELOPER)
		{
			$message .= Message::notice('As you are not registered as a developer, you won\'t be able to control any plugins you upload here, and they will not be marked as yours.<br />Once you have been moved to the Developers group, you will gain access to edit your plugin.'); // default message
			$ismyplugin = 'disabled="disabled';
		}

		if (isset($_POST['submit']) && $_POST['submit'] == 'Create!')
		{
			if (User::$role < User::ROLE_DEVELOPER)
			{
				$_POST['ismyplugin'] = ''; // force no :D
			}

			// stuff happens here.
			$newPlugin = new Plugin();
			$pname = $newPlugin->name = htmlentities($_POST['pname']);
			$pdesc = $newPlugin->desc = htmlentities($_POST['pdesc']);
			$preqs = $newPlugin->reqs = htmlentities($_POST['preqs']);
			$pmysql .= ( $_POST['pmysql'] == 'yes') ? ' checked="checked"' : '';
			$ismyplugin .= ( $_POST['ismyplugin'] == 'yes') ? ' checked="checked"' : '';
			$newPlugin->requires_mysql = ($_POST['pmysql'] == 'yes');
			$newPlugin->author_id = User::$uid;
			$pauthorname = $newPlugin->real_author_name = ($_POST['ismyplugin'] == 'yes') ? '' : htmlentities($_POST['pauthorname']);
			$pauthornameVis = ($_POST['ismyplugin'] == 'yes') ? ' style="display:none;"' : '';
			$newPlugin->status = ($_POST['ismyplugin'] == 'yes') ? -1 : -2;
			if ($newPlugin->saveData())
			{
				redirect('/upload/' . User::$uname . '/' . $newPlugin->name . '/');
			}
			else
			{
				$message .= Message::error('An error occurred whilst adding the plugin to the database. Please contact an hRepo administrator.');
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
			<div class="form-row" id="pauthornameRow" $pauthornameVis>
				<label for="pauthorname">Real Author Name</label>
				<span><input type="text" name="pauthorname" id="pauthorname" value="$pauthorname" /></span>
			</div>
			<div class="form-row form-row-last">
				<span><input type="submit" name="submit" id="submitBtn" value="Create!" /></span>
			</div>
		</form>
EOT
		);
	}
}
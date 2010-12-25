<?php

function url($slug, $args = array()) {
	return HR_PUB_ROOT . ltrim($slug, "/");
}

function template() {
	header($_SERVER["SERVER_PROTOCOL"] . " " . Content::$status);

	foreach (Content::$headers as $k => $v)
	{
		header($k . ": " . $v);
	}

	if (Content::$useTemplate)
	{
		require HR_TEMPLATE . HR_TEMPLATE_TO_USE . '/index.php';
	}
	else
	{
		echo Content::$content;
	}

	exit();
}

function content() {
	return Content::$content;
}

function nav() {
	global $nav, $slug;
	usort($nav, function ($x, $y)
			{
				if ($x['weight'] > $y['weight'])
					return 1;
				if ($x['weight'] < $y['weight'])
					return -1;
				return 0;
			});

	$r = "<ul id='nav'>";
	$totalpre = '';
	$totalpost = '';
	foreach ($nav as $thisSlug => $vals)
	{
		if (($vals['visible'] !== false) && (($vals['loggedInOnly'] == 0) || ($vals['loggedInOnly'] == 1 && User::$isValid) || ($vals['loggedInOnly'] == -1 && !User::$isValid)))
		{
			if (!isset($vals['extrapre']))
				$vals['extrapre'] = '';
			if (!isset($vals['extrapost']))
				$vals['extrapost'] = '';
			$totalpre .= $vals['extrapre'];
			$totalpost .= $vals['extrapost'];
			$r .= sprintf('<li%s id="topBarLink%s"><a href="%s">%s</a></li>', ($slug == $vals['slug'] ? ' class="active"' : ''), ucfirst($vals['name']), url($vals['url']), $vals['name']);
		}
	}
	return $totalpre . $r . '</ul>' . $totalpost;
}

function pagetitle() {
	global $nav, $slug;
	if (!empty(Content::$forcedTitle))
	{
		return Content::$forcedTitle;
	}
	return $nav[$slug]['name'];
}

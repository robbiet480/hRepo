<?php

if ($_SESSION['debugmode']) {
	Content::append(<<<EOT
<h4>Debug details</h4>
	<h4>Logging Output</h4>
		<table>
			<tr>
				<th>Time from start</th>
				<th>Value</th>
			</tr>
EOT
			);
	$first = true;
	foreach (Log::getLog() as $logent) {
		if ($first === true) {
			$first = $logent['time'];
		}
		$timefromstart = $logent['time'] - $first;
		Content::append("
			<tr>
				<td>$timefromstart</td>
				<td>{$logent['val']}</td>
			</tr>");
	}
	Content::append(<<<EOT
		</table>
	<h4>Current Global Vars</h4>
		<pre>
EOT
			);
	Content::append(print_r($GLOBALS, true));
	Content::append('</pre>');
}
<?php

if($slug == "git") {
	Content::$forcedTitle = "Git Update";
	if (count($params) > 0) {
		chdir(HR_ROOT);
		Content::setContent("
			<h1>Updating website from git...</h1>
			<pre>".`echo Running svn revert && svn revert -R . 2>&1 && echo Running svn update && svn update`."</pre><br />
			<h3>Now pulling latest commit information from Github...</h3>
		");
		inclib('github/lib/phpGitHubApi.php');
		$phpGH = new phpGitHubApi();
		$phpGH->authenticate('lukegb', '3b4e0c11ee0681db035b0e885147e236', phpGitHubAPI::AUTH_HTTP_TOKEN);
		$latestCommits = $phpGH->getCommitApi()->getBranchCommits('robbiet480', 'hRepo', 'master');
		$gitCommit = array(
			'long' => $latestCommits[0]['id'],
			'short' => substr($latestCommits[0]['id'], 0, 7),
			'userid' => $latestCommits[0]['author']['login'],
			'commitdate' => $latestCommits[0]['committed_date']
		);
		file_put_contents(HR_ROOT . '/gitcommit.txt', serialize($gitCommit));
		Content::append('<p>Last git commit: ' . $gitCommit['long'] . ' by ' . $gitCommit['userid']. '</p>');
	} else {
		ob_start();
		print_r($parts);
		$dnote = ob_get_contents();
		ob_end_clean();
		Content::setContent("
			<h1>Authorisation code incorrect or missing</h2>
			<p>Git update did not go through.</p>
			<!-- Debug note: $dnote -->
		");
	}
}

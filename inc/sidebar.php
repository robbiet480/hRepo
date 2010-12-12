<?php

class Sidebar {
	public static $blocks;
	
	public function add($title, $contents) {
		self::$blocks[] = array('title' => $title, 'content' => $contents);
	}
}

Sidebar::add("Most Downloaded Plugins", <<<EOT
						<ol>
							<li><a href="/plugins/sk89q/WorldEdit/">WorldEdit<span class="count"> (946)</span></a></li>
							<li><a href="/plugins/sk89q/WorldEdit/">WorldEdit<span class="count"> (946)</span></a></li>
							<li><a href="/plugins/sk89q/WorldEdit/">WorldEdit<span class="count"> (946)</span></a></li>
							<li><a href="/plugins/sk89q/WorldEdit/">WorldEdit<span class="count"> (946)</span></a></li>
							<li><a href="/plugins/sk89q/WorldEdit/">WorldEdit<span class="count"> (946)</span></a></li>
							<li><a href="/plugins/sk89q/WorldEdit/">WorldEdit<span class="count"> (946)</span></a></li>
							<li><a href="/plugins/sk89q/WorldEdit/">WorldEdit<span class="count"> (946)</span></a></li>
							<li><a href="/plugins/sk89q/WorldEdit/">WorldEdit<span class="count"> (946)</span></a></li>
							<li><a href="/plugins/sk89q/WorldEdit/">WorldEdit<span class="count"> (946)</span></a></li>
							<li><a href="/plugins/sk89q/WorldEdit/">WorldEdit<span class="count"> (946)</span></a></li>
						</ol>
EOT
);
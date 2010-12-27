<?php

// This is a placeholder page to include all the plugins/ pages!
foreach(glob(HR_PAGES.'plugins/*.php') as $page) {
	require($page);
}
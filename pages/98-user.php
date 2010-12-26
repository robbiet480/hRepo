<?php

// This is a placeholder page to include all the user/ pages!
foreach(glob(HR_PAGES.'user/*.php') as $page) {
	require($page);
}
<?php

// This is a placeholder page to include all the uploads/ pages!
foreach(glob(HR_PAGES.'uploads/*.php') as $page) {
	require($page);
}
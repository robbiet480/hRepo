<?php

function inc ($file) {
	Log::add("including file: ".$file);
	require(HR_INC.$file);
}
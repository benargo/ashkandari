<?php

/**
 * function toSlug()
 * This function takes a string and converts it to a URL slug
 * @param $string (string)
 */

function toSlug($string) {

	$return = str_replace(array('&',
								'/',
								'+',
								'=',
								'@',
								'!'),
						  array('and',
						  		'or',
						  		'plus',
						  		'equals',
						  		'at',
						  		''), $string);

	$return = preg_replace('/[^a-z0-9\s\-]/i', '', $return);

	$return = str_replace(' ', '-', strtolower($return));

	return $return;
}
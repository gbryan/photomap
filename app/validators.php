<?php

/**
 * Check that a given $value is a valid JSON array.
 */
Validator::extend('jsonArray', function($attribute, $value, $parameters)
{
	$json = json_decode($value);

    return (!is_null($json));
});

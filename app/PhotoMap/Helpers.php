<?php namespace PhotoMap;

use \App;

class Helpers {

	public static function currentScope($key)
	{
		$currentScopes = App::make('currentScopes');

		return $currentScopes->$key;
	}

	public static function setCurrentScope($key, $value)
	{
		$currentScopes = App::make('currentScopes');
		$currentScopes->$key = $value;
	}

}

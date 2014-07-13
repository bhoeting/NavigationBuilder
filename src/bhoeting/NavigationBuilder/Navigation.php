<?php namespace bhoeting\NavigationBuilder;

use \Illuminate\Support\Facades\Facade;

/**
 * @see \bhoeing\Navigation\NavigationBuilder
 */
class Navigation extends Facade {

	/**
	 * Get the registered name of the component.
	 * 
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'navigationbuilder';
	} 
}
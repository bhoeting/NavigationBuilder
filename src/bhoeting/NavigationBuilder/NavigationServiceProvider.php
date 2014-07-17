<?php namespace bhoeting\NavigationBuilder;

use Illuminate\Support\ServiceProvider;
use bhoeting\NavigationBuilder\NavigationBuilder;

class NavigationServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	public function boot()
	{
		$this->package('bhoeting/navigation-builder');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('navigationbuilder', function($app) 
		{
			return new NavigationBuilder;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('navigationbuilder');
	}

}

<?php namespace bhoeting\NavigationBuilder;

class DefaultNavigation extends AbstractNavigation {

	protected $activeClass = 'active';

	protected $itemTemplate = 'navigation-builder::item';

	protected $containerTemplate = 'navigation-builder::container';

}
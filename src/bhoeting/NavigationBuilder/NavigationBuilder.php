<?php namespace bhoeting\NavigationBuilder;

class NavigationBuilder {

	public function create($param)
	{
		if (is_array($param))
		{
			$navigation = new DefaultNavigation($param);
		}
		else
		{
			$navigation = new $param();
		}

		return $navigation->getNavigationHtml();
	}

}

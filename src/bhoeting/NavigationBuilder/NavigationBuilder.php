<?php namespace bhoeting\NavigationBuilder;

use \URL;
use \Request;

class NavigationBuilder {

	public function create($items)	
	{
		$items = $this->makeItems($items);

		return $this->getNavigationHtml($items);
	}

	public function makeItems($items)
	{
		foreach ($items as $key => $item)
		{
			$tempName = $item;

			$item = ['name' => $tempName];	

			if ( ! isset($item['url']))
			{
				$item['url'] = URL::to($item['name']);
			}

			if ( ! isset($item['text']))
			{
				$item['text'] = ucwords($item['name']);
			}

			$item['active'] = Request::is($item['name'] . '/*') || Request::url() == $item['url'];

			$items[$key] = $item;
		}

		return $items;
	}

	public function getItemHtml($item)
	{
		return "<li class='" . ($item['active'] ? 'active' : '') . "'>" .
					"<a href='" . $item['url'] . "'>" . $item['text'] . "</a>" .
				"</li>";
	}

	public function getNavigationHtml($items)
	{
		$navigationHtml = $this->getItemContainerOpeningHtml();

		foreach ($items as $item)
		{
			$navigationHtml .= $this->getItemHtml($item);
		}

		$navigationHtml .= $this->getItemContainerClosingHtml();

		return $navigationHtml;
	}

	public function getItemContainerOpeningHtml()
	{
		return "<ul class='navbar-nav nav'>";
	}

	public function getItemContainerClosingHtml()
	{
		return "</ul>";
	}
}
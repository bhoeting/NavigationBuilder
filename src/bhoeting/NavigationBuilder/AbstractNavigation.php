<?php namespace bhoeting\NavigationBuilder;

use \View;

abstract class AbstractNavigation {

	/**
	 * @param array $items
	 */
	public function __construct($items = null)
	{
		if (isset($items))
		{
			$this->items = $items;
		}
	}

	/**
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @return string
	 */
	public function getContainerTemplate()
	{
		return $this->containerTemplate;
	}

	/**
	 * @param array
	 */
	public function setItems($items)
	{
		$this->items = $items;
	}

	/**
	 * @return string
	 */
	public function getActiveClass()
	{
		return $this->activeClass;
	}

	/**
	 * @return View
	 */
	public function getNavigationHtml()
	{
		return View::make($this->containerTemplate)->withNavigation($this);
	}

	/**
	 * @return string
	 */
	public function getItemHtml()
	{
		$html = '';

		$this->items = Item::makeItems($this->items, $this->activeClass);

		foreach ($this->items as $item)
		{
			$html .= $item->makeHtml($this->itemTemplate);
		}

		return $html;
	}

}

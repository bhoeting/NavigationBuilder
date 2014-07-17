<?php namespace bhoeting\NavigationBuilder;

use \Response;
use \View;

/**
 * @property string containerTemplate
 * @property string activeClass
 * @property string itemTemplate
 * @property Item[] items
 */
abstract class AbstractNavigation {

	/**
	 * @param  Item[] $items
	 * @return AbstractNavigation
	 */
	public function __construct($items = null)
	{
		if (isset($items))
		{
			$this->items = $items;
		}

		return $this;
	}

	/**
	 * @return Item[]
	 */
	public function getItems()
	{
		return $this->items;
	}


	/**
	 * @param array
	 * @return AbstractNavigation
	 */
	public function setItems($items)
	{
		$this->items = $items;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getContainerTemplate()
	{
		return $this->containerTemplate;
	}

	/**
	 * @return string
	 */
	public function getActiveClass()
	{
		return $this->activeClass;
	}

	/**
	 * @return Response
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

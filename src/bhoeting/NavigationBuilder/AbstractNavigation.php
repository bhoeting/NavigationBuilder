<?php namespace bhoeting\NavigationBuilder;

use \Response;
use \View;

/**
 * @property string containerTemplate
 * @property string itemTemplate
 * @property Item[] items
 * @property string table
 */
abstract class AbstractNavigation {

	protected $itemTemplate = 'navigation-builder::item';

	protected $containerTemplate = 'navigation-builder::container';

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
	 * @return Response
	 */
	public function getNavigationHtml()
	{
		return View::make($this->containerTemplate)->withNavigation($this);
	}

	/**
	 * Make the Item array contain Item objects.
	 * 
	 * @return void
	 */
	public function initializeItems()
	{
		if (isset($this->table))
		{
			$this->items = Item::makeItemsFromDB($this->table);
		}
		else
		{
			$this->items = Item::makeItems($this->items);
		}

	}

	/**
	 * @return string
	 */
	public function getItemHtml()
	{
		$html = '';

		$this->initializeItems();

		foreach ($this->items as $item)
		{
			$html .= $item->makeHtml($this->itemTemplate);
		}

		return $html;
	}
	
}

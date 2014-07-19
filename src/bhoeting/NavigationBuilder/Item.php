<?php namespace bhoeting\NavigationBuilder;

use DB;
use \URL;
use \View;
use \Request;

class Item {

	/**
	 * The name of the item.
	 * 
	 * @var string 
	 */ 
	private $name;

	/**
	 * The URL the item will link to.
	 * 
	 * @var string
	 */ 
	private $url;

	/**
	 * The display text of the item.
	 *
	 * @var string
	 */
	private $text;

	/**
	 * The keys for the Item array.
	 *
	 * @var array
	 */
	public static $attributes = ['url', 'text'];

	/**
	 * Create an Item from an array of attributes.
	 *
	 * @param  array $attributes
	 * @return Item
	 */
	public static function fromArray($attributes)
	{
		$item = new Item;

		$item->setName($attributes['name']);

		foreach (self::$attributes as $attribute)
		{
			$func = 'set' . ucwords($attribute);

			if ( ! isset($attributes[$attribute]))
			{
				$func .= 'FromName';

				$item->$func($item->getName());
			}
			else
			{
				$item->$func($attributes[$attribute]);
			}
		}

		return $item;
	}

	public static function makeItemsFromDB($table)
	{
		$items = [];

		$itemsFromDb = DB::table($table)->get();

		foreach ($itemsFromDb as $item)
		{
			$attributes = get_object_vars($item);

			array_push($items, Item::fromArray($attributes));
		}

		return $items;
	}

	/**
	 * Turn an array into an array of Item objects.
	 *
	 * @param  array $itemArray
	 * @return array|bool
	 */
	public static function makeItems($itemArray)
	{
		$items = [];

		if (count($itemArray) < 1) return false;


		foreach ($itemArray as $index => $itemName)
		{
			$itemArray[$index]['name'] = $index;

			array_push($items, Item::fromArray($itemArray[$index]));
		}

		return $items;
	}

	/**
	 * Check if the current URL follows a pattern of or is equal to the Item's url.
	 *
	 * @return boolean 
	 */ 
	public function isActive()
	{
		return (Request::is($this->url . '/*') || Request::url() == $this->makeUrl());
	}

	/**
	 * If the Item is active, then return the active class.
	 *
	 * @param  string $activeClass
	 * @return string
	 */
	public function makeActive($activeClass = 'active')
	{
		return $this->isActive() ? $activeClass : '';
	}

	/**
	 * Get HTML from the provided Item template.
	 *
	 * @param  string $view
	 * @return string
	 */
	public function makeHtml($view)
	{
		return View::make($view)->withItem($this);
	}

	/**
	 * Get the full URL based of the url private data.
	 * 
	 * @return string
	 */ 
	public function makeUrl()
	{
		return URL::to($this->url);
	}

	/**
	 * @return string
	 */ 
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param  string $name
	 * @return $this
	 */
	private function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */ 
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param  string $url 
	 * @return Item
	 */
	public function setUrl($url)
	{
		$this->url = $url;

		return $this;		
	}

	/**
	 * @return string
	 */ 
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param  string $text
	 * @return Item
	 */  
	public function setText($text)
	{
		$this->text = $text;

		return $this;
	}

	/**
	 * Generate the Item's url based off its name.
     *
	 * @return Item
	 */
	private function setUrlFromName()
	{
		$this->setUrl($this->name);

		return $this;
	}

	/**
	 * Generate the Item's text based off its name.
	 *
	 * @return Item
	 */ 
	private function setTextFromName()
	{
		$this->setText(ucwords($this->name));

		return $this;
	}


}

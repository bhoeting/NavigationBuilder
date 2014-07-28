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
	 * The route the item will link to.
	 * 
	 * @var string
	 */ 
	private $route;

	/**
	 * The display text of the item.
	 *
	 * @var string
	 */
	private $text;

	/**
	 * The name of the icon that can be displayed next to an item's text.
	 * 
	 * @var string
	 */ 
	private $icon;

	/**
	 * The attriubutes each Item could have.
	 *
	 * @var array
	 */
	public static $attributes = ['url', 'text', 'route', 'icon'];

	/**
	 * The attributes that can be null.
	 * 
	 * @var array
	 */ 
	public static $optionalAttributes = ['route', 'icon'];

	/**
	 * Create an Item from an array of attributes.
	 *
	 * @param  array $attributes
	 * @return Item
	 */
	public static function fromArray($attributes)
	{
		$item = new Item;

		$item->name = $attributes['name'];

		foreach (self::$attributes as $attribute)
		{
			if ( ! isset($attributes[$attribute]) && ! self::isOptional($attribute) )
			{
				$func = 'set' . ucwords($attribute) . 'FromName';

				$item->$func($item->name);
			}
			else
			{
				if (array_key_exists($attribute, $attributes))
				{
					$item->$attribute = ($attributes[$attribute]);
				}
			}
		}

		return $item;
	}

	/**
	 * Create Items by reading records from a DB table.
	 * 
	 * @param  string $table
	 * @return Item[] $items
	 */ 
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
			if ( ! is_array($itemArray[$index]))
			{
				$itemArray[$index] = [];

				$itemArray[$index]['name'] = $itemName;
			}
			else
			{
				$itemArray[$index]['name'] = $index;
			}

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
	 * Check if an attribute is optional.
	 *
	 * @param  string $attribute
	 * @return boolean
	 */
	private static function isOptional($attribute)
	{
		return in_array($attribute, self::$optionalAttributes);
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
	 * Get the Item's display text.
	 * 
	 * @return string
	 */
	public function makeText()
	{
		return $this->text;
	}

	/**
	 * Create the HTML for the item's icon.
	 *
	 * @return string
	 */ 
	public function makeIcon()
	{
		if ($this->icon != null) 
		{
			$iconString = '';

			if (strpos($this->icon, '|'))
			{
				foreach (explode('|', $this->icon) as $icon)
				{
					$iconString .= " fa-{$icon}";		
				}
			}
			else 
			{
				$iconString = 'fa-' . $this->icon;
			}
						
			return "<i class='fa {$iconString}'></i> ";
		}
		return '';
	}

	/**
	 * Get the full URL based of the url private data.
	 * 
	 * @return string
	 */ 
	public function makeUrl()
	{
		return $this->route == null
			? URL::to($this->url)
			: URL::route($this->route);
	}

	/**
	 * Generate the Item's url based off its name.
     *
	 * @return Item
	 */
	private function setUrlFromName()
	{
		$this->url = $this->name;

		return $this;
	}

	/**
	 * Generate the Item's text based off its name.
	 *
	 * @return Item
	 */ 
	private function setTextFromName()
	{
		$this->text = ucwords($this->name);

		return $this;
	}

}

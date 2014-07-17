<?php namespace bhoeting\NavigationBuilder;

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
	 * The CSS class[es] for when the Item is active.
	 * 
	 * @var string
	 */ 
	private $activeClass;

	/**
	 * The keys for the Item array.
	 *
	 * @var array
	 */
	public static $attributes = ['url', 'text'];

	/**
	 * @param array  $attributes
	 * @param string $name
	 * @param string $activeClass
	 */
	public function __construct($attributes, $name, $activeClass)
	{
		$this->make($attributes, $name, $activeClass);	
	}

	/**
	 * Turn an array into an array of Item objects.
	 *
	 * @param  array $itemArray
	 * @param string $activeClass
	 * @return array
	 */
	public static function makeItems($itemArray, $activeClass = '')
	{
		$items = [];

		if (count($itemArray) < 1) return false;

		foreach ($itemArray as $index => $itemName)
		{
			if ( ! is_array($itemArray[$index]))
			{
				$itemArray[$itemName] = [];

				unset($itemArray[$index]);

				array_push($items, new Item($itemArray[$itemName], $itemName, $activeClass));
			}
			else
			{
				array_push($items, new Item($itemArray[$index], $index, $activeClass));
			}
		}

		return $items;
	}

	/**
	 * Convert an array of attributes to an Item object.
	 *
	 * @param  array  $attributes
	 * @param  string $name
	 * @param  string $activeClass
	 * @return Item
	 */
	public function make($attributes, $name, $activeClass = '')
	{
		$this->name = $name;

		$this->activeClass = $activeClass;

		foreach (self::$attributes as $attribute)
		{
			$func = 'set' . ucwords($attribute);

			if ( ! array_key_exists($attribute, $attributes))
			{
				$func .= 'FromName';

				$this->$func($name);
			}
			else
			{
				$this->$func($attributes[$attribute]);
			}
		}

		return $this;
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
	 * @return string
	 */
	public function makeActive()
	{
		return ($this->isActive()) ? $this->activeClass : '';
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
	 * @return Item
	 */ 
	public function getActiveClass()
	{
		return $this->activeClass;
	}

	/**
	 * @param  string $activeClass
	 * @return Item
	 */ 
	public function setActiveClass($activeClass)
	{
		$this->activeClass = $activeClass;

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

NavigationBuilder
=================

A navigation HTML generator for Laravel.

## Installation

```js
"require": {
	"bhoeting\navigation-builder": "~1.0"
}
```

Run `composer install`

Add the service provider to the `providers` array in `app/config/app.php`
```php
'bhoeting\NavigationBuilder\NavigationServiceProvider',
'''
Then add the facade to the `aliases` array
```php
'Navigation' => 'bhoeting\NavigationBuilder\Navigation'
```
## Usage
### Basic
```php
{{ Navigation::create(['home', 'about', 'contact'] }}
```
Will generate:
```html
<ul class="nav navbar-nav">
	<li class="">
		<a href="http://localhost:8000/home">Home</a>
	</li>
	<li class="active">
		<a href="http://localhost:8000/about">About</a>
	</li>
	<li class="">
		<a href="http://localhost:8000/contact">Contact</a>
	</li>
</ul>
```
By default, a Bootstrap template is used to generate the HTML.  See Advanced on how you can create your own templates.
Also note that the `about` item has an a class of `active`.  This is because the current URL is the same as the about item's link.
Items are also active when the current URL matches a pattern of the item's link.  For instance, `http://localhost:8000/about/who-we-are` will also make the `about`	item active.

The display text and URL for each item are based on the strings provided in the array.  You can specify your own like so:

```php
{{ Navigation::create['home' => ['url' => '/'], 'about' => ['text' => 'about-us'], 'contact']) }}
```
Output:
```html
<ul class="nav navbar-nav">
	<li class="">
		<a href="http://localhost:8000/">Home</a>
	</li>
	<li class="active">
		<a href="http://localhost:8000/about">About-us</a>
	</li>
	<li class="">
		<a href="http://localhost:8000/contact">Contact</a>
	</li>
</ul>
```

### Advanced
You can easily re-use and configure Navigations by extending the provided `AbstractNavigaiton` and specifying your own templates, active class, and Items.
```php
// app/Acme/Navigation/MasterNavigation.php

<?php namespace Acme\Navigation;

use bhoeting\NavigationBuilder\AbstractNavigation;

class MasterNavigation extends AbstractNavigation {

	protected $items = [
		'home'    => ['url' => '/'],
		'about'   => ['text' => 'About us'],
		'contact' => ['text' => 'Contact us']
	];

	protected $activeClass = 'active';

	protected $itemTemplate = 'navigation.item';

	protected $containerTemplate = 'navigation.container';

}
```

Then in your view:

```php
{{ Navigation::create('Acme\Navigation\MasterNavigation') }}
```



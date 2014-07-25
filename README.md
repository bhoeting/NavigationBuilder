NavigationBuilder
=================

A navigation HTML generator for Laravel.

## Installation

```js
"require": {
	"bhoeting/navigation-builder": "*"
}
```

Run `composer install`

Add the service provider to the `providers` array in `app/config/app.php`
```php
'bhoeting\NavigationBuilder\NavigationServiceProvider',
```
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
Items are also active when the current URL matches a pattern of the item's link.  For instance, `http://localhost:8000/about/who-we-are` will also make the `about` item active.

The display text and URL for each item are based on the strings provided in the array.  You can specify your own like so:

```php
{{ Navigation::create(['home' => ['url' => '/'], 'about' => ['text' => 'about-us'], 'contact' => ['route' => 'contact.us']]) }}
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

You can also associate a Font Awesome Icon to be displayed next to the Item's text.
```php
{{ Navigation::create(['home' => ['url' => '/', 'icon' => 'user']]) }}
```
Will output:
```html
...
<li class="">
	<i class="fa fa-user"></i> Home
</li>
```

### Advanced
You can easily re-use and configure Navigations by extending the provided `AbstractNavigaiton` and specify your own templates, active class, and Items.
```php
// app/Acme/Navigation/MasterNavigation.php

<?php namespace Acme\Navigation;

use bhoeting\NavigationBuilder\AbstractNavigation;

class MasterNavigation extends AbstractNavigation {

	protected $items = [
		'home'    => ['url' => '/'],
		'about'   => ['text' => 'About us'],
		'contact' => ['route' => 'contact.page']
	];


	protected $itemTemplate = 'navigation.item';

	protected $containerTemplate = 'navigation.container';

}
```
Create the templates:
`app/views/navigation/item.blade.php`
```php
<li class="{{ $item->makeActive('aDifferentActiveClass') }}">
	<a href="{{ $item->makeUrl() }}">
		{{ $item->getText() }}
	</a>
</li>
```
`app/views/navigation/container.blade.php`
```php
<ul class="nav navbar-nav">
	{{ $navigation->getItemHtml() }}
</ul>
```
Then in your view:


```php
{{ Navigation::create('Acme\Navigation\MasterNavigation') }}
```
You can also store Navigation items in the database.  First, create a migration like the one below:
```php
// app/database/migrations/CreateMasterNavItemsTable.php

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavigationTable extends Migration {

	public function up()
	{
		Schema::create('master_nav_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('url')->nullable();
			$table->string('route')->nullable();
			$table->string('text')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('master_nav_items');
	}

}
```
Now in your extension of `AbstractNavigation`
```php
<?php namespace Acme\Navigation;

use bhoeting\NavigationBuilder\AbstractNavigation;

class MasterNavigation extends AbstractNavigation {
	
	protected $table = 'master_nav_items';

	protected $itemTemplate = 'navigation.item';

	protected $containerTemplate = 'navigation.container';

}
```

<li class="{{ $item->makeActive('active') }}">
	<a href="{{ $item->makeUrl() }}">
		{{ $item->makeIcon() . $item->makeText() }}
	</a>
</li>
<li class="{{ $item->makeActive('active') }}">
	<a href="{{ $item->makeUrl() }}">
		{{ $item->makeText() }}
	</a>
</li>
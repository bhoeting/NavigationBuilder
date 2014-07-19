<li class="{{ $item->makeActive('derp') }}">
	<a href="{{ $item->makeUrl() }}">
		{{ $item->getText() }}
	</a>
</li>
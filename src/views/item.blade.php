<li class="{{ $item->makeActive('active') }}">
	<a href="{{ $item->makeUrl() }}">
		{{ $item->getText() }}
	</a>
</li>
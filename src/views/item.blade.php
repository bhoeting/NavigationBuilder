<li class="{{ $item->makeActive() }}">
	<a href="{{ $item->makeUrl() }}">
		{{ $item->getText() }}
	</a>
</li>
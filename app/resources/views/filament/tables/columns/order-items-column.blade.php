<ul
	{{ $getExtraAttributeBag() }}
	class="grid grid-cols-1 gap-1"
>
	@foreach ($record->order_items as $key => $orderItem)
		<li class="grid grid-cols-[auto_minmax(0,1fr)] items-center gap-1">
			<span class="bg-status-{{ $orderItem->status }} h-full min-h-4 w-1"></span>

			<div class="max-w-full truncate">
				<span class="font-semibold">{{ $orderItem->quantity }} x</span> <span
					class="text-gray-400">{{ $orderItem->name }}</span>
			</div>
		</li>
	@endforeach
</ul>

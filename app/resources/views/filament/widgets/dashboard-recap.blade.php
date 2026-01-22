<x-filament-widgets::widget>

	<h2 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 sm:text-3xl dark:text-white">Recap</h2>

	<div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

		@foreach ($this->statusData() as $statusId => $statusData)
			<article class="fi-section p-6">
				<h3 class="text-lg">{{ $statusData['title'] }}</h3>
				<p class="text-status-{{ $statusId }} text-3xl font-bold">{{ price($statusData['value']) }}</p>

				@if ($statusId == 6)
					<p class="mt-2 text-gray-400">fatturato <span
							class="font-semibold text-white">{{ price($this->billedWithdrawn()) }}</span></p>
					<p class="text-gray-400">da fatturare <span
							class="font-semibold text-white">{{ price($this->notBilledWithdrawn()) }}</span></p>
				@endif

			</article>
		@endforeach

	</div>

</x-filament-widgets::widget>

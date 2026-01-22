@props([
    'title' => null,
    'description' => null,
    'thumbnail' => null,
    'locationTag' => null,
    'colorScheme' => null,
    'headerConfig' => [],
])

@push('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy.css') }}?v={{ filemtime(public_path('css/legacy.css')) }}"
	>
	<style>
		.main-content {
			display: grid;
			grid-template-areas:
				'header'
				'main';
			grid-template-columns: minmax(0, 1fr);
			grid-template-rows: max-content 1fr;
			min-height: 100svh;
		}

		main {
			grid-area: main;
		}
	</style>
@endpush

@pushOnce('scripts')
	<script
		src="https://code.jquery.com/jquery-3.7.1.min.js"
		integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
		crossorigin="anonymous"
	></script>
	<script src="{{ asset('js/legacy.js') }}"></script>
@endpushOnce

<x-layouts.app
	:$title
	:$description
	:$thumbnail
	:$colorScheme
>
	<div class="main-content">
		<x-navigation.header
			:config="$headerConfig"
			:$locationTag
		/>

		<main>
			<div class="content-grid min-h-full">
				{{ $slot }}
			</div>
		</main>
	</div>

	<x-navigation.footer />
</x-layouts.app>

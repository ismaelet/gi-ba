<!DOCTYPE html>
<html
	class="{{ $colorScheme }}"
	lang="{{ $lang }}"
>

<head>
	<meta charset="utf-8">
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1"
	>
	<meta
		name="description"
		content="{{ $description }}"
	>
	<meta
		property="og:image"
		content="{{ $thumbnail }}"
	>
	<link
		rel="icon"
		href="{{ asset('favicon.ico') }}"
	>
	<title>
		{{ $title }}
	</title>

	<livewire:styles />

	@vite(['resources/css/app.css', 'resources/js/app.ts'])
	@stack('styles')
	@stack('scripts')

</head>

<body>
	{{ $slot }}

	<livewire:scripts />
</body>

</html>

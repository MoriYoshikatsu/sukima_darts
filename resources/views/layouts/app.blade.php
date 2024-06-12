<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		@if (isset($title))
			<title>{{ $title }}</title>
		@endif
		
		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.bunny.net">
		<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

		<!-- Scripts -->
		@vite(['resources/css/app.css', 'resources/js/app.js'])
		<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&libraries=streetView&callback=initMap&v=weekly&libraries=places"></script>
	</head>
	<body class="font-sans antialiased">
		<div class="min-h-screen bg-gray-400">

			<!-- Page Heading -->
			@if (isset($header))
				<header class="bg-white shadow">
					<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
						{{ $header }}
					</div>
				</header>
			@endif

			@include('layouts.navigation')

			<!-- Page Content -->
			<main class="bg-gray-400 max-h-full">
				{{ $slot }}
			</main>
		</div>
	</body>
</html>

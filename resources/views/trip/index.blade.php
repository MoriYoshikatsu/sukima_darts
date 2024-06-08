<x-app-layout>
	<x-slot name="title">投稿一覧</x-slot>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&libraries=streetView&callback=initMap&v=weekly"></script>
	<div class="container mx-auto p-4">
		<div class="flex justify-between items-center border-b pb-2">
			<div class="text-xl font-bold">User名</div>
			<div class="mt-4">
				<p class="text-lg">自己紹介文</p>
			</div>
		</div>
			
		<div class="flex mt-4">
			<!-- Sidebar -->
			<div class="w-1/5 border-r pr-4">
				<div class="mb-4">
					<input type="text" placeholder="トリップリスト検索ボックス" class="w-full p-2 border">
				</div>
				<div class="mb-4">
					<input type="text" placeholder="トリップリストソートボックス" class="w-full p-2 border">
				</div>
				<div>
					@foreach($trips as $tripz)
						@if($tripz->status == 1)
							<div>
								<a href="/users/{{ Auth::id() }}/edit/trip/{{ $tripz->id }}">{{ $tripz->title }}</a>
							</div>
						@endif
					@endforeach
				</div>
			</div>
		
			<!-- Main Content -->
			<div class="w-4/5 pl-4">
				<div class="flex mb-4">
					<button class="bg-blue-500 text-white py-2 px-4 rounded mr-2">この計画リストを公開する</button>
					<button class="bg-gray-500 text-white py-2 px-4 rounded">実際に行った日付詳細ブレダウン</button>
				</div>

				<div class="border p-4 mb-4">
					@foreach($trips as $trip)
						@if($trip->status == 1)
							<a href="/users/{{ Auth::id() }}/edit/trip/{{ $trip->id }}">{{ $trip->title }}</a>
							<br>
							<div class="description">{{ $trip->description }}</div>
							<br>
							<div class="trip_date">{{ $trip->trip_date }}</div>
							<div id="map-{{ $trip->id }}" class="map" style="height: 500px; width: 100%;"></div>
			
							<script>
								var infoWindow;
								var marker;
								var greenPin = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";
								var redPin = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
								var bluePin = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
								function initMap_{{ $trip->id }}() {
									var map_{{ $trip->id }} = new google.maps.Map(document.getElementById('map-{{ $trip->id }}'), {
										center: { lat: {{ $trip->dart_latitude }}, lng: {{ $trip->dart_longitude }} },
										zoom: 13
									});
			
									new google.maps.Marker({
										position: { lat: {{ $trip->dart_latitude }}, lng: {{ $trip->dart_longitude }} },
										map: map_{{ $trip->id }},
										icon: greenPin
									});
			
									@foreach ($tripSpotTrips[$trip->id] as $SpotTrip)
										var spotMarker = new google.maps.Marker({
											position: { lat: {{ $SpotTrip->spot->latitude }}, lng: {{ $SpotTrip->spot->longitude }} },
											map: map_{{ $trip->id }},
											icon: bluePin
										});
										spotMarker.set('placeName', "{{ $SpotTrip->spot->name }}");
			
										google.maps.event.addListener(spotMarker, 'click', (function(marker) {
											return function() {
												var placeName = marker.get('placeName');
												showInfoWindow(placeName, map_{{ $trip->id }}, marker);
											};
										})(spotMarker));
									@endforeach
			
									@foreach ($tripWentSpotTrips[$trip->id] as $wentSpotTrip)
										var wentSpotMarker = new google.maps.Marker({
											position: { lat: {{ $wentSpotTrip->spot->latitude }}, lng: {{ $wentSpotTrip->spot->longitude }} },
											map: map_{{ $trip->id }},
											icon: redPin
										});
										wentSpotMarker.set('placeName', "{{ $wentSpotTrip->spot->name }}");
			
										google.maps.event.addListener(wentSpotMarker, 'click', (function(marker) {
											return function() {
												var placeName = marker.get('placeName');
												showInfoWindow(placeName, map_{{ $trip->id }}, marker);
											};
										})(wentSpotMarker));
									@endforeach
								};
			
								function showInfoWindow(placeName, map, marker) {
									var contentString = `
										<div>
											<p>クリックするとググれます！</p>
											<strong><a href="https://www.google.com/search?q=${encodeURIComponent(placeName)}" target="_blank">${placeName}</a></strong>
										</div>`;
			
									var infowindow = new google.maps.InfoWindow({
										content: contentString
									});
			
									infowindow.open(map, marker);
								};
			
								initMap_{{ $trip->id }}();
							</script>
						@endif
					@endforeach
				</div>
			</div>
		</div>
	</div>
</x-app-layout>

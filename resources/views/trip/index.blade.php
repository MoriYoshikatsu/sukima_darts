<x-app-layout>
	<x-slot name="title">{{ Auth::user()->name }}の投稿一覧</x-slot>
	
	<div class="container mx-auto p-4">
		<div class="flex mt-4">
			<!-- Sidebar -->
			<div class="w-1/5 border-r pr-4">
				<h2 class="text-2xl font-bold mb-4">{{ Auth::user()->name }}の旅行先</h2>
				<div>
					@foreach($trips as $tripz)
						<div>
							<button id="trip_{{ $tripz->id }}" class="text-lg font-bold mb-1">{{ $tripz->title }}</p>
						</div>
					@endforeach
				</div>
			</div>
		
			<!-- Main Content -->
			<div class="w-4/5 pl-4">
				@foreach($trips as $trip)
					<div class="items-center border p-4 bg-white rounded-md shadow-sm mb-4">
						<a id="title_{{ $trip->id }}" href="/users/{{ Auth::id() }}/edit/trip/{{ $trip->id }}" class="text-xl font-bold">{{ $trip->title }}</a>
						<div id="map-{{ $trip->id }}" class="map" style="height: 500px; width: 100%;"></div>
						<br>
						<div class="description">{{ $trip->description }}</div>
						<br>
						<div class="trip_date">{{ $trip->trip_date }}</div>
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
					</div>
				@endforeach
			</div>
		</div>
	</div>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			@foreach($trips as $tripz)
				const button_{{ $tripz->id }} = document.getElementById("trip_{{ $tripz->id }}");
				const targetSection_{{ $tripz->id }} = document.getElementById("title_{{ $tripz->id }}");

				if (button_{{ $tripz->id }} && targetSection_{{ $tripz->id }}) {
					button_{{ $tripz->id }}.addEventListener("click", function() {
						targetSection_{{ $tripz->id }}.scrollIntoView({
							behavior: "smooth"
						});
					});
				}
			@endforeach
		});
	</script>
</x-app-layout>

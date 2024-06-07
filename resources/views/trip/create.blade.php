<x-app-layout>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&libraries=streetView&callback=initMap&v=weekly"></script>
	<x-slot name="title">投稿作成</x-slot>

	<div>
		<form method="POST" action="/store/trip">
			@csrf
			<input type="hidden" name="dart_latitude" value={{ $trip->dart_latitude }}>
			<input type="hidden" name="dart_longitude" value={{ $trip->dart_longitude }}>
			<!--<div class="flex justify-between h-16">-->
			<label for="title">タイトル</label>
			<input type="text" id="title" name="title" value="{{ old('title', $trip->title) }}">
			<br>

			<label for="description">説明</label>
			<textarea id="description" name="description">{{ old('description', $trip->description) }}</textarea>
			<br>

			<label for="trip_date">旅行日</label>
			<input type="date" id="trip_date" name="trip_date" value="{{ old('trip_date', $trip->trip_date) }}">
			<br>

			<label for="status">公開状態</label>
			<select id="status" name="status">
				<option value="1" {{ $trip->status == 1 ? 'selected' : '' }}>公開</option>
				<option value="0" {{ $trip->status == 0 ? 'selected' : '' }}>非公開</option>
			</select>
			<br>
			<button type="submit">投稿</button>
		</form>
		<!-- Google マップ表示 -->
		<div id="map" style="height: 500px; width: 100%;"></div>
		<div class="flex justify-between h-16">
			<div>
				<label>まだ行ってない場所</label>
				@foreach ($SpotTrips as $SpotTrip)
					<div>
						{{ $SpotTrip->spot->name }}
					</div>
				@endforeach
			</div>
			<div>
				<label>行ったことのある場所</label>
				@foreach ($wentSpotTrips as $wentSpotTrip)
					<div>
						{{ $wentSpotTrip->spot->name }}
					</div>
				@endforeach
			</div>
		</div>
	</div>

	<script>
		let infoWindow;
		let marker;
		const dartLocation = { lat: {{ $trip->dart_latitude }}, lng: {{ $trip->dart_longitude }} };
		const greenPin = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";
		const redPin = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
		const bluePin = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
		
		function initMap() {
			var map = new google.maps.Map(document.getElementById('map'), {
				center: { lat: {{ $trip->dart_latitude }}, lng: {{ $trip->dart_longitude }} },
				zoom: 13
			});
			
			var dartmarker = new google.maps.Marker({
				position: { lat: {{ $trip->dart_latitude }}, lng: {{ $trip->dart_longitude }} },
				map: map,
				icon: greenPin
			});
			
			@foreach ($SpotTrips as $SpotTrip)
				var spotMarker = new google.maps.Marker({
					position: { lat: {{ $SpotTrip->spot->latitude }}, lng: {{ $SpotTrip->spot->longitude }} },
					map: map,
					icon: bluePin
				});
				spotMarker.set('placeName', "{{ $SpotTrip->spot->name }}");
				
				google.maps.event.addListener(spotMarker, 'click', (function(marker) {
					return function() {
						var placeName = marker.get('placeName');
						showInfoWindow(placeName, map, marker);
					};
				})(spotMarker));
			@endforeach
		
			@foreach ($wentSpotTrips as $wentSpotTrip)
				var wentSpotMarker = new google.maps.Marker({
					position: { lat: {{ $wentSpotTrip->spot->latitude }}, lng: {{ $wentSpotTrip->spot->longitude }} },
					map: map,
					icon: redPin
				});
				wentSpotMarker.set('placeName', "{{ $wentSpotTrip->spot->name }}");
		
				google.maps.event.addListener(wentSpotMarker, 'click', (function(marker) {
					return function() {
						var placeName = marker.get('placeName');
						showInfoWindow(placeName, map, marker);
					};
				})(wentSpotMarker));
			@endforeach

		}
		
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
		}
	</script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&callback=initMap&libraries=places"></script>
</x-app-layout>

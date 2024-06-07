<x-app-layout>
	<x-slot name="title">トリップリスト作成</x-slot>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&libraries=streetView&callback=initMap&v=weekly"></script>
	<div>
		<form method="POST" action="/store/spot_trip/status" id="spotForm">
			@csrf
			@foreach ($spotTrips as $spotTrip)
				<div>
					<input type="checkbox" id="spot_{{ $spotTrip->spot->id }}" name="spots[]" value="{{ $spotTrip->spot->id }}" data-latitude="{{ $spotTrip->spot->latitude }}" data-longitude="{{ $spotTrip->spot->longitude }}" data-place-name="{{ $spotTrip->spot->name }}" class="spot-checkbox">
					<label for="spot_{{ $spotTrip->spot->id }}">{{ $spotTrip->spot->name }}</label>
				</div>
			@endforeach
			<button type="submit">送信</button>
		</form>
	</div>

	<div id="map" style="height: 500px; width: 100%;"></div>

	<script>
		const dartsLocation = { lat: {{ $trip->dart_latitude }}, lng: {{ $trip->dart_longitude }} };
		let infoWindow;
		const departureLocation = { lat: {{ $parameter->departure_latitude }}, lng: {{ $parameter->departure_longitude }} };
		const redPin = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
		const bluePin = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
		const greenPin = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";

		document.addEventListener('DOMContentLoaded', function() {
			var map = new google.maps.Map(document.getElementById('map'), {
				center: dartsLocation,
				zoom: 13
			});
			
			// dartsLocationにピン
			new google.maps.Marker({
				position: dartsLocation,
				map: map,
				icon: greenPin
			});
			
			var spotCheckboxes = document.querySelectorAll('.spot-checkbox');
			 // placeName を定義する例：マーカーの情報ウィンドウに表示される場所の名前
			
			spotCheckboxes.forEach(function(checkbox) {
				var lat = parseFloat(checkbox.getAttribute('data-latitude'));
				var lng = parseFloat(checkbox.getAttribute('data-longitude'));
				var placeName = checkbox.getAttribute('data-place-name');
				
				var marker = new google.maps.Marker({
					position: { lat: lat, lng: lng },
					map: map,
					icon: checkbox.checked ? redPin : bluePin
				});
				
				// マーカーをクリックしたときの処理
				google.maps.event.addListener(marker, 'click', function() {
					// ウィンドウに表示するコンテンツを作成
					var contentString = `
					<div>
						<p>クリックするとググれます！</p>
						<strong><a href="https://www.google.com/search?q=${encodeURIComponent(placeName)}" target="_blank">${placeName}</a></strong>
					</div>`;
				
					// インフォウィンドウのオプションを設定
					var infowindow = new google.maps.InfoWindow({
						content: contentString
					});
				
					// マーカーがクリックされたときにインフォウィンドウを開く
					infowindow.open(map, marker);
				});
			
				checkbox.addEventListener('change', function() {
					marker.setIcon(this.checked ? redPin : bluePin);
				});
			});
		});
	</script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&callback=initMap&libraries=places"></script>
</x-app-layout>

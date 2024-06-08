<x-app-layout>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&libraries=streetView&callback=initMap&v=weekly"></script>
	<x-slot name="title">ダーツの結果</x-slot>

	<div id="map" style="height: 600px; width:1200px; float: left;"></div>

	<div id="place-result" style="float: right; width: 300px;">
		<form id="spotsForm" action="/users/{{ Auth::id() }}/trip/list" method="POST">
			@csrf
			<div id="spot-list">
			</div>
			
			<input type="hidden" name="parameter_id" value={{ $parameter->id }}>
			<input type="hidden" name="dart_latitude" id="dart_latitude">
			<input type="hidden" name="dart_longitude" id="dart_longitude">
			
			<button type="submit">送信</button>
		</form>
	</div>

	<div style="clear: both;">
		{{ $parameter->spot_category->ja_name }}
	</div>

	<!-- Google Maps JavaScript -->
	<script>
		let map;
		let infoWindow;
		const departureLocation = { lat: {{ $parameter->departure_latitude }}, lng: {{ $parameter->departure_longitude }} };
		const r = getRadius();
		let dartLocation = {}; // dartLocation をグローバルスコープで宣言
		const redPin = "https://maps.google.com/mapfiles/ms/micons/red-pushpin.png";
		const orangePin = "http://maps.google.com/mapfiles/ms/icons/orange-dot.png";
		const bluePin = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
		const greenPin = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";
		
		function placeMarker(pos, iconUrl) {
			return new google.maps.Marker({
				position: pos,
				map: map,
				icon: {
					url: iconUrl
				}
			});
		}
		
		function getRadius(departureLocation) {
			const transportation = "{{ $parameter->transportation }}";
			const tripTime = {{ $parameter->trip_time }};
			let radius = 0;
			if (transportation === "徒歩") {
				radius = 40 * tripTime;
			} else if (transportation === "自転車") {
				radius = 170 * tripTime;
			} else if (transportation === "車") {
				radius = 370 * tripTime;
			}
			return radius;
		}
		
		function drawCircle(pos, r) {
			const cityCircle = new google.maps.Circle({
				strokeColor: "#ED1A3D",
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: "#F0566E",
				fillOpacity: 0.2,
				map: map,
				center: pos,
				radius: r,
			});
		}
		
// 			function processSVData({ data }) {		// processSVData 関数を initMap 関数の外に移動する
// 				const location = data.location;		// map 変数を参照するため、map を関数の外で定義するか、引数として渡す必要があります
// 				placeMarker(location.latLng, redPin);
// 				map.setCenter(location.latLng);
// 				dartLocation.lat = data.location.lat();		// データが取得された後に dartLocation の値をセットする
// 				dartLocation.lng = data.location.lng();		// dartLocation の値を更新
			
// 				// Hidden Inputs の値を設定
// 				document.getElementById('dart_latitude').value = dartLocation.lat;
// 				document.getElementById('dart_longitude').value = dartLocation.lng;
// 			}
// 			console.log(location);
		
		function initMap() {
			map = new google.maps.Map(document.getElementById("map"), {
				center: departureLocation,
				zoom: 14,
			});
			
			infoWindow = new google.maps.InfoWindow();
			
			placeMarker(departureLocation, greenPin);
			
			drawCircle(departureLocation, r);
			
			// ストリートビューのあるところにランダムにピンを指す機能
			const sv = new google.maps.StreetViewService();
				var outerRadius = r;
				var innerRadius = outerRadius - outerRadius / 10;
				var angle = Math.random() * Math.PI * 2;
				var distance = Math.sqrt(Math.random()) * (outerRadius - innerRadius) + innerRadius;
				var dartLat = departureLocation.lat + (distance / 111111) * Math.cos(angle);
				var dartLng = departureLocation.lng + (distance / (111111 * Math.cos(departureLocation.lat * Math.PI / 180))) * Math.sin(angle);
				var dartLocation = { lat: dartLat, lng: dartLng };
				console.log(dartLocation);
				
			placeMarker(dartLocation, redPin);
			map.setCenter(dartLocation);
			//   Street View のデータを取得
			// sv.getPanorama({ location: dartLocation, radius: r*0.3 })
			// 	.then(processSVData)
			// 	.catch((e) => console.error("Street View data not found for this location."));
			
			document.getElementById('dart_latitude').value = dartLocation.lat;
			document.getElementById('dart_longitude').value = dartLocation.lng;
		
		const request = {
				location: dartLocation,
				radius: r*0.5,
				type: ['{{ $parameter->spot_category->en_name }}'],
			}
			
			const service = new google.maps.places.PlacesService(map);
			service.nearbySearch(request, callback);
		}
		
		function callback(results, status) {
			if (status == google.maps.places.PlacesServiceStatus.OK) {
				const spotListDiv = document.getElementById('spot-list');
				spotListDiv.innerHTML = '';
				
				for (var i = 0; i < results.length; i++) {
					const spot = results[i];
					placeMarkerWithInfo(spot.geometry.location.lat(), spot.geometry.location.lng(), bluePin, spot);
					
					const spotElement = document.createElement('div');
					spotElement.innerHTML = `
						<label>
							<input type="checkbox" name="spot[${i}][selected]" value="1">
							${spot.name}
						</label>
						<input type="hidden" name="spot[${i}][spot_category_id]" value="{{ $parameter->spot_category->id }}">
						<input type="hidden" name="spot[${i}][name]" value="${spot.name}">
						<input type="hidden" name="spot[${i}][latitude]" value="${spot.geometry.location.lat()}">
						<input type="hidden" name="spot[${i}][longitude]" value="${spot.geometry.location.lng()}">
					`;
					spotListDiv.appendChild(spotElement);
				}
			} else {
				document.getElementById('place-result').innerHTML = "該当する施設が見つかりませんでした。";
			}
		}
		
		function placeMarkerWithInfo(lat, lng, iconUrl, place) {
			const marker = placeMarker({ lat: lat, lng: lng }, iconUrl);
			
			google.maps.event.addListener(marker, 'click', function() {
				const content = `
					<div>
						<p>クリックするとググれます！</p>
						<strong><a href="https://www.google.com/search?q=${encodeURIComponent(place.name)}" target="_blank">${place.name}</a></strong>
						<p>${place.vicinity}</p>
					</div>`;
				infoWindow.setContent(content);
				infoWindow.open(map, marker);
			});
		}
	</script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&callback=initMap&libraries=places"></script>
</x-app-layout>
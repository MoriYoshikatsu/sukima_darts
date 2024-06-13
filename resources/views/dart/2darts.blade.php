<x-app-layout>
	<x-slot name="title">ダーツの結果</x-slot>

	<div class="container mx-auto p-4 space-y-4">
		<div class="items-center border p-4 bg-white rounded-md shadow-sm mb-4">
			<div id="map" class="map" style="height: 500px; width: 100%;"></div>
		</div>

		<!-- Place Result Section -->
		<div id="place-result" class="w-full bg-white p-4 rounded shadow">
			<form id="spotsForm" action="/users/{{ Auth::id() }}/trip/list" method="POST">
				@csrf
				<div id="spot-list" class="mb-4 space-y-2"></div>
	
				<input type="hidden" name="parameter_id" value="{{ $parameter->id }}">
				<input type="hidden" name="dart_latitude" id="dart_latitude">
				<input type="hidden" name="dart_longitude" id="dart_longitude">
	
				<button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-300">送信</button>
			</form>
		</div>
	</div>

	<!-- Google Maps JavaScript -->
	<script>
        let map;
        let infoWindow;
        const departureLocation = { lat: {{ $parameter->departure_latitude }}, lng: {{ $parameter->departure_longitude }} };
        const r = getRadius();
        const redPin = "https://maps.google.com/mapfiles/ms/micons/red-pushpin.png";
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

        function getRadius() {
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

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: departureLocation,
                zoom: 14,
            });

            infoWindow = new google.maps.InfoWindow();

            placeMarker(departureLocation, greenPin);

            drawCircle(departureLocation, r);

            findStreetViewLocation();

            const request = {
                location: dartLocation,
                radius: r * 0.5,
                type: ['{{ $parameter->spot_category->en_name }}'],
            };

            const service = new google.maps.places.PlacesService(map);
            service.nearbySearch(request, callback);
        }

        function findStreetViewLocation() {
            const sv = new google.maps.StreetViewService();
            const maxAttempts = 10;
            let attempts = 0;

            function getRandomLocation() {
                const outerRadius = r;
                const innerRadius = outerRadius - outerRadius / 10;
                const angle = Math.random() * Math.PI * 2;
                const distance = Math.sqrt(Math.random() * (outerRadius * outerRadius - innerRadius * innerRadius) + innerRadius * innerRadius);
                const dartLat = departureLocation.lat + (distance / 111111) * Math.cos(angle);
                const dartLng = departureLocation.lng + (distance / (111111 * Math.cos(departureLocation.lat * Math.PI / 180))) * Math.sin(angle);
                return { lat: dartLat, lng: dartLng };
            }

            function checkStreetView(location) {
                sv.getPanorama({ location: location, radius: 50 }, (data, status) => {
                    if (status === google.maps.StreetViewStatus.OK) {
                        placeMarker(location, redPin);
                        map.setCenter(location);
                        document.getElementById('dart_latitude').value = location.lat;
                        document.getElementById('dart_longitude').value = location.lng;
                    } else if (attempts < maxAttempts) {
                        attempts++;
                        findStreetViewLocation(); // 再試行
                    } else {
                        console.error("Street View data not found in the vicinity.");
                    }
                });
            }

            checkStreetView(departureLocation); // 出発地点から探し始める
        }

        function callback(results, status) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
                const spotListDiv = document.getElementById('spot-list');
                spotListDiv.innerHTML = '';

                for (var i = 0; i < results.length; i++) {
                    const spot = results[i];
                    placeMarkerWithInfo(spot.geometry.location.lat(), spot.geometry.location.lng(), bluePin, spot);

                    const spotElement = document.createElement('div');
                    spotElement.className = 'bg-gray-100 p-2 rounded shadow mb-2';
                    spotElement.innerHTML = `
                        <label class="flex items-center">
                            <input type="checkbox" name="spot[${i}][selected]" value="1" class="mr-2">
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

            google.maps.event.addListener(marker, 'click', function () {
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

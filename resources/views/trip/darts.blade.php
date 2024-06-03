<x-app-layout>
    <x-slot name="title">ダーツの結果</x-slot>

    <div id="map" style="height: 600px; width:1200px; float: left;"></div>

    <div id="place-result" style="float: right; width: 300px;">
        <form id="spotsForm" action="/users/{{ Auth::id() }}/trip/list" method="POST">
            @csrf
            <div id="spot-list"></div>
            <input type="hidden" name="parameter_id" value={{ $parameter->id }}>
            <input type="hidden" name="first_latitude" id="dart_latitude">
            <input type="hidden" name="first_longitude" id="dart_longitude">
            <button type="submit">送信</button>
        </form>
    </div>

    <div style="clear: both;">
        {{ $parameter->spot_category->en_name }}
    </div>

    <!-- Google Maps JavaScript -->
    <script>
        let map;
        let infoWindow;
        const departureLocation = { lat: {{ $parameter->departure_latitude }}, lng: {{ $parameter->departure_longitude }} };
        const redPin = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
        const orangePin = "http://maps.google.com/mapfiles/ms/icons/orange-dot.png";
        const bluePin = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
        const greenPin = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: departureLocation,
                zoom: 13
            });

            infoWindow = new google.maps.InfoWindow();

            placeMarker(departureLocation, redPin);

            const dart_pos = getRandomLocationInCircle(departureLocation, getRadius());
            placeMarker(dart_pos, greenPin);

            // Set dart position to hidden inputs
            document.getElementById('dart_latitude').value = dart_pos.lat;
            document.getElementById('dart_longitude').value = dart_pos.lng;

            const r = getRadius();
            drawCircle(dart_pos, r);

            const request = {
                location: dart_pos,
                radius: r,
                type: ['{{ $parameter->spot_category->en_name }}'],
            }

            const service = new google.maps.places.PlacesService(map);
            service.nearbySearch(request, callback);
        }

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
            let radius = 0;
            const transportation = "{{ $parameter->transportation }}";
            const tripTime = {{ $parameter->trip_time }};
            if (transportation === "徒歩") {
                radius = 80 * tripTime;
            } else if (transportation === "自転車") {
                radius = 250 * tripTime;
            } else if (transportation === "車") {
                radius = 1000 * tripTime;
            }
            return radius;
        }

        function getRandomLocationInCircle(center, radius) {
            const angle = Math.random() * Math.PI * 2;
            const distance = Math.random() * radius;
            const deltaLat = distance * Math.cos(angle) / 111320;
            const deltaLng = distance * Math.sin(angle) / (111320 * Math.cos(center.lat * Math.PI / 180));

            return {
                lat: center.lat + deltaLat,
                lng: center.lng + deltaLng
            };
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

        function callback(results, status) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
                const spotListDiv = document.getElementById('spot-list');
                spotListDiv.innerHTML = '';

                for (var i = 0; i < results.length; i++) {
                    const spot = results[i];
                    placeMarkerWithInfo(spot.geometry.location.lat(), spot.geometry.location.lng(), bluePin, spot);

                    const spotElement = document.createElement('div');
                    spotElement.innerHTML = `
                        <input type="checkbox" name="spot[${i}][selected]" value="1">
                        <label>${spot.name}</label>
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
                        <strong><a href="https://www.google.com/search?q=${encodeURIComponent(place.name)}" target="_blank">${place.name}</a></strong>
                        <p>${place.vicinity}</p>
                    </div>`;
                infoWindow.setContent(content);
                infoWindow.open(map, marker);
            });
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHmS-cMWkslPonyCsDSuV8hyc5U6p55ps&callback=initMap&libraries=places"></script>
</x-app-layout>

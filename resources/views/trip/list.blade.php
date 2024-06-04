<x-app-layout>
    <x-slot name="title">トリップリスト作成</x-slot>

    <div>
        <form method="POST" action="/store/spot_trip/status" id="spotForm">
            @csrf
            @foreach ($spotTrips as $spotTrip)
                <div>
                    <input type="checkbox" id="spot_{{ $spotTrip->spot->id }}" name="spots[]" value="{{ $spotTrip->spot->id }}" data-latitude="{{ $spotTrip->spot->latitude }}" data-longitude="{{ $spotTrip->spot->longitude }}" class="spot-checkbox">
                    <label for="spot_{{ $spotTrip->spot->id }}">{{ $spotTrip->spot->name }}</label>
                </div>
            @endforeach
            <button type="submit">送信</button>
        </form>
    </div>

    <div id="map" style="height: 500px; width: 100%;"></div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHmS-cMWkslPonyCsDSuV8hyc5U6p55ps"></script>
    <script>
        const dartsLocation = { lat: {{ $trip->first_latitude }}, lng: {{ $trip->first_longitude }} };
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

            spotCheckboxes.forEach(function(checkbox) {
                var lat = parseFloat(checkbox.getAttribute('data-latitude'));
                var lng = parseFloat(checkbox.getAttribute('data-longitude'));

                var marker = new google.maps.Marker({
                    position: { lat: lat, lng: lng },
                    map: map,
                    icon: checkbox.checked ? redPin : bluePin
                });

                checkbox.addEventListener('change', function() {
                    marker.setIcon(this.checked ? redPin : bluePin);
                });
            });
        });
    </script>
</x-app-layout>

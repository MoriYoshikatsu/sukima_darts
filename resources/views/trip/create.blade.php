<x-app-layout>
    <x-slot name="title">投稿作成</x-slot>

    <div>
        <form method="POST" action="/store/trip">
            @csrf
            <input type="hidden" name="first_latitude" value={{ $trip->first_latitude }}>
            <input type="hidden" name="first_longitude" value={{ $trip->first_longitude }}>
            
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
            
            <div>
                @foreach ($wentSpotTrips as $wentSpotTrip)
                    <div>
                        {{ $wentSpotTrip->spot->name }}
                    </div>
                @endforeach
            </div>

            <button type="submit">投稿</button>
        </form>

        <br>

        <!-- Google マップ表示 -->
        <div id="map" style="height: 500px; width: 100%;"></div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHmS-cMWkslPonyCsDSuV8hyc5U6p55ps"></script>
    <script>
        const greenPin = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";
        const redPin = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";

        document.addEventListener('DOMContentLoaded', function() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: {{ $trip->first_latitude }}, lng: {{ $trip->first_longitude }} },
                zoom: 13
            });

            new google.maps.Marker({
                position: { lat: {{ $trip->first_latitude }}, lng: {{ $trip->first_longitude }} },
                map: map,
                icon: greenPin
            });

            @foreach ($wentSpotTrips as $wentSpotTrip)
                new google.maps.Marker({
                    position: { lat: {{ $wentSpotTrip->spot->latitude }}, lng: {{ $wentSpotTrip->spot->longitude }} },
                    map: map,
                    icon: redPin
                });
            @endforeach
        });
    </script>
</x-app-layout>

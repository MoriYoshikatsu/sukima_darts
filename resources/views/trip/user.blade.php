<x-app-layout>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&libraries=streetView&callback=initMap&v=weekly"></script>
	<x-slot name="title">{{ $trip->title }}</x-slot>
	<div class="container mx-auto p-4">
		<div class="flex justify-between items-center border-b pb-2">
			<div class="text-xl font-bold">User名</div>
			<div class="mt-4">
				<p class="text-lg">自己紹介文</p>
			</div>
			<div class="flex space-x-4">
				<a href="/users/{user}/trip/{trip}/index" class="text-blue-500">フォロー</a>
				<a href="/users/{user}/trip/{trip}/follower" class="text-blue-500">フォロワー</a>
				<a href="#" class="text-blue-500">いいね</a>
				<a href="#" class="text-blue-500">ブックマーク</a>
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
				<ul>
					@foreach($trips as $tripz)
						<!--@if($tripz->status == 1)-->
							<a href="/users/{{ Auth::id() }}/trip/{{ $tripz->id }}">{{ $tripz->title }}</a>
						<!--@endif-->
					@endforeach
				</ul>
			</div>
		
			<!-- Main Content -->
			<div class="w-4/5 pl-4">
				<h2 class="text-2xl font-bold mb-4">トリップリスト{{ $trip->title }}</h2>

				<div class="flex mb-4">
					<button class="bg-blue-500 text-white py-2 px-4 rounded mr-2">この計画リストを公開する</button>
					<button class="bg-gray-500 text-white py-2 px-4 rounded">実際に行った日付詳細ブレダウン</button>
				</div>

				<div class="border p-4 mb-4">
					<p>計画リストをピンで一括表示したグーグルマップ</p>
					<!-- Google マップ表示 -->
					<div id="map" style="height: 500px; width: 100%;"></div>
				</div>

				<div class="flex">
					<!-- Spot List -->
					<div class="w-1/4 border-r pr-4">
						<h3 class="text-lg font-bold mb-2">行きたいスポット</h3>
						<ul>
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
						</ul>
					</div>

					<!-- Main Details -->
					<div class="w-3/4 pl-4">
						<h3 class="text-lg font-bold mb-2">計画リストのアピールポイント</h3>
							<textarea id="description" name="description">{{ $trip->description }}</textarea>
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
							
							<button type="submit">編集完了</button>
							</form>
						</div>
						<div class="mt-4 border-t pt-2">
							<h4 class="text-md font-bold">読者からのコメント及び5つ星評価</h4>
							<!-- コメントと評価をここに表示 -->
						</div>
					</div>
				</div>
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
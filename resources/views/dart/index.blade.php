<x-app-layout>
	<x-slot name="title">ダーツの条件入力</x-slot>

	<div class="container mx-auto p-4">
		<form action="/users/{{ Auth::id() }}/trip/input" method="POST" class="space-y-6">
			@method('post')
			@csrf
			<input type="hidden" name="parameter[user_id]" value="{{ Auth::id() }}"/>
			<div class="max-w-7xl mx-auto space-y-6">
				<!-- 移動手段の選択 -->
				<div class="space-y-2">
					<label for="transportation" class="block text-sm font-medium text-gray-700">移動手段を選択:</label>
					<select name="parameter[transportation]" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
						<option value="徒歩">徒歩</option>
						<option value="自転車">自転車</option>
						<option value="車">車</option>
					</select>
				</div>
	
				<!-- 移動可能時間の選択 -->
				<div class="space-y-2">
					<label for="trip_time" class="block text-sm font-medium text-gray-700">移動可能時間を選択:</label>
					<select name="parameter[trip_time]" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
						@for ($i = 15; $i <= 720; $i += 15)
							<option value="{{ $i }}">{{ intdiv($i, 60) }}時間{{ $i % 60 }}分</option>
						@endfor
					</select>
				</div>
	
				<!-- 行きたい場所のタグの選択 -->
				<div class="space-y-2">
					<label for="spot_category" class="block text-sm font-medium text-gray-700">行きたい場所のタグを選択:</label>
					<select name="parameter[spot_category_id]" id="spot_category" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
						@foreach ($spot_categories as $spot_category)
							<option value="{{ $spot_category->id }}">{{ $spot_category->ja_name }}</option>
						@endforeach
					</select>
				</div>
	
				<!-- 出発地の選択 -->
				<div class="space-y-2">
					<label for="departure_location" class="block text-sm font-medium text-gray-700">出発地を選択:</label>
					<div class="items-center border p-4 bg-white rounded-md shadow-sm mb-4">
						<!--<div id="map" style="height: 80%; width:80%; rounded-md shadow-sm"></div>-->
						<div id="map" class="map" style="height: 500px; width: 100%;"></div>
					</div>
					<input type="hidden" name="parameter[departure_latitude]" id="departure_latitude">
					<input type="hidden" name="parameter[departure_longitude]" id="departure_longitude">
					<input id="address" type="text" name="post[address]" placeholder="出発地名を入力" value="" class="mt-2 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"/>
					<p class="text-red-500 text-sm mt-1">{{ $errors->first('address') }}</p>
					<button onclick="searchPlaces()">検索</button>
				</div>
	
				<div class="flex justify-end">
					<button type="submit" class="relative inline-flex items-center justify-center px-6 py-3 overflow-hidden font-medium text-white transition duration-300 ease-out bg-blue-600 rounded-md shadow-lg group hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
						<span class="absolute inset-0 w-full h-full bg-gradient-to-br from-blue-500 to-blue-600"></span>
						<span class="relative">この条件でダーツを投げる</span>
					</button>
				</div>
			</div>
		</form>
	</div>

	<!-- Google Maps JavaScript -->
	<script>
		let map;
		let marker;
		let service;
		let infowindow;
		function initMap(position) {	// Google Maps API を使用して地図を初期化する関数
			map = new google.maps.Map(document.getElementById('map'), {		// 地図を表示する要素を取得・新しい地図インスタントの作成
				center: {lat: 35.6585769, lng: 139.7454506},	// 東京駅を中心に表示
				zoom: 15
			});
			infowindow = new google.maps.InfoWindow();
			
			if (navigator.geolocation) {	// 現在位置を取得して地図の中心に設定する
				navigator.geolocation.getCurrentPosition(function(position) {
					var pos = {
						lat: position.coords.latitude,
						lng: position.coords.longitude
					};
					map.setCenter(pos);		// 現在地を画面中心に
					createMarker(pos);	// 現在地にピン
					displayAddress(pos);	// 住所を表示する関数を呼び出し
					document.getElementById('departure_latitude').value = pos.lat;
					document.getElementById('departure_longitude').value = pos.lng;
				}, function() {
					handleLocationError(true, infoWindow, map.getCenter());
				});
			} else {
				handleLocationError(false, infoWindow, map.getCenter());	// ブラウザが位置情報をサポートしていない場合のエラー処理
			}
			
			map.addListener('click', function(event) {		// クリックした位置の緯度経度を取得してフォームにセットする
				map.setCenter(event.latLng);
				createMarker(event.latLng);
				displayAddress(event.latLng);
				document.getElementById('departure_latitude').value = event.latLng.lat();
				document.getElementById('departure_longitude').value = event.latLng.lng();
			});
		}
		
		function searchPlaces() {
			event.preventDefault(); // フォームのデフォルトの送信動作を防ぐ
			const searchInput = document.getElementById('address').value;
			const request = {
				query: searchInput,
				fields: ['name', 'geometry'],
				locationBias: map.getCenter()
			};
		
			service = new google.maps.places.PlacesService(map);
			service.findPlaceFromQuery(request, function(results, status) {
				if (status === google.maps.places.PlacesServiceStatus.OK && results) {
					for (let i = 0; i < results.length; i++) {
						createMarker(results[i]);
					}
					map.setCenter(results[0].geometry.location);
				}
			});
		}

		function createMarker(location) {
			const marker = new google.maps.Marker({
				map: map,
				position: place.geometry.location
			});
			
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.setContent(place.name);
				infowindow.open(map, this);
			});
		
		}
		
		function displayAddress(latlng) {	//Geocoderオブジェクトを作成
			var geocoder = new google.maps.Geocoder();	//Geocoderオブジェクトを作成
			geocoder.geocode({'location': latlng}, function(results, status) {	//住所を取得するリクエストを作成
				if (status === google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						document.getElementById('address').value = results[0].formatted_address;	// 取得した住所を表示
					} else {
						console.log('No results found');
					}
					} else {
						console.log('Geocoder failed due to: ' + status);
					}
			});
		}
		
		function handleLocationError(browserHasGeolocation, infoWindow, pos) {
			console.error('位置情報の取得に失敗しました。');
		}
		
	</script>
	<script async defer	src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&callback=initMap&libraries=places"></script>
</x-app-layout>

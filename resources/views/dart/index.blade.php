<x-app-layout>
    <x-slot name="title">ダーツの条件入力</x-slot>

    <!-- Google Maps API の読み込み -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&libraries=places"></script>

    <div class="px-auto">
        <form action="/users/{{ Auth::id() }}/trip/input" method="POST">
            @method('post')
            @csrf
            <input type="hidden" name="parameter[user_id]" value="{{ Auth::id() }}"/>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- 移動手段の選択 -->
                <div>
                    <label for="transportation">移動手段を選択:</label>
                    <select name="parameter[transportation]">
                        <option value="徒歩">徒歩</option>
                        <option value="自転車">自転車</option>
                        <option value="車">車</option>
                    </select>
                </div>
    
                <!-- 移動可能時間の選択 -->
                <div>
                    <label for="trip_time">移動可能時間を選択:</label>
                    <select name="parameter[trip_time]">
                        @for ($i = 15; $i <= 720; $i += 15)
                            <option value="{{ $i }}">{{ intdiv($i, 60) }}時間{{ $i % 60 }}分</option>
                        @endfor
                    </select>
                </div>
    
                <!-- 行きたい場所のタグの選択 -->
                <div>
                    <label for="spot_category">行きたい場所のタグを選択:</label>
                    <select name="parameter[spot_category_id]" id="spot_category">
                        @foreach ($spot_categories as $spot_category)
                            <option value="{{ $spot_category->id }}">{{ $spot_category->ja_name }}</option>
                        @endforeach
                    </select>
                </div>
    
                <!-- 出発地の選択 -->
                <div>
                    <label for="departure_location">出発地を選択:</label>
                    <div id="map" style="height: 400px; width:1200px;"></div>
                    <input type="hidden" name="parameter[departure_latitude]" id="departure_latitude">
                    <input type="hidden" name="parameter[departure_longitude]" id="departure_longitude">
                    <input id="address" type="text" name="post[address]" placeholder="出発地" value=""/>
    				<p class="title__error" style="color:red">{{ $errors->first('address') }}</p>
                </div>
    
                <input type="submit" value="この条件でダーツを投げる">
            </div>
        </form>
    </div>

    <!-- Google Maps JavaScript -->
    <script>
        let map;
		let marker;
		
		function initMap(position) {	// Google Maps API を使用して地図を初期化する関数
			map = new google.maps.Map(document.getElementById('map'), {		// 地図を表示する要素を取得・新しい地図インスタントの作成
				center: {lat: 35.6585769, lng: 139.7454506},	// 東京駅を中心に表示
				zoom: 15
			});
			
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
		
		function createMarker(location) {
			if (marker) {
				marker.setPosition(location);
			} else {
				marker = new google.maps.Marker({
					position: location,
					map: map
				});
			}
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
	<script async defer
	src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_key') }}&callback=initMap&libraries=places">
	</script>
</x-app-layout>

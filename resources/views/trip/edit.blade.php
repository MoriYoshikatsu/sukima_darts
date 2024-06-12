<x-app-layout>
	<x-slot name="title">{{ $trip->title }}の編集</x-slot>
	
	<div class="container mx-auto p-4">
		<div class="flex justify-between items-center border-b pb-2">
			<div class="text-xl font-bold">{{ Auth::user()->name }}</div>
			<div class="mt-4">
				<p class="text-lg">{{ Auth::user()->introduce }}</p>
			</div>
		</div>
		
		<div class="flex mt-4">
			<!-- Sidebar -->
			<div class="w-1/5 border-r pr-4">
				<h2 class="text-2xl font-bold mb-4">{{ Auth::user()->name }}の旅行先</h2>
				<ul>
					@foreach($trips as $tripz)
						<div>
							<a href="/users/{{ Auth::id() }}/edit/trip/{{ $tripz->id }}">{{ $tripz->title }}</a>
						</div>
					@endforeach
				</ul>
			</div>
		
			<!-- Main Content -->
			<div class="w-4/5 pl-4">
				<h2 class="text-2xl font-bold mb-4">{{ $trip->title }}</h2>

				<div class="items-center border p-4 bg-white rounded-md shadow-sm mb-4">
					<p>計画リストをピンで一括表示したグーグルマップ</p>
					<div id="map" class="map" style="height: 500px; width: 100%;"></div>
				</div>

				<form method="POST" action="/users/{{ Auth::id() }}/put/trip/{{ $trip->id }}">
					@csrf
					@method('PUT')
					<div class="flex justify">
						<!-- Spot List -->
						<div class="w-1/2 border-r pr-4">
							<h3 class="text-lg font-bold mb-2">行きたいスポット</h3>
							<div>
								<label class="block text-xl font-bold text-gray-700">まだ行ってない場所</label>
								@foreach ($SpotTrips as $SpotTrip)
									@if($SpotTrip->status == 0)
										<div>
											<input type="checkbox" id="spot_{{ $SpotTrip->spot->id }}" name="spots[]" value="{{ $SpotTrip->spot->id }}" data-latitude="{{ $SpotTrip->spot->latitude }}" data-longitude="{{ $SpotTrip->spot->longitude }}" data-place-name="{{ $SpotTrip->spot->name }}" class="spot-checkbox">
											<label for="spot_{{ $SpotTrip->spot->id }}">{{ $SpotTrip->spot->name }}</label>
										</div>
									@endif
								@endforeach
							</div>
							<div>
								<label class="block text-xl font-bold text-gray-700">行ったことのある場所</label>
								@foreach ($SpotTrips as $SpotTrip)
									@if($SpotTrip->status == 1)
										<div>
											<input type="checkbox" id="spot_{{ $SpotTrip->spot->id }}" name="spots[]" value="{{ $SpotTrip->spot->id }}" data-latitude="{{ $SpotTrip->spot->latitude }}" data-longitude="{{ $SpotTrip->spot->longitude }}" data-place-name="{{ $SpotTrip->spot->name }}" class="spot-checkbox">
											<label for="spot_{{ $SpotTrip->spot->id }}">{{ $SpotTrip->spot->name }}</label>
										</div>
									@endif
								@endforeach
							</div>
						</div>
	
						<!-- Main Details -->
						<div class="w-1/2 pl-4">
							<h3 class="text-lg font-bold mb-2">計画リストのアピールポイント</h3>
						
							<!--<div class="flex justify-between h-16">-->
							<label for="title" class="block text-xl font-bold text-gray-700">タイトル</label>
							<input type="text" id="title" name="title" value="{{ old('title', $trip->title) }}">
							<br>
				
							<label for="description" class="block text-xl font-bold text-gray-700">説明</label>
							<textarea id="description" name="description">{{ old('description', $trip->description) }}</textarea>
							<br>
				
							<label for="trip_date" class="block text-xl font-bold text-gray-700">旅行日</label>
							<input type="date" id="trip_date" name="trip_date" value="{{ old('trip_date', $trip->trip_date) }}">
							<br>
				
							<label for="status" class="block text-xl font-bold text-gray-700">公開状態</label>
							<select id="status" name="status">
								<option value="1" {{ $trip->status == 1 ? 'selected' : '' }}>公開</option>
								<option value="0" {{ $trip->status == 0 ? 'selected' : '' }}>非公開</option>
							</select>
							<br>
							<button type="submit" class="relative inline-flex items-center justify-center px-6 py-3 overflow-hidden font-medium text-white transition duration-300 ease-out bg-blue-600 rounded-md shadow-lg group hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
								<span class="absolute inset-0 w-full h-full bg-gradient-to-br from-blue-500 to-blue-600"></span>
								<span class="relative">編集完了</span>
							</button>
						</div>
					</div>
				</form>
				
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
				center: dartLocation,
				zoom: 13
			});
			
			var dartmarker = new google.maps.Marker({
				position: dartLocation,
				map: map,
				icon: greenPin
			});
			
			@foreach ($SpotTrips as $SpotTrip)
				@if($SpotTrip->status == 0)
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
				@endif
			@endforeach
			
			@foreach ($SpotTrips as $SpotTrip)
				@if($SpotTrip->status == 1)
					var wentSpotMarker = new google.maps.Marker({
						position: { lat: {{ $SpotTrip->spot->latitude }}, lng: {{ $SpotTrip->spot->longitude }} },
						map: map,
						icon: redPin
					});
					wentSpotMarker.set('placeName', "{{ $SpotTrip->spot->name }}");
			
					google.maps.event.addListener(wentSpotMarker, 'click', (function(marker) {
						return function() {
							var placeName = marker.get('placeName');
							showInfoWindow(placeName, map, marker);
						};
					})(wentSpotMarker));
				@endif
			@endforeach
			
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
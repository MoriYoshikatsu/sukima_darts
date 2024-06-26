<x-app-layout>
	<x-slot name="title">{{ Auth::user()->name }}のフォロワー</x-slot>
	
	<div class="container mx-auto p-4">
		<form method="POST" action="/users/{{ Auth::id() }}/follower/search" class="mb-4">
			@csrf
			<div class="flex mb-4">
				<input type="text" name="search" placeholder="ユーザー名検索" value="{{ request('search') }}" class="w-1/2 p-2 border mr-4">
				<div class="text-right">
					<button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">検索</button>
				</div>
			</div>
		</form>

		 @if ($target_users->isNotEmpty())
			<div class="space-y-4">
				<h2 class="text-xl font-bold mb-4">検索結果</h2>
				@foreach ($target_users as $target_user)
					<div class="flex items-center border p-4 bg-white rounded-md shadow-sm">
						<div class="w-3/4">
							<a href='/users/{{ $target_user->id }}/others' class="text-lg font-bold mb-1">{{ $target_user->name }}</a>
							<p class="text-sm text-gray-600">初期ランダムピン地点名</p>
							<p class="text-sm text-gray-600">行ったスポットのタグカテゴリー</p>
						</div>
						<div class="w-1/4 bg-gray-400 text-white py-2 px-4 rounded">
							@switch($target_user->relation())
								@case(0) <span>はじめまして</span> @break
								@case(1) <span>フォローしています</span> @break
								@case(2) <span>フォローされています</span> @break
								@case(3) <span>相互フォロー</span> @break
								@default <span>エラー</span>
							@endswitch
						</div>
						<div class="w-1/4 text-right">
							<form method="POST" action="/users/{{ Auth::id() }}/follower/update">
								@csrf
								<input name="follow_id" type="hidden" value="{{ $target_user->id }}" />
								@if($target_user->isFollow())
									<button type="submit" class="bg-red-500 text-white py-2 px-4 rounded">フォロー解除</button>
								@else
									<button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">フォローする</button>
								@endif
							</form>
						</div>
					</div>
				@endforeach
			</div>
		@endif

		<div class="space-y-4 mt-8">
			<h2 class="text-xl font-bold mb-4">フォロワーユーザー</h2>
			@foreach ($followers as $follower)
				<div class="flex items-center border p-4 bg-white rounded-md shadow-sm">
					<div class="w-3/4">
						<a href='/users/{{ $follower->id }}/others' class="text-lg font-bold mb-1">{{ $follower->name }}</a>
						<p class="text-sm text-gray-600">初期ランダムピン地点名</p>
						<p class="text-sm text-gray-600">行ったスポットのタグカテゴリー</p>
					</div>
					<div class="w-1/4 bg-gray-400 text-white py-2 px-4 rounded">
						@switch($follower->relation())
							@case(0) <span>はじめまして</span> @break
							@case(1) <span>フォローしています</span> @break
							@case(2) <span>フォローされています</span> @break
							@case(3) <span>相互フォロー</span> @break
							@default <span>エラー</span>
						@endswitch
					</div>
					<div class="w-1/4 text-right">
						<form method="POST" action="/users/{{ Auth::id() }}/follower/update">
							@csrf
							<input name="follow_id" type="hidden" value="{{ $follower->id }}" />
							@if($follower->isFollow())
								<button type="submit" class="bg-red-500 text-white py-2 px-4 rounded">フォロー解除</button>
							@else
								<button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">フォローする</button>
							@endif
						</form>
					</div>
				</div>
			@endforeach
		</div>

		<div class="mt-4 text-center">
			<p class="text-gray-500">...</p>
		</div>
	</div>
</x-app-layout>
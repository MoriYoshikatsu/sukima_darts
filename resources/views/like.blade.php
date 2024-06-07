<x-app-layout>
	<div class="container mx-auto p-4">
	    <div class="flex mb-4">
	        <input type="text" placeholder="検索ボックス" class="w-1/2 p-2 border mr-4">
	        <input type="text" placeholder="タグor日付ソートボックス" class="w-1/2 p-2 border">
	    </div>

	    <div class="space-y-4">
	        @foreach ($users as $user)
	            <div class="flex items-center border p-4 bg-white rounded-md shadow-sm">
	                <div class="w-3/4">
	                    <h2 class="text-lg font-bold mb-1">{{ $user->name }}</h2>
	                    <p class="text-sm text-gray-600">初期ランダムピン地点名</p>
	                    <p class="text-sm text-gray-600">行ったスポットのタグカテゴリー</p>
	                </div>
	                <div class="w-1/4 text-right">
	                    <form method="POST" action="/users/{{ Auth::id() }}/follow">
							@csrf
							<input name="follow_id" type="hidden" value="{{ $user->id }}" />
							@if($user->isFollow())
								<button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">
									フォロー解除
								</button>
							@else
								<button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">
									フォローする
								</button>
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
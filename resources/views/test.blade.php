<x-app-layout>
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center border-b pb-2">
            <div class="text-xl font-bold">User名</div>
            <div class="mt-4">
                <p class="text-lg">自己紹介文</p>
            </div>
            <div class="flex space-x-4">
                <a href="#" class="text-blue-500">フォロー</a>
                <a href="#" class="text-blue-500">フォロワー</a>
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
                    @for ($i = 1; $i <= 15; $i++)
                        <li class="mb-2"><a href="#" class="text-blue-500">計画リスト{{ $i }}</a></li>
                    @endfor
                </ul>
            </div>

            <!-- Main Content -->
            <div class="w-4/5 pl-4">
                <h2 class="text-2xl font-bold mb-4">トリップリスト1 〜初期ランダムピン地点名〜 移動時間, 観光時間</h2>

                <div class="flex mb-4">
                    <button class="bg-blue-500 text-white py-2 px-4 rounded mr-2">この計画リストを公開する</button>
                    <button class="bg-gray-500 text-white py-2 px-4 rounded">実際に行った日付詳細ブレダウン</button>
                </div>

                <div class="border p-4 mb-4">
                    <p>計画リストをピンで一括表示したグーグルマップ</p>
                </div>

                <div class="flex">
                    <!-- Spot List -->
                    <div class="w-1/4 border-r pr-4">
                        <h3 class="text-lg font-bold mb-2">行きたいスポット</h3>
                        <ul>
                            @for ($i = 10; $i >= 1; $i--)
                                <li class="mb-2">行きたいスポット{{ $i }}</li>
                            @endfor
                        </ul>
                    </div>

                    <!-- Main Details -->
                    <div class="w-3/4 pl-4">
                        <h3 class="text-lg font-bold mb-2">計画リストのアピールポイント</h3>
                        <p>該当ホテル、オススメレストラン、メモなどを写真を添付して書き込めばよい</p>
                        <div class="mt-4 border-t pt-2">
                            <h4 class="text-md font-bold">読者からのコメント及び5つ星評価</h4>
                            <!-- コメントと評価をここに表示 -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
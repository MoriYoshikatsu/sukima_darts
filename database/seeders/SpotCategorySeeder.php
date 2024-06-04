<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpotCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'accounting' => '会計',
            'airport' => '空港',
            'amusement_park' => '遊園地',
            'aquarium' => '水族館',
            'art_gallery' => '美術館',
            'atm' => 'ATM',
            'bakery' => 'パン屋',
            'bank' => '銀行',
            'bar' => 'バー',
            'beauty_salon' => '美容院',
            'bicycle_store' => '自転車店',
            'book_store' => '書店',
            'bowling_alley' => 'ボウリング場',
            'bus_station' => 'バス停',
            'cafe' => 'カフェ',
            'campground' => 'キャンプ場',
            'car_dealer' => '自動車販売店',
            'car_rental' => 'レンタカー',
            'car_repair' => '自動車修理',
            'car_wash' => '洗車場',
            'casino' => 'カジノ',
            'cemetery' => '墓地',
            'church' => '教会',
            'city_hall' => '市役所',
            'clothing_store' => '衣料品店',
            'convenience_store' => 'コンビニエンスストア',
            'courthouse' => '裁判所',
            'dentist' => '歯科医',
            'department_store' => 'デパート',
            'doctor' => '医者',
            'drugstore' => '薬局',
            'electrician' => '電気技師',
            'electronics_store' => '電器店',
            'embassy' => '大使館',
            'fire_station' => '消防署',
            'florist' => '花屋',
            'funeral_home' => '葬儀場',
            'furniture_store' => '家具店',
            'gas_station' => 'ガソリンスタンド',
            'gym' => 'ジム',
            'hair_care' => '理髪店',
            'hardware_store' => '金物店',
            'hindu_temple' => 'ヒンドゥー教寺院',
            'home_goods_store' => 'ホームセンター',
            'hospital' => '病院',
            'insurance_agency' => '保険代理店',
            'jewelry_store' => '宝石店',
            'laundry' => 'クリーニング店',
            'lawyer' => '弁護士',
            'library' => '図書館',
            'light_rail_station' => 'ライトレール駅',
            'liquor_store' => '酒屋',
            'local_government_office' => '地方政府オフィス',
            'locksmith' => '鍵屋',
            'lodging' => '宿泊施設',
            'meal_delivery' => '宅配飲食',
            'meal_takeaway' => 'テイクアウト',
            'mosque' => 'モスク',
            'movie_rental' => '映画レンタル',
            'movie_theater' => '映画館',
            'moving_company' => '引越し業者',
            'museum' => '博物館',
            'night_club' => 'ナイトクラブ',
            'painter' => '塗装業者',
            'park' => '公園',
            'parking' => '駐車場',
            'pet_store' => 'ペットショップ',
            'pharmacy' => '薬局',
            'physiotherapist' => '理学療法士',
            'plumber' => '配管工',
            'police' => '警察',
            'post_office' => '郵便局',
            'primary_school' => '小学校',
            'real_estate_agency' => '不動産会社',
            'restaurant' => 'レストラン',
            'roofing_contractor' => '屋根工事業者',
            'rv_park' => 'RVパーク',
            'school' => '学校',
            'secondary_school' => '中学校・高校',
            'shoe_store' => '靴屋',
            'shopping_mall' => 'ショッピングモール',
            'spa' => 'スパ',
            'stadium' => 'スタジアム',
            'storage' => '倉庫',
            'store' => '店',
            'subway_station' => '地下鉄駅',
            'supermarket' => 'スーパーマーケット',
            'synagogue' => 'シナゴーグ',
            'taxi_stand' => 'タクシー乗り場',
            'tourist_attraction' => '観光名所',
            'train_station' => '鉄道駅',
            'transit_station' => '交通ハブ',
            'travel_agency' => '旅行代理店',
            'university' => '大学',
            'veterinary_care' => '動物病院',
            'zoo' => '動物園'
        ];

        foreach ($categories as $key => $value) {
            DB::table('spot_categories')->insert([
                'en_name' => $key,
                'ja_name' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

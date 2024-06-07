<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Models\Follow;

class FollowSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// for ($i = 1; $i <= 50; $i++) {
		// 	$id = rand(1, 5);
		// 	$id2 = rand(1, 5);
		// 	DB::table('follows')->insert([
		// 		'follower_id' => $id,
		// 		'followee_id' => $id2,
		// 		'created_at' => new DateTime(),
		// 		'updated_at' => new DateTime(),
		// 	]);
		// }
		$data = [
			//ID1~3は、相互フォロー
			[1, 2],[1, 3],[2, 1],[3, 1],

			//ID4は、ログインユーザー1がフォロー
			[1, 4],

			//ID5は、ログインユーザー1をフォロー
			[5, 1]
		];

		foreach($data as $record){
			Follow::create([
				'user_id' => $record[0],
				'follow_id' => $record[1],
			]);
		}
	}
}

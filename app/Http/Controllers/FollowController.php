<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FollowController extends Controller
{
	public function followee_index()
	{
		$followees_id = Follow::where('user_id', Auth::id())->pluck('follow_id'); // IDのリストを取得
		$followees = User::whereIn('id', $followees_id)->get(); // 取得したIDリストを使ってユーザーを取得
		$target_users = collect(); // 空のコレクションを初期化

		return view('followee')->with(['followees' => $followees, 'target_users' => $target_users]);
	}


	public function followee_search(Request $request)
	{
		// 検索キーワードを取得
		$search = $request->input('search');
		$target_users = User::where('name', 'like', '%' . $search . '%')->where('id', '!=', Auth::id())->get();
		
		$followees_id = Follow::where('user_id', Auth::id())->pluck('follow_id');
		$followees = User::whereIn('id', $followees_id)->get();

		return view('followee')->with(['target_users' => $target_users, 'followees' => $followees]);
	}


	public function followee_update(Request $request)
	{
		$follow_id = $request->follow_id;
		//ログインユーザーが対象のユーザーをフォローしているか？ 
		$isFollow = (boolean) Follow::where('user_id', Auth::user()->id)->where('follow_id', $follow_id)->first();
		if($isFollow){
			$unfollow = Follow::where('user_id', Auth::user()->id)->where('follow_id', $follow_id);
			$unfollow->delete();
		}else{
			$follow = new follow();
			$follow->user_id = Auth::user()->id;
			$follow->follow_id = $follow_id;
			$follow->save();
		}
		
		$followees_id = Follow::where('user_id', Auth::id())->pluck('follow_id'); // IDのリストを取得
		$followees = User::whereIn('id', $followees_id)->get(); // 取得したIDリストを使ってユーザーを取得
		$target_users = collect(); // 空のコレクションを初期化
	
		return view('followee')->with(['followees' => $followees, 'target_users' => $target_users]);
	}
	
	
	
	public function follower_index()
	{
		$followers_id = Follow::where('follow_id', Auth::id())->pluck('user_id'); // IDのリストを取得
		$followers = User::whereIn('id', $followers_id)->get(); // 取得したIDリストを使ってユーザーを取得
		$target_users = collect(); // 空のコレクションを初期化

		return view('follower')->with(['followers' => $followers, 'target_users' => $target_users]);
	}


	public function follower_search(Request $request)
	{
		// 検索キーワードを取得
		$search = $request->input('search');
		$target_users = User::where('name', 'like', '%' . $search . '%')->where('id', '!=', Auth::id())->get();
		
		$followers_id = Follow::where('follow_id', Auth::id())->pluck('user_id');
		$followers = User::whereIn('id', $followers_id)->get();

		return view('follower')->with(['target_users' => $target_users, 'followers' => $followers]);
	}

	public function follower_update(Request $request)
	{
		$follow_id = $request->follow_id;
		//ログインユーザーが対象のユーザーをフォローしているか？ 
		$isFollow = (boolean) Follow::where('user_id', Auth::user()->id)->where('follow_id', $follow_id)->first();
		if($isFollow){
			$unfollow = Follow::where('user_id', Auth::user()->id)->where('follow_id', $follow_id);
			$unfollow->delete();
		}else{
			$follow = new follow();
			$follow->user_id = Auth::user()->id;
			$follow->follow_id = $follow_id;
			$follow->save();
		}
		
		$followers_id = Follow::where('follow_id', Auth::id())->pluck('user_id'); // IDのリストを取得
		$followers = User::whereIn('id', $followers_id)->get(); // 取得したIDリストを使ってユーザーを取得
		$target_users = collect(); // 空のコレクションを初期化
	
		return view('follower')->with(['followers' => $followers, 'target_users' => $target_users]);
	}

}
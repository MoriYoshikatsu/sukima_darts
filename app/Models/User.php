<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function parameters() {
        return $this->hasMany(Trip::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }
    
    //user_idのユーザーが、フォローしているユーザー(ID:follow_id)を抽出
    public function follows() {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'follow_id');
    }
    
    //ログインユーザー(Auth::user())との関係性を返す
    //0:どちらもフォローしていない　1：ログインユーザーが相手をフォロー　2：相手がログインユーザーをフォロー　3：お互いにフォロー
    public function relation()
    {
        $id = $this->id;
    
        //ログインユーザーが対象ユーザーをフォローしているか？をtrue/falseで返す
        $follow = (boolean) Auth::user()->follows()->where('follow_id', $id)->first();
    
        //対象Userが自分をフォローしているか？をtrue/falseで返す
        $follower = (boolean) $this->follows()->where('follow_id', Auth::user()->id)->first();
    
        if(!($follow) && !($follower)){ //0:どちらもフォローしていない
            $result = 0;
        }elseif($follow && !($follower)){ //1：ログインユーザーが相手をフォロー
            $result = 1;
        }elseif(!($follow) && $follower){ //2：相手がログインユーザーをフォロー
            $result = 2;
        }else{ //3：お互いにフォロー
            $result = 3;
        }
    
        return $result;
    }
    
    //ログインユーザーは、対象ユーザーをフォローしているか？
    public function isFollow()
    {
        $id = $this->id;
        $isFollow = (boolean) Auth::user()->follows()->where('follow_id',$id)->first();
    
        return $isFollow;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    //このユーザーに関係するモデルの件数をロードする。
    public function loadrelationshipCounts()
    {
        $this->loadCount(["microposts", "followings", "followers", "favorites"]);
    }
    
    //このユーザーがフォロー中のユーザー（Userモデルとの関係を定義）
    public function followings()
    {
        return $this->belongsToMany(User::class, "user_follow", "user_id", "follow_id")->withTimestamps();
    }
    
    //このユーザーをフォロー中のユーザー（Userモデルとの関係を定義）
    public function followers()
    {
        return $this->belongsToMany(User::class, "user_follow", "follow_id", "user_id")->withTimestamps();
    }
    
    public function follow($userId)
    {
        //既にフォローしているかの確認
        $exist = $this->is_following($userId);
        //対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;
        
        if ($exist || $its_me) {
            //既にフォローしていれば何もしない
            return false;
        } else {
            //未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        //既にフォローされているかの確認
        $exist = $this->is_following($userId);
        //対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;
        
        if($exist && !$its_me) {
            //既にフォローしていればフォローを外す。
            $this->followings()->detach($userId);
            return true;
        } else {
            //未フォローであれば何もしない。
            return false;
        }
    }
    
    public function is_following($userId)
    {
        //フォロー中ユーザの中に$userIdのものが存在するか
        return $this->followings()->where("follow_id", $userId)->exists();
    }
    
    public function feed_microposts()
    {
        //このユーザーがフォロー中のユーザーのidを取得して配列にする。
        $userIds = $this->followings()->pluck("users.id")->toArray();
        //このユーザーのidもその配列に追加
        $userIds[] = $this->id;
        //それらのユーザーが所有する投稿に絞り込む
        return Micropost::whereIn("user_id", $userIds);
    }
    
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, "favorites", "user_id", "micropost_id")->withTimestamps();
    
    }
    
    public function favorite($micropostId)
    {
        //既にお気に入りしているかの確認
        $exist = $this->is_favorite($micropostId);
        
        if($exist) {
            //にお気に入りしていれば何もしない
            return false;
        } else {
            // お気に入りしていないならする
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    public function unfavorite($micropostId)
    {
        //既にお気に入りしているかの確認
        $exist = $this->is_favorite($micropostId);
        
        
        if($exist) {
            //既にお気に入りしていればそれを外す
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            //未フォローであれば何もしない
            return false;
        }
    }
    
    public function is_favorite($micropostId)
    {
        return $this->favorites()->where("micropost_id", $micropostId)->exists();
    }
    
}
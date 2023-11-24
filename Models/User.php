<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'department_id',
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

    // モデルにリレーション設定
    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    } 


    // 検索機能の実装
    public function scopeSearch($query,$search)
    {
    if($search !== null){
        $search_split = mb_convert_kana($search, 's');//全角スペースを半角
        $search_split2 = preg_split('/[\s]+/',$search_split);//空白で区切る
        foreach($search_split2 as $value) {
        $query->where('name','like','%'.$value.'%'); }
    }
    return $query;
    }

    


}

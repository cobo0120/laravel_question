<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Item;


class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'application_status', 
        'application_day', 
        'user_id', 
        'department_id', 
        'purchase', 
        'purchasing_url', 
        'purpose_of_use', 
        'delivery_hope_day', 
        'subtotal', 
        'tax_amount', 
        'total_amount', 
        'destination',
        'remarks', 
        'delivery_day',
    ];

    // protected $guarded = ['delivery_day'];
   

    public function scopeSearch($query,$search)
    {
    if($search !== null){
        $search_split = mb_convert_kana($search, 's');//全角スペースを半角
        $search_split2 = preg_split('/[\s]+/',$search_split);//空白で区切る
        foreach($search_split2 as $value) {
        $query->where('purchase','like','%'.$value.'%');
      }
    }
    return $query;

    


    }

    //子→親へのリレーション 
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    // 親（Post）と子(Item)のリレーション
    public function items(){
        // postsは複数のitemに関連つけられるので、メソッドはitemsとし、postsテーブルはitemsテーブルに対して主となるのでhasMany
        return $this->hasMany(Item::class);
    }

    

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'post_id',
        'consumable_equipment_id', 
        'product_name', 
        'unit_purchase_price', 
        'purchase_quantities', 
        'units', 
        'account_id',
    ];

    public function post()
    {
        // このクラス（Item）がPostクラスに一つずつ紐着くようにpostメソッドを作成した
        return $this->belongsTo(Post::class);
    }

    public function consumable_equipment(){

        return $this->belongsTo(Consumable::class);
    }

    public function account(){

        return $this->belongsTo(Account::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    use HasFactory;
    protected $table = "consumables_equipments";

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

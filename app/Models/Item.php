<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;
    protected $fillable = [
        'item_name',
        'description',
        'stock',
        'price',
        'image_url',
        'id_user',
        'total_price'
    ];
    protected $attributes = [
        'image_url' => 'default.png',
        'total_price' => 0
    ];
}

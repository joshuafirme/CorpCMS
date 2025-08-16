<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";
    public $timestamps = false;

    protected $fillable = [
        "product_name",
        "product_slug",
        "product_description",
        "product_price",
        "qty",
        "status",
        "product_img",
    ];
}

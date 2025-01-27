<?php

// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    // Explicitly define the table name
    protected $table = 'products';

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship with OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

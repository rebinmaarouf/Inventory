<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'products';

    // Relationship with Category (fixed class name)
    public function category()
    {
        return $this->belongsTo(category::class); // Ensure capital C for Category
    }

    // Relationship with Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship with OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship with SalesItems
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}

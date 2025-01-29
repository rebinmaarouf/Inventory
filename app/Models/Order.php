<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    // Explicitly define the table name
    protected $table = 'orders';
    // Relationship with Customer
    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }

    // Relationship with OrderItems
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

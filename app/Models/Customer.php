<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];
    // Explicitly define the table name
    protected $table = 'customers';
    // Relationship with Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

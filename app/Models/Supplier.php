<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    protected $guarded = [];
    // Supplier.php
    public function orders()
    {
        return $this->hasMany(Order::class); // assuming Supplier has many Orders
    }
}

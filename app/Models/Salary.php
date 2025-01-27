<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Define the relationship: A salary belongs to one employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

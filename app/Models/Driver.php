<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'longitude',
        'latitude',
        'balance',
        'rating',
        'status',
        'login',
        'password',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'driver_id');
    }
}

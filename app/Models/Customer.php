<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'preferences'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'preferences' => 'array'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

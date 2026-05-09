<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToBranch;

class Drink extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'branch_id',
        'name',
        'price',
        'image',
        'status',
    ];

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'drink_id');
    }
}

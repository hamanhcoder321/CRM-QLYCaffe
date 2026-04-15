<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'name' 
    ];

    public function users(){
        return $this->hasMany(User::class, 'part_id');
    }
    
}

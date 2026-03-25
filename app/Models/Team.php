<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'part_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'team_id'); 
    }

    public function parts(){
        return $this->belongsTo(Part::class, 'part_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_account extends Model
{
    protected $table = 'type_accounts';
    protected $fillable = [
        'name',
        'description' 
    ];

    public function users(){
        return $this->hasMany(User::class, 'type_accounts_id');
    }
}

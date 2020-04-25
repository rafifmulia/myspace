<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['password', 'remember_token'];

    public function spaces()
    {
        return $this->hasMany('Spaces', 'user_id', 'id');
    }
}

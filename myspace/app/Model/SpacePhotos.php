<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SpacePhotos extends Model
{
    protected $guarded = [];

    public function spaces()
    {
        return $this->belongsTo('Spaces', 'space_id', 'id');
    }
}

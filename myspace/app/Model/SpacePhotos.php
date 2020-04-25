<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SpacePhotos extends Model
{
    public function spaces()
    {
        return $this->belongsTo('Spaces', 'space_id', 'id');
    }
}

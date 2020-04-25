<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Spaces extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo('Users', 'user_id', 'id');
    }

    public function spacePhotos()
    {
        return $this->hasMany('SpacePhotos', 'space_id', 'id');
    }
    public function getNeighbord($lat, $lng, $rad)
    {
        // 3959 dalam satuan mil
        // 6371 dalam satuan km
        return $this->select('spaces.*')
        ->selectRaw(
            '(6371 *
                acos( cos( radians(?) ) *
                    cos( radians(lat) ) *
                    cos( radians(lng) - radians(?) ) +
                    sin( radians(?) ) *
                    sin( radians(lat) )
                )
            ) AS distance', [$lat, $lng, $lat]
        )
        ->havingRaw("distance < ?", [$rad])
        ->whereNotIn('lat', [$lat])
        ->whereNotIn('lng', [$lng])
        ->orderBy('distance', 'asc');
    }
}

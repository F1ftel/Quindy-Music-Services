<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'service_id',
        'package_id',
        'review',
        'track_link',
    ];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function getItemNameAttribute()
    {
        if ($this->service) {
            return $this->service->name;
        }

        if ($this->package) {
            return $this->package->name;
        }

        return 'Unknown';
    }
}

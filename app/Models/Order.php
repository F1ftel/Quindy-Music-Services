<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'service_id',
        'status',
        'google_drive_link',
    ];

    public $timestamps = false;

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class);
    }

    public function package()
    {
        return $this->belongsTo(\App\Models\Package::class);
    }
}

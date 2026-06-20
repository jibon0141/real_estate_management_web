<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'package_name',
        'package_no',
        'package_price',
        'return_amount',
        'return_time',
        'extra_benefit',
        'share_count',
        'allotted_share',
        'description',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    protected static function booted()
    {
        static::creating(function ($package) {
            $package->package_no = 'TEMP';
        });

        static::created(function ($package) {
            $package->package_no = 'PKG-' . (1000 + $package->id);
            $package->save();
        });
    }
}

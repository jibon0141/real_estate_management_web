<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_id',
        'project_id',
        'package_id',
        'package_price',
        'return_amount',
        'return_time',
        'extra_benefit',
        'share_count',
        'booking_money',
    ];

    public function sell(){
        return $this->belongsTo(Sell::class,'sell_id');
    }

    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }

    public function package(){
        return $this->belongsTo(Package::class,'package_id');
    }
}

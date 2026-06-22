<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_voucher',
        'sell_date',
        'sell_type',
        'sell_quantity',
        'total_share_sell',
        'total_amount',
        'paid_amount',
        'due_amount',
        'current_due_amount',
        'take_return',
        'user_id',
        'account_id',
        'installment_number',
        'installment_amount',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function sellInfo(){
        return $this->hasOne(SellInfo::class,'sell_id');
    }

    protected static function booted()
    {
        static::creating(function ($sell) {
            $sell->sell_voucher = 'TEMP';
        });

        static::created(function ($sell) {
            $sell->sell_voucher = 'SV-' . (100000 + $sell->id);
            $sell->save();
        });
    }

}

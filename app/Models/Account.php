<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_no',
        'account_name',
        'user_id',
        'depo_id',
        'opening_balance',
        'balance',
        'status',
        'is_default',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'balance' => 'decimal:2',
        'status' => 'boolean',
        'is_default' => 'boolean',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function debitVouchers(){
        return $this->hasMany(DebitVoucher::class,'account_id','id');
    }

    public function creditVouchers(){
        return $this->hasMany(CreditVoucher::class, 'account_id', 'id');
    }



}

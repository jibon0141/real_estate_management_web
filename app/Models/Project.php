<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'status',
        'total_share',
        'description',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Triggers extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'endpoint',
        'jobs'
    ];
    protected $casts = [
        'created_at' => 'date:d-m-Y',
        'updated_at' => 'date:d-m-Y'
    ];
}

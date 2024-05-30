<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListJob extends Model
{
    use HasFactory;
    protected $table = 'list_jobs';
    protected $fillable = [
        'job_name',
        'role_id',
    ];
    protected $casts = [
        'created_at' => 'date:d-m-Y',
        'updated_at' => 'date:d-m-Y',
    ];
}

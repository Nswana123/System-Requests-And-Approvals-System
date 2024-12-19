<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class assignment_tbl extends Model
{
    protected $table = 'assignment_tbl';
    protected $fillable = [
        'request_id',
        'assigned_user',
    ];
}

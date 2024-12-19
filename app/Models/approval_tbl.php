<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class approval_tbl extends Model
{
    //
    use HasFactory;
    protected $table = 'approval_tbl';
    protected $fillable = [
        'request_id',
        'approver_id',
        'status',
    ];
    public function user()
{
    return $this->belongsTo(User::class, 'approver_id');  // assuming 'approver_id' is the foreign key
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attachment extends Model
{
    use HasFactory;
    protected $table = 'attachments';
    protected $fillable = ['file_path','file_name', 'request_id'];

    public function request()
    {
        return $this->belongsTo(request_tbl::class, 'request_id');
    }
}

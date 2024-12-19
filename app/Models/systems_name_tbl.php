<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class systems_name_tbl extends Model
{
    //
    protected $table = 'systems_name_tbl';
    protected $fillable = [
   'systems_name',
   'request_type_id',
    ];
    public function systemsName()
    {
        return $this->belongsTo(systems_name_tbl::class, 'systems_name_id', 'id');
    }
    public function systemName()
    {
        return $this->belongsTo(request_type::class, 'request_type_id');
    }
}

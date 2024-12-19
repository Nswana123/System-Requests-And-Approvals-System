<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class request_type extends Model
{
    //
    use HasFactory;
    protected $table = 'request_type';
    protected $fillable = [
   'priority',
   'request_name',
   'ttr_in_hour',
    ];
    public function requests()
{
    return $this->hasMany(request_tbl::class, 'request_type_id');
}
public function systems_name_tbl()
{
    return $this->hasMany(systems_name_tbl::class, 'request_type_id');
}
public function systemName()
{
    return $this->belongsTo(request_type::class, 'request_type_id');
}
}

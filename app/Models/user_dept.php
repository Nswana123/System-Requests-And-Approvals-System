<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_dept extends Model
{
    use HasFactory;
    protected $table = 'user_dept';
    protected $fillable = [
        'id',
        'dept_name',
        'position',   
    ];
    public function role_permissions()
    {
        return $this->hasMany(role_permissions::class, 'user_dept_id');
    }
    public function user_dept()
{
    return $this->belongsTo(user_dept::class, 'dept_id', 'id');
}
    public function permissions()
    {
        return $this->belongsToMany(permissions::class, 'role_permissions', 'user_dept_id', 'permission_id');
    }
    public function user()
    {
        return $this->hasMany(User::class, 'dept_id', 'id'); // Assuming dept_id is the foreign key in users table
    }
    public function users()
{
    return $this->hasMany(User::class, 'dept_id');
}
    
}

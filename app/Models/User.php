<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
  protected $fillable = [
    'id',
        'fname',
        'lname',
        'email',
        'mobile',
        'status',
        'dept_id',
        'dep_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function user_dept() {
        
        return $this->belongsTo(user_dept::class, 'dept_id','id');
    }
    public function requests()
{
    return $this->hasMany(request_tbl::class, 'user_id');
}
public function department()
{
    return $this->belongsTo(user_dept::class, 'dept_id');
}
}

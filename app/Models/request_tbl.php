<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class request_tbl extends Model
{
    //
    use HasFactory;
    protected $table = 'request_tbl';
    protected $fillable = [
        'request_refference',
        'user_id',
        'request_type_id',
        'systems_name_id',
        'account_type',
        'fname',
        'lname',
        'email',
        'mobile',
        'department',
        'position',
        'description',
        'status',
        'access_state',
        'comment',
        'assigned_username',
        'assigned_rolw',
        'access_duration',
        'closed_by',
    ];
    public function requestType()
    {
        return $this->belongsTo(request_type::class, 'request_type_id');
    }
    public function systemName()
    {
        return $this->belongsTo(request_type::class, 'request_type_id');
    }
    public function systemsName()
    {
        return $this->belongsTo(systems_name_tbl::class, 'systems_name_id');
    }
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function attachments()
    {
        return $this->hasMany(attachment::class, 'request_id');
    }
    public function approvals()
    {
        return $this->hasMany(approval_tbl::class, 'request_id'); // assuming 'request_id' is the foreign key in approval_tbl
    }
    public function assignments()
{
    return $this->hasMany(assignment_tbl::class, 'request_id', 'id');
}
public function closedBy()
{
    return $this->belongsTo(User::class, 'closed_by'); // Adjust 'User' and 'closed_by' to match your setup
}
}

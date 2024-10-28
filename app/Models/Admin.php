<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    const SUPER_ADMIN = 1; // 超级管理员
    const ADMIN = 2; // 管理员

    protected $fillable = [
        'name',
        'contact_tel',
        'account',
        'password',
        'role_id',
        'position_status_id',
        'description',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    // 判断当前用户是否为超级管理员
    public function isSuperAdmin()
    {
        return $this->role_id == self::SUPER_ADMIN;
    }

    // 判断当前用户是否为管理员
    public function isAdmin()
    {
        return $this->role_id == self::ADMIN;
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function positionStatus()
    {
        return $this->belongsTo(PositionStatus::class, 'position_status_id');
    }
}

<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'menu_id',
        'description',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id', 'id');
    }
}

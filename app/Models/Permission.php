<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'role_id',
        'menu_id',
        'method_id'
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function menus()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function methods()
    {
        $methodIds = explode(',', $this->method_id);
        return Method::whereIn('id', $methodIds)->get();
    }
}

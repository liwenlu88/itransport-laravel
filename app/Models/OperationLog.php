<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account',
        'path',
        'method',
        'operation',
        'ip_address',
        'user_agent',
        'request_data',
        'response_data',
        'status_code',
        'original_data',
        'new_data'
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function admins()
    {
        return $this->belongsTo(Admin::class, 'account', 'account');
    }
}

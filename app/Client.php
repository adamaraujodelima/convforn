<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'id',
        'user_id',
        'name',
        'secret',
        'redirect',
        'personal_access_client',
        'password_client',
        'revoked',
        'created_at',
        'updated_at'
    ];
}

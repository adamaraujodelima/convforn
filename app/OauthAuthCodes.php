<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthAuthCodes extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'id',
        'user_id',
        'client_id',
        'scopes',
        'revoked',
        'expires_a',
    ];
}

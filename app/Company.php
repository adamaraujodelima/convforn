<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'name',
        'cnpj',
        'postcode',
        'address',
        'number',
        'neighborhood',
        'city',
        'state',
        'created_at',
        'updated_at',
        'user_id',
    ];
}

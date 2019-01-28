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

     /**
     * Get the user record associated with the company.
     */
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    /**
     * Get the Manufacturer for the blog post.
     */
    public function manufacturer()
    {
        return $this->hasMany('App\Manufacturer');
    }
}

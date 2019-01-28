<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'manufacturer';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [        
        'name',
        'email',
        'month_payment',
        'user_id',
        'company_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

     /**
     * Get the user record associated with the company.
     */
    public function company()
    {
        return $this->belongsTo('App\Company','company_id');
    }

     /**
     * Get the user record associated with the user.
     */
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}

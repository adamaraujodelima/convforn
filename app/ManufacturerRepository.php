<?php

namespace App;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

class ManufacturerRepository extends BaseRepository implements CacheableInterface {
    
    use CacheableRepository;
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return "App\\Manufacturer";
    }
}
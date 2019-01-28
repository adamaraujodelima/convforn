<?php

namespace App\Listeners;

use App\ManufacturerRepository;
use App\CompanyRepository;
use App\Events\UpdateEntities;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class RefreshCacheRedis
{
    protected $time = 60 * 60 * 24;
    /**
     * @var ManufacturerRepository
     */
    protected $repoManufacturer;
    
    /**
     * @var CompanyRepository
     */
    protected $repoCompany;

    public function __construct(ManufacturerRepository $repository, CompanyRepository $repoCompany){
        $this->repoManufacturer = $repository;
        $this->repoCompany = $repoCompany;
    }

    /**
     * Handle the event.
     *
     * @param  UpdateEntities  $event
     * @return void
     */
    public function handle(UpdateEntities $event)
    {
        switch (get_class($event->getEntity())) {
            case 'App\Manufacturer':
                $Manufacturers = $this->repoManufacturer->findByField('user_id',$event->getEntity()->user_id);
                Redis::setex('manufacturer.all',  $this->time, $Manufacturers);
                Redis::setex('manufacturer.entity.'.$event->getEntity()->id, $this->time, $event->getEntity());
            case 'App\Company':
                Redis::setex('company.entity.' . $event->getEntity()->user_id, $this->time, $event->getEntity());
                break;            
            default:
                # code...
                break;
        }
    }
}

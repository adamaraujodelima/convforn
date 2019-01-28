<?php

namespace App\Http\Controllers\Api\Manufacturer;

use App\User;
use App\ManufacturerRepository;
use App\CompanyRepository;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Controller;
use App\Events\UpdateEntities;

class InfoController extends Controller
{
    
    use RegistersUsers;

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

    protected function getError($e)
    {
        $jsonError = response()->json(['message' => $e->getMessage()]);
        return \Response::json($jsonError,500);
    }

    public function list(Request $request)
    {
        try {
            $repository = $this->repoManufacturer;
            $manufacturers = Cache::remember('manufacturer_all_' . $request->user()->id, 60, function() use ($request, $repository) {
                $manufacturers = $repository->findByField('company_id',$request->user()->company->id);
                return $manufacturers;
            });            
            if ($manufacturers) {
                $jsonResponse = response()->json(['status' => 'success', 'manufacturers' => $manufacturers]);
                return \Response::json($jsonResponse,200);
            }else{
                $jsonError = response()->json(['message' => "This Company doesn't has any Manufacturer registered"]);
                return \Response::json($jsonError,400);
            }
        } catch (\Exception $e) {
            return $this->getError($e);
        }
    }

    public function info(Request $request, int $id = 0)
    {
        try {
            
            $repository = $this->repoManufacturer;
            
            $manufacturer = Cache::remember('manufacturer_entity_' . $id, 1, function() use ($repository, $id) {
                return $repository->find($id);
            });

            if ($manufacturer) {
                $jsonResponse = response()->json(['status' => 'success', 'manufacturer' => $manufacturer]);
                return \Response::json($jsonResponse,200);
            }else{
                $jsonError = response()->json(['message' => "This Manufacturer doesn't exists"]);
                return \Response::json($jsonError,400);
            }            
        } catch (\Exception $e) {
            return $this->getError($e);
        }
    }    

}

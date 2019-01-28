<?php

namespace App\Http\Controllers\Api\Manufacturer;

use App\User;
use App\ManufacturerRepository;
use App\CompanyRepository;
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
            
            if($Manufacturers = Redis::get('manufacturer.all')){
                $jsonResponse = response()->json(['status' => 'success', 'manufacturers' => $Manufacturers]);
                return \Response::json($jsonResponse,200);
            }
            $Manufacturers = $this->repoManufacturer->findByField('user_id',$request->user()->id);
            if ($Manufacturers) {
                $jsonResponse = response()->json($Manufacturers);
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
            
            if($Manufacturer = Redis::get('manufacturer.entity.'.$id)){
                $jsonResponse = response()->json(['status' => 'success', 'manufacturer' => $Manufacturer]);
                return \Response::json($jsonResponse,200);
            }
            
            $Manufacturer = $this->repoManufacturer->find($id);

            if ($Manufacturer) {
                Redis::set('manufacturer.entity.'.$id, $Manufacturer);
                $jsonResponse = response()->json(['status' => 'success', 'manufacturer' => $Manufacturer]);
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

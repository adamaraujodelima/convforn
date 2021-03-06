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

class RemoveController extends Controller
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

    public function delete(Request $request, int $id = 0)
    {
        try {
            $manufacturer = $this->repoManufacturer->find($id);
            if ($manufacturer) {
                $manufacturer->delete();
                Cache::forget('manufacturer_entity_' . $id);
                
                $manufacturers = $repository->findByField('company_id',$request->user()->company->id);
                Cache::put('manufacturer_all_' . $request->user()->id, $manufacturers, 60);

                $jsonResponse = response()->json(['message' => 'The Manufacturer was removed']);
                return \Response::json($jsonResponse,200);
            }
        } catch (\Exception $e) {
            return $this->getError($e);
        }
    }    

}

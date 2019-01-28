<?php

namespace App\Http\Controllers\Api\Company;

use App\Company;
use App\CompanyRepository;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use App\Events\UpdateEntities;

class InfoController extends Controller
{
    
    /**
     * @var CompanyRepository
     */
    protected $CompanyRepository;

    public function __construct(CompanyRepository $CompanyRepository){
        $this->CompanyRepository = $CompanyRepository;
    }

    protected function getError($e)
    {
        $jsonError = response()->json(['message' => $e->getMessage()]);
        return \Response::json($jsonError,500);
    }

    public function index(Request $request)
    {
        try {
            
            $repository = $this->CompanyRepository;

            $Company = Cache::remember('company_entity_' . $request->user()->user_id, 60, function() use ($request, $repository) {                
                $Company = $repository->with('user')->findByField('user_id',$request->user()->id)->first();
                return $Company;
            });

            if ($Company) {                
                $jsonResponse = response()->json(['message' => 'success', 'company' => $Company]);    
                return \Response::json($jsonResponse,200);            
            }else{
                $jsonError = response()->json(['message' => "This user doesn't has a company"]);
                return \Response::json($jsonError,400);
            }

            $jsonResponse = response()->json($Company);
            return \Response::json($jsonResponse,200);

        } catch (\Exception $e) {
            return $this->getError($e);
        }
    }    

    public function totalMonthPayment(Request $request)
    {
        try {
            $totalPayments = $this->CompanyRepository->getTotalMonthPayment($request->user());
            $jsonResponse = response()->json(['message' => 'success', 'total' => $totalPayments]);
            return \Response::json($jsonResponse,200);
        } catch (\Exception $e) {
            return $this->getError($e);
        }
    }
}

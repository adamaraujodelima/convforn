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

class EditController extends Controller
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

    public function update(Request $request)
    {
        try {
            $Company = $this->CompanyRepository->with('user')->findByField('user_id',$request->user()->id)->first();
    
            if (!$Company) {
                $jsonError = response()->json(['message' => "This user doesn't has a company"]);
                return \Response::json($jsonError,400);
            }
            
            $data = request()->all();
            $data['id'] = $Company->id;
    
            $valid = $this->validator($data);
    
            if ($valid->fails()) {
                $jsonError = response()->json($valid->errors()->all());
                return \Response::json($jsonError,400);
            }
            
            foreach ($data as $field => $value) {
                $Company->{$field} = $value;
            }
    
            $Company->save();

            // // Dispatching Event
            // event(new UpdateEntities($Company));

            $hasCache = Cache::has('company_entity_' . $request->user()->user_id);
            if ($hasCache) {
                Cache::put('company_entity_' . $request->user()->user_id, $Company, 60);
            }else{
                Cache::add('company_entity_' . $request->user()->user_id, $Company, 60);
            }
    
            $jsonResponse = response()->json(['message' => 'success', 'company' => $Company]);    
            return \Response::json($jsonResponse,200);            
        } catch (\Exception $e) {
            return $this->getError($e);
        }

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => ($data['id']) ? ['required', 'string', 'max:20', 'unique:company,cnpj,'.$data['id']] : ['required', 'string', 'max:20', 'unique:company'],
            'postcode' => ['required', 'string', 'max:20'],            
            'address' => ['required', 'string', 'max:20'],            
            'number' => ['required', 'string', 'max:20'],            
            'neighborhood' => ['required', 'string', 'max:20'],            
            'city' => ['required', 'string', 'max:20'],            
            'state' => ['required', 'string', 'max:20'],            
        ]);
    }
}

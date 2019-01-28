<?php

namespace App\Http\Controllers\Api\Company;

use App\Company;
use App\CompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use App\Events\UpdateEntities;

class RegisterController extends Controller
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

    public function create(Request $request)
    {
        try {
            $data = request()->all();
            $data['id'] = null;
            
            $valid = $this->validator($data);
    
            if ($valid->fails()) {
                $jsonError = response()->json($valid->errors()->all());
                return \Response::json($jsonError,400);
            }
    
            $Company = $this->CompanyRepository->with('user')->findByField('user_id',$request->user()->id)->first();
            if($Company){
                $jsonError = response()->json(['message' => 'This user already has a company.']);
                return \Response::json($jsonError,400);
            }
            
            $Company = Company::create([
                'name' => $data['name'],
                'cnpj' => $data['cnpj'],
                'postcode' => $data['postcode'],
                'address' => $data['postcode'],
                'number' => $data['number'],
                'neighborhood' => $data['neighborhood'],
                'city' => $data['city'],
                'state' => $data['state'],
                'user_id' => $request->user()->id,
            ]);

            // Dispatching Event
            event(new UpdateEntities($Company));
    
            $jsonResponse = response()->json(['status' => 'success', 'company' => $Company]);    
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

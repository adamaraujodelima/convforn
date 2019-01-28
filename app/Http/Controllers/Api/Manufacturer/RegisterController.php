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
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\Events\UpdateEntities;

class RegisterController extends Controller
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

    public function create(Request $request)
    {
        try {            

            $Company = $this->repoCompany->findByField('user_id',$request->user()->id)->first();
            if(!$Company){
                $jsonError = response()->json(['message' => "This user doesn't has a company."]);
                return \Response::json($jsonError,400);
            }

            $data = request()->all();
            $data['id'] = null;
            
            $valid = $this->validator($data);
            
            if ($valid->fails()) {
                $jsonError = response()->json($valid->errors()->all());
                return \Response::json($jsonError,400);
            }       

            $user = $this->createUser($data);

            event(new Registered($user));
            
            $Manufacturer = $this->repoManufacturer->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'month_payment' => $data['month_payment'],            
                'user_id' => $user->id,
                'company_id' => $request->user()->company->id,
            ]);
            
            // Dispatching Event
            event(new UpdateEntities($Manufacturer));
                
            $jsonResponse = response()->json(['status' => 'success', 'message' => 'An e-mail verification to confirm this new register was sended to e-mail informed. Please confirm first before use this Manufacturer.', 'manufacturer' => $Manufacturer]);
    
            return \Response::json($jsonResponse,200);
        } catch (\Exception $e) {
            return $this->getError($e);
        }
    }     

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make(uniqid()),
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:manufacturer'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'month_payment' => [
                'required',
                'max:10', 
                function ($attribute, $value, $fail) {
                    if (!is_float((float)$value)) {
                        $fail($value.' is invalid to field '.$attribute.'. Please inform a float value with decimal separator like as Ex.: 10.00');
                    }
                },
            ],
        ]);
    } 

}

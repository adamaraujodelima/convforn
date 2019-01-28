<?php

namespace App\Http\Controllers\Api\Manufacturer;

use App\User;
use App\ManufacturerRepository;
use App\CompanyRepository;
use App\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Controller;
use App\Events\UpdateEntities;

class EditController extends Controller
{
    
    use RegistersUsers;

    /**
     * @var UserRepository
     */
    protected $repoManufacturer;

    /**
     * @var ManufacturerRepository
     */
    protected $repoUser;
    
    /**
     * @var CompanyRepository
     */
    protected $repoCompany;

    public function __construct(ManufacturerRepository $repository, CompanyRepository $repoCompany, UserRepository $repoUser){
        $this->repoManufacturer = $repository;
        $this->repoCompany = $repoCompany;
        $this->repoUser = $repoUser;
    }

    protected function getError($e)
    {
        $jsonError = response()->json(['message' => $e->getMessage()]);
        return \Response::json($jsonError,500);
    }

    public function update(Request $request, int $id = 0)
    {
        
        try {            
            if(!$id){
                $jsonError = response()->json(['message' => 'The field ID was not informed']);
                return \Response::json($jsonError,500);
            }
    
            $data = request()->all();
            $data['id'] = $id;
    
            $Manufacturer = $this->repoManufacturer->find($id);

            if (!$Manufacturer->user->email_verified_at) {
                $jsonError = response()->json(['message' => 'Email verification is pending. Please confirm the register of Manufacturer on email verification link']);
                return \Response::json($jsonError,400);
            }
    
            $valid = $this->validator($data);
    
            if ($valid->fails()) {
                $jsonError = response()->json($valid->errors()->all());
                return \Response::json($jsonError,400);
            }

            $User = $this->updateUser($data, $Manufacturer);
            
            foreach ($data as $field => $value) {
                $Manufacturer->{$field} = $value;
            }
    
            $Manufacturer->save();

            // Dispatching Event
            event(new UpdateEntities($Manufacturer));
    
            $jsonResponse = response()->json(['message' => 'success', 'manufacturer' => $Manufacturer]);
    
            return \Response::json($jsonResponse,200);
        } catch (\Exception $e) {
            return $this->getError($e);
        }

    }

    protected function updateUser(array $data, $Manufacturer)
    {
        $User = $this->repoUser->find($Manufacturer->user->id);
        $User->name = $data['name'];
        $User->email = $data['email'];
        $User->save();

        return $User;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:manufacturer,email,' . $data['id']],
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $data['id']],
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

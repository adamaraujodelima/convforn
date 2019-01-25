<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class UserController extends Controller
{
    use RegistersUsers;


    public function create(Request $request)
    {   
        
        $data = request()->only('email','name','password');
        
        $valid = $this->validator($data);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);
            return \Response::json($jsonError);
        }
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),            
        ]);

        $token = $this->addClient($data);
        
        return \Route::dispatch($token);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    private function addClient($params = array())
    {
        // And created user until here.

        $client = Client::where('password_client', 1)->first();

        // Is this $request the same request? I mean Request $request? Then wouldn't it mess the other $request stuff? Also how did you pass it on the $request in $proxy? Wouldn't Request::create() just create a new thing?

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $params['email'],
            'password'      => $params['password'],
            'scope'         => null,
        ]);

        // Fire off the internal request. 
        $token = Request::create(
            'oauth/token',
            'POST'
        );

        return $token;
    }

    
}
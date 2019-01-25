<?php

namespace App\Http\Controllers\Api;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function list(Request $request)
    {
        $Companies = Company::where('user_id', $request->user()->id)->get();
        $jsonResponse = response()->json($Companies);
        return \Response::json($jsonResponse,200);
    }

    public function info(Request $request, int $id = 0)
    {
        $Company = Company::find($id);
        $jsonResponse = response()->json($Company);
        return \Response::json($jsonResponse,200);
    }

    public function create(Request $request)
    {
        $data = request()->all();
        
        $valid = $this->validator($data);

        if ($valid->fails()) {
            $jsonError = response()->json($valid->errors()->all());
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

        $jsonResponse = response()->json(['status' => 'success', 'company' => $Company]);

        return \Response::json($jsonResponse,200);
    }

    public function update(Request $request, int $id = 0)
    {
        
        if(!$id){
            $jsonError = response()->json(['message' => 'The field ID was not informed']);
            return \Response::json($jsonError,500);
        }

        $data = request()->all();
        $data['id'] = $id;

        $Company = Company::find($id);

        if (!$Company) {
            $jsonError = response()->json(['message' => 'This company does not exist']);
            return \Response::json($jsonError,400);
        }

        $valid = $this->validator($data);

        if ($valid->fails()) {
            $jsonError = response()->json($valid->errors()->all());
            return \Response::json($jsonError,400);
        }
        
        foreach ($data as $field => $value) {
            $Company->{$field} = $value;
        }

        $Company->save();

        $jsonResponse = response()->json(['message' => 'success', 'company' => $Company]);

        return \Response::json($jsonResponse,200);

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => (isset($data['id'])) ? ['required', 'string', 'max:20', 'unique:company,cnpj,'.$data['id']] : ['required', 'string', 'max:20', 'unique:company'],
            'postcode' => ['required', 'string', 'max:20'],            
            'address' => ['required', 'string', 'max:20'],            
            'number' => ['required', 'string', 'max:20'],            
            'neighborhood' => ['required', 'string', 'max:20'],            
            'city' => ['required', 'string', 'max:20'],            
            'state' => ['required', 'string', 'max:20'],            
        ]);
    }
}

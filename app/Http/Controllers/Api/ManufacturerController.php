<?php

namespace App\Http\Controllers\Api;

use App\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class ManufacturerController extends Controller
{
    public function list(Request $request)
    {
        $Manufacturers = Manufacturer::where('user_id', $request->user()->id)->get();
        $jsonResponse = response()->json($Manufacturers);
        return \Response::json($jsonResponse,200);
    }

    public function info(Request $request, int $id = 0)
    {
        $Manufacturer = Manufacturer::find($id);
        $jsonResponse = response()->json($Manufacturer);
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
        
        $Manufacturer = Manufacturer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'month_payment' => $data['month_payment'],            
            'user_id' => $request->user()->id,
        ]);

        $jsonResponse = response()->json(['status' => 'success', 'Manufacturer' => $Manufacturer]);

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

        $Manufacturer = Manufacturer::find($id);

        if (!$Manufacturer) {
            $jsonError = response()->json(['message' => 'This Manufacturer does not exist']);
            return \Response::json($jsonError,400);
        }

        $valid = $this->validator($data);

        if ($valid->fails()) {
            $jsonError = response()->json($valid->errors()->all());
            return \Response::json($jsonError,400);
        }
        
        foreach ($data as $field => $value) {
            $Manufacturer->{$field} = $value;
        }

        $Manufacturer->save();

        $jsonResponse = response()->json(['message' => 'success', 'Manufacturer' => $Manufacturer]);

        return \Response::json($jsonResponse,200);

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:manufacturer'],
            'month_payment' => ['required', 'string', 'max:10'],
        ]);
    }
}

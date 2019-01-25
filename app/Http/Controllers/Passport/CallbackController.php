<?php

namespace App\Http\Controllers\Passport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CallbackController extends Controller
{
    public function index(Request $request)
    {
        return view('authorized')->with([
            'response' => null,
            'authCode' => $request->code
        ]);
    }   
    
    public function authorizationCode(Request $request)
    {
        $query = http_build_query([
            'client_id' => $request->client_id,
            'redirect_uri' => $request->redirect_uri,
            'response_type' => 'code',
            'scope' => '*',
        ]);

        return redirect('http://localhost/oauth/authorize?'.$query);
    }

    public function getToken(Request $request)
    {
        $response = [];

        if ($request->auth_code && $request->client_id && $request->client_secret) {
            $http = new \GuzzleHttp\Client;
            $response = $http->post('http://'. $_SERVER['SERVER_ADDR'] . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $request->client_id,        
                    'client_secret' => $request->client_secret,
                    'redirect_uri' => 'http://localhost/client/callback',
                    'code' => $request->auth_code,
                ],
            ]);
            
            $response = json_decode((string) $response->getBody(), true);
        }

        return view('clientToken')->with([
            'response' => $response,
            'authCode' => null
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function list(Request $request)
    {
        echo __FUNCTION__;
        exit;
    }

    public function get(Request $request, int $id = 0)
    {
        dump($id);
        exit;
    }

    public function add(Request $request)
    {
        echo __FUNCTION__;
        exit;
    }

    public function update(Request $request, int $id = 0)
    {        
        dump($id);
        exit;
    }
}

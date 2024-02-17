<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request) : JsonResponse {
        $data = ['hello world'];
        return new JsonResponse($data);
    }

    public function show(){}

    public function store(){}

    public function update(){}

    public function destroy(){}
}

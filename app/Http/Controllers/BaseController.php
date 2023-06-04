<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function redirectOnError($error, $route, $params = [])
    {
        return redirect()->route($route, $params)->withErrors($error);
    }
}

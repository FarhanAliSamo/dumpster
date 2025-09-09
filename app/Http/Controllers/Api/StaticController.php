<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Addon;
use App\Models\Container;

class StaticController extends Controller
{
    public function Addons()
    {
        $data = Addon::all();
        return response()->json($data);
    }
    public function Containers()
    {
        $data = Container::all();
        return response()->json($data);
    }
}

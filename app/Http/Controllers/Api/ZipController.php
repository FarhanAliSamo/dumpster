<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZipCode;
use Illuminate\Http\Request;

class ZipController extends Controller
{
    public function validateZip(Request $request)
    {
        $request->validate([
            'zip' => 'required|max:10',
        ]);
        $zip = $request->input('zip');
        $data  = ZipCode::where('zip', $zip)->first();
        // $data  = ZipCode::with('county')->where('zip', $zip)->first();
        if ($data) {
            return response()->json(['valid' => true, 'message' => 'Valid ZIP code.', 'data' => $data]);
        } else {
            return response()->json(['valid' => false, 'message' => 'Invalid ZIP code.']);
        }
    }
}   

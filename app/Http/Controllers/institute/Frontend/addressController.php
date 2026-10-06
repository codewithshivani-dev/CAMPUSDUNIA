<?php

namespace App\Http\Controllers\institute\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect; 

class addressController extends Controller
{

            public function getStatesByCountry(Request $request)
        {
            // $country = $request->input('country');
            $country = 'India';
            if (!$country) {
                return response()->json([
                    'success' => false,
                    'message' => 'Country is required'
                ], 400);
            }
            try {
                $response = Http::post("https://countriesnow.space/api/v0.1/countries/states", [
                    'country' => $country
                ]);
                $data = $response->json();
                if (isset($data['data']['states'])) {
                    return response()->json([
                    'success' => true,
                    'data' => [
                        'states' => $data['data']['states']
                    ]
                ]);

                } else {
                    return response()->json([
                        'success' => false,
                        'states' => [],
                        'message' => 'No states found for this country'
                    ]);
                }
            } catch (\Exception $e) {
               return response()->json([
                'success' => true,
                'data' => [
                    'states' => $data['data']['states']
                ]
            ]);

            }
        }
      public function getCitiesByState(Request $request)
{
    $country = $request->input('country', 'India');
    $state = $request->input('state');

    if (!$country || !$state) {
        return response()->json([
            'success' => false,
            'message' => 'Country and state are required',
            'data' => ['city' => []]
        ], 400);
    }

    try {
        $response = Http::post('https://countriesnow.space/api/v0.1/countries/state/cities', [
            'country' => $country,
            'state' => $state,
        ]);

        $data = $response->json();

        return response()->json([
            'success' => true,
            'data' => [
                'city' => $data['data'] ?? []
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

}        
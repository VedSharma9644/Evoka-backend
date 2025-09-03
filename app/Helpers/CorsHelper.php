<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class CorsHelper
{
    /**
     * Add CORS headers to a response
     */
    public static function addCorsHeaders($response)
    {
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Expose-Headers', 'Content-Length, X-JSON');
        
        return $response;
    }

    /**
     * Create a CORS-enabled JSON response
     */
    public static function corsJson($data, $status = 200)
    {
        $response = response()->json($data, $status);
        return self::addCorsHeaders($response);
    }

    /**
     * Handle preflight OPTIONS request
     */
    public static function handlePreflight()
    {
        return response('', 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Max-Age', '86400');
    }
}

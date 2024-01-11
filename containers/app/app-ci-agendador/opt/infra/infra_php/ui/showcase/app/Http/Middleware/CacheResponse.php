<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silber\PageCache\Middleware\CacheResponse as BaseCacheResponse;

class CacheResponse extends BaseCacheResponse
{   
    protected function shouldCache(Request $request, Response $response) {
       
        if (!env('PRODUCTION')) {
            return false;        
        }

        return $request->isMethod('GET') && $response->getStatusCode() == 200;
    }

}
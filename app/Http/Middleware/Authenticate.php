<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
   protected function authenticate($request, array $guards)
   {
       if($request->bearerToken() === config('apitoken')) return;
       $this->unauthenticated($request, $guards);
   }
}

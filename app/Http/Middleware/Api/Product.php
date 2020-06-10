<?php

namespace App\Http\Middleware\Api;

use Closure;
use App\Http\Controllers\Controller;

class Product extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try{
            if(isset($request->key)){
                if($request->key == md5('switch_product_key')){
                    return $next($request);
                }else{
                    return $this->convertJson(500,'fali','key was invalid!');
                }
            }else{
                return $this->convertJson(500,'fali','key was not found!');
            }
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }
}

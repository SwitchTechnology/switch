<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Order;
use Validator;
use Illuminate\Support\Str;
use App\Model\Product;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 8d140db8d5665054ad40dc4abd7d110d
        try{
            $order = Order::latest('id')->get();
            return $this->convertJson(200,'success',["order" => $order]);
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $product = Product::all();
            return $this->convertJson(200,'success',["product" => $product]);
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $json = json_encode($request->all());
            $data = json_decode($json, true);
            $rule = [
                "product_id" => "required",
                "customer_name" => "required",
                "phone" => "required",
                "count" => "required",
                "fulladdress" => "required",
                "description" => "required",
                "user_id" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug('switch-order','-').'-'.uniqid();
                    $sql = new Order;
                    $sql->product_id = $request->product_id;
                    $sql->customer_name = $request->customer_name;                    
                    $sql->phone = $request->phone;                    
                    $sql->count = $request->count;                    
                    $sql->fulladdress = $request->fulladdress;                    
                    $sql->description = $request->description;                    
                    $sql->user_id = $request->user_id;                    
                    $sql->slug = $slug;                    
                    $sql->save();

                    return $this->index();
                }catch(\Exception $e){
                    return $this->convertJson(500,'fail',$e->getMessage());    
                }
            }else{
                return $this->convertJson(500,'fail',$validator->errors()->all());
            }
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $order = Order::where('slug',$id)->first();
            $product = Product::all();
            return $this->convertJson(200,'success',["order" => $order,"product" => $product]);
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $json = json_encode($request->all());
            $data = json_decode($json, true);
            $rule = [
                "product_id" => "required",
                "customer_name" => "required",
                "phone" => "required",
                "count" => "required",
                "fulladdress" => "required",
                "description" => "required",
                "user_id" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug('switch-order','-').'-'.uniqid();
                    $sql = Order::where('slug',$id)->first();
                    $sql->product_id = $request->product_id;
                    $sql->customer_name = $request->customer_name;                    
                    $sql->phone = $request->phone;                    
                    $sql->count = $request->count;                    
                    $sql->fulladdress = $request->fulladdress;                    
                    $sql->description = $request->description;                    
                    $sql->user_id = $request->user_id;                    
                    $sql->slug = $slug;     
                    $sql->save();

                    return $this->index();
                }catch(\Exception $e){
                    return $this->convertJson(500,'fail',$e->getMessage());    
                }
            }else{
                return $this->convertJson(500,'fail',$validator->errors()->all());
            }
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Order::where('slug',$id)->delete();
            return $this->index();
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }
}

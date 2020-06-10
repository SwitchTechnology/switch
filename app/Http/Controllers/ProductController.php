<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Product;
use Validator;
use Illuminate\Support\Str;
use App\Model\Website;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $product = Product::latest('id')->get();
            return $this->convertJson(200,'success',["product" => $product]);
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
            $website = Website::all();
            return $this->convertJson(200,'success',["website" => $website]);
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
                "website_id" => "required",
                "name" => "required",
                "description" => "required",
                "total_count" => "required",
                "amount" => "required",
                "discount" => "required",
                "price_per_product" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug($request->name,'-').'-'.uniqid();

                    if($request->hasFile('image')){
                        $imageName = 'switch-'.uniqid().'.'.$request->image->extension();  
                        $request->image->move(public_path('uploads/'), $imageName);
                    }else{
                        return $this->convertJson(500,'fail','image field is required!');
                    }

                    $sql = new Product;
                    $sql->website_id = $request->website_id;
                    $sql->image = $imageName;                    
                    $sql->name = $request->name;                    
                    $sql->description = $request->description;                    
                    $sql->total_count = $request->total_count;                    
                    $sql->amount = $request->amount;                    
                    $sql->discount = $request->discount;                    
                    $sql->price_per_product = $request->price_per_product;                    
                    $sql->slug = $slug;                    
                    $sql->save();

                    return $this->index();
                }catch(\Exception $e){
                    return $this->convertJson(500,'fail',$e->getMessage()->all());    
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
            $product = Product::where('slug',$id)->first();
            $website = Website::all();
            return $this->convertJson(200,'success',["product" => $product,"website" => $website]);
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
                "website_id" => "required",
                "name" => "required",
                "description" => "required",
                "total_count" => "required",
                "amount" => "required",
                "discount" => "required",
                "price_per_product" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug($request->name,'-').'-'.uniqid();

                    if(isset($request->image)){
                        if($request->hasFile('image')){

                            $old = Product::where('slug',$id)->select('image')->first();
                            unlink(public_path('uploads/').$old->image);

                            $imageName = 'switch-'.uniqid().'.'.$request->image->extension();  
                            $request->image->move(public_path('uploads/'), $imageName);
    
                            $sql = Product::where('slug',$id)->first();
                            $sql->website_id = $request->website_id;
                            $sql->image = $imageName;                    
                            $sql->name = $request->name;                    
                            $sql->description = $request->description;                    
                            $sql->total_count = $request->total_count;                    
                            $sql->amount = $request->amount;                    
                            $sql->discount = $request->discount;                    
                            $sql->price_per_product = $request->price_per_product;                       
                            $sql->slug = $slug;
                            $sql->save();
    
                            return $this->index();
    
                        }
                    }else{
                        $sql = Product::where('slug',$id)->first();
                        $sql->website_id = $request->website_id;
                        $sql->name = $request->name;                    
                        $sql->description = $request->description;                    
                        $sql->total_count = $request->total_count;                    
                        $sql->amount = $request->amount;                    
                        $sql->discount = $request->discount;                    
                        $sql->price_per_product = $request->price_per_product;                           
                        $sql->slug = $slug;
                        $sql->save();

                        return $this->index();
                    }

                    
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
            $old = Product::where('slug',$id)->select('image')->first();
            unlink(public_path('uploads/').$old->image);

            Product::where('slug',$id)->delete();
            return $this->index();
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }
}

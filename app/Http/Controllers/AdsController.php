<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Ads;
use Validator;
use Illuminate\Support\Str;
use App\Model\AdsType;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 90097e83ded6f68298c06ee06d939de4
        try{
            $ads = Ads::latest('id')->get();
            return $this->convertJson(200,'success',["ads" => $ads]);
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
            $ads_type = AdsType::all();
            return $this->convertJson(200,'success',["ads_type" => $ads_type]);
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
                "ads_type_id" => "required",
                "description" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug('switch-ads','-').'-'.uniqid();

                    if($request->hasFile('image')){
                        $imageName = 'switch-'.uniqid().'.'.$request->image->extension();  
                        $request->image->move(public_path('uploads/'), $imageName);
                    }else{
                        return $this->convertJson(500,'fail','image field is required!');
                    }

                    $sql = new Ads;
                    $sql->ads_type_id = $request->ads_type_id;
                    $sql->image = $imageName;                    
                    $sql->description = $request->description;                    
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
            $ads = Ads::where('slug',$id)->first();
            $ads_type = AdsType::all();
            return $this->convertJson(200,'success',["ads_type" => $ads_type,"ads" => $ads]);
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
                "ads_type_id" => "required",
                "description" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug('switch-ads','-').'-'.uniqid();

                    if(isset($request->image)){
                        if($request->hasFile('image')){

                            $old = Ads::where('slug',$id)->select('image')->first();
                            unlink(public_path('uploads/').$old->image);

                            $imageName = 'switch-'.uniqid().'.'.$request->image->extension();  
                            $request->image->move(public_path('uploads/'), $imageName);
    
                            $sql = Ads::where('slug',$id)->first();
                            $sql->ads_type_id = $request->ads_type_id;
                            $sql->image = $imageName;                    
                            $sql->description = $request->description;                    
                            $sql->slug = $slug;
                            $sql->save();
    
                            return $this->index();
    
                        }
                    }else{
                        $sql = Ads::where('slug',$id)->first();
                        $sql->ads_type_id = $request->ads_type_id;
                        $sql->description = $request->description;                    
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
            $old = Ads::where('slug',$id)->select('image')->first();
            unlink(public_path('uploads/').$old->image);

            Ads::where('slug',$id)->delete();
            return $this->index();
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }
}

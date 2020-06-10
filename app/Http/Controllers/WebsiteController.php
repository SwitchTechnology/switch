<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Website;
use Validator;
use Socialite;
use Illuminate\Support\Str;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 289e870318cc0126f46b3ef7101b74f9
        try{
            $website = Website::latest('id')->get();
            return $this->convertJson(200,'success',["website" => $website]);
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
        //
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
                "name" => "required",
                "user_id" => "required",
                "description" => "required",
                "about" => "required",
                "contact" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug($request->name,'-').'-'.uniqid();

                    if($request->hasFile('banner_images')){
                        $imageName = 'switch-'.uniqid().'.'.$request->banner_images->extension();  
                        $request->banner_images->move(public_path('uploads/'), $imageName);
                    }else{
                        return $this->convertJson(500,'fail','banner_images field is required!');
                    }

                    $sql = new Website;
                    $sql->name = $request->name;
                    $sql->user_id = $request->user_id;
                    $sql->banner_images = $imageName;
                    $sql->description = $request->description;
                    $sql->about = $request->about;
                    $sql->contact = $request->contact;
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
            $website = Website::where('slug',$id)->first();
            return $this->convertJson(200,'success',["website" => $website]);
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
                "name" => "required",
                "user_id" => "required",
                "description" => "required",
                "about" => "required",
                "contact" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    
                    $slug = Str::slug($request->name,'-').'-'.uniqid();

                    if(isset($request->banner_images)){
                        if($request->hasFile('banner_images')){

                            $old = Website::where('slug',$id)->select('banner_images')->first();
                            unlink(public_path('uploads/').$old->banner_images);

                            $imageName = 'switch-'.uniqid().'.'.$request->banner_images->extension();  
                            $request->banner_images->move(public_path('uploads/'), $imageName);
    
                            $sql = Website::where('slug',$id)->first();
                            $sql->name = $request->name;
                            $sql->user_id = $request->user_id;
                            $sql->banner_images = $imageName;
                            $sql->description = $request->description;
                            $sql->about = $request->about;
                            $sql->contact = $request->contact;
                            $sql->slug = $slug;    
                            $sql->save();

                            return $this->index();
    
                        }
                    }else{
                        $sql = Website::where('slug',$id)->first();
                        $sql->name = $request->name;
                        $sql->user_id = $request->user_id;
                        $sql->banner_images = $imageName;
                        $sql->description = $request->description;
                        $sql->about = $request->about;
                        $sql->contact = $request->contact;
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
            Website::where('slug',$id)->delete();
            return $this->index();
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }
}

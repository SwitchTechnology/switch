<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Announcement;
use Validator;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // b8455ff8f60d5553dc972e36431d60a1
        try{
            $announcement = Announcement::latest('id')->get();
            return $this->convertJson(200,'success',["announcement" => $announcement]);
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
                "message" => "required",
                "user_id" => "required",
                "is_all" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug('switch-announcement','-').'-'.uniqid();

                    if($request->hasFile('image')){
                        $imageName = 'switch-'.uniqid().'.'.$request->image->extension();  
                        $request->image->move(public_path('uploads/'), $imageName);
                    }else{
                        return $this->convertJson(500,'fail','image field is required!');
                    }

                    $sql = new Announcement;
                    $sql->image = $imageName;                    
                    $sql->message = $request->message;
                    $sql->user_id = $request->user_id;                    
                    $sql->is_all = $request->is_all;                    
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
            $announcement = Announcement::where('slug',$id)->first();
            return $this->convertJson(200,'success',["announcement" => $announcement]);
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
                "message" => "required",
                "user_id" => "required",
                "is_all" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug('switch-announcement','-').'-'.uniqid();

                    if(isset($request->image)){
                        if($request->hasFile('image')){

                            $old = Announcement::where('slug',$id)->select('image')->first();
                            unlink(public_path('uploads/').$old->image);

                            $imageName = 'switch-'.uniqid().'.'.$request->image->extension();  
                            $request->image->move(public_path('uploads/'), $imageName);
    
                            $sql = Announcement::where('slug',$id)->first();
                            $sql->image = $imageName;                    
                            $sql->message = $request->message;
                            $sql->user_id = $request->user_id;                    
                            $sql->is_all = $request->is_all;                    
                            $sql->slug = $slug;
                            $sql->save();
    
                            return $this->index();
    
                        }
                    }else{
                        $sql = Announcement::where('slug',$id)->first();
                        $sql->message = $request->message;
                        $sql->user_id = $request->user_id;                    
                        $sql->is_all = $request->is_all;                    
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
            $old = Announcement::where('slug',$id)->select('image')->first();
            unlink(public_path('uploads/').$old->image);

            Announcement::where('slug',$id)->delete();
            return $this->index();
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }
}

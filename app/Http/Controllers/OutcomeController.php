<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Outcome;
use Validator;
use App\Model\Website;
use Illuminate\Support\Str;

class OutcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 5057cb7603ed9f151e4020edd7a16b7f
        try{
            $outcome = Outcome::latest('id')->get();
            return $this->convertJson(200,'success',["outcome" => $outcome]);
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
                "user_id" => "required",
                "amount" => "required",
                "description" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug('switch-outcome','-').'-'.uniqid();
                    $sql = new Outcome;
                    $sql->website_id = $request->website_id;                    
                    $sql->user_id = $request->user_id;
                    $sql->amount = $request->amount;
                    $sql->description = $request->description;
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
            $outcome = Outcome::where('slug',$id)->first();
            $website = Website::all();
            return $this->convertJson(200,'success',["outcome" => $outcome,"website" => $website]);
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
                "user_id" => "required",
                "amount" => "required",
                "description" => "required",
            ];

            $validator = Validator::make($data,$rule);
            if($validator->passes()){
                try{
                    $slug = Str::slug('switch-outcome','-').'-'.uniqid();
                    $sql = Outcome::where('slug',$id)->first();
                    $sql->website_id = $request->website_id;                    
                    $sql->user_id = $request->user_id;
                    $sql->amount = $request->amount;
                    $sql->description = $request->description;
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
            Outcome::where('slug',$id)->delete();
            return $this->index();
        }catch(\Exception $e){
            return $this->convertJson(500,'fail',$e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;

class LoginController extends Controller
{
    public function login($token,$user_name){
        return $this->convertJson(200,'success',["token" => $token, "name" => $user_name]);
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->user();
        
        $user_name = $user->getName();
        $user_email = $user->getEmail();
        $user_token = $user->token;
        $user_password = "password";
        
        $sql = new User;
        $sql->name = $user_name;
        $sql->email = $user_email;
        $sql->password = $user_password;
        $sql->save();

        $token = $sql->createToken('Switch')->accessToken;
        $this->login($token,$user_name);
        // $user->token;
    }

}

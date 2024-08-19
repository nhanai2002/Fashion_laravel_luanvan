<?php

namespace App\Http\Controllers;
use Exception;
use FashionCore\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use FashionCore\Interfaces\IUserRepository;

class LoginGoogleController extends Controller
{
    protected $userRepo;
    public function __construct(IUserRepository $userRepo){
        $this->userRepo = $userRepo;
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {        
            $user = Socialite::driver('google')->user();
         
            $finduser = User::where('google_id', $user->id)->first();
         
            if($finduser){
         
                Auth::login($finduser);
        
                return redirect()->intended('/');
         
            }else{
                $newUser = User::updateOrCreate(['email' => $user->email],[
                        'username' => $user->name,
                        'google_id'=> $user->id,
                        'password' => encrypt('123456dummy')
                    ]);
         
                Auth::login($newUser);
        
                return redirect()->intended('/');
            }
        
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}

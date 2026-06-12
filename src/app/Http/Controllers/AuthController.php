<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Address;


class AuthController extends Controller
{
    public function createStep1()
    {
        return view('auth.register');
    }
    public function postStep1(RegisterRequest $request, CreateNewUser $creator)
    {
        $user =$creator->create($request->validated());
        $user->sendEmailVerificationNotification();
        Auth::login($user);
        return redirect()->route('verification.notice');
    }
    public function createStep2()
    {
        $user =Auth::user();
        if(!$user){
            return redirect()->route('login');
        }
        return view('update',compact('user'));
    }
    public function updateProfile(AddressRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());
        return redirect('/mypage');
    }
    public function store(AddressRequest $request, CreateNewUser $creator)
    {
        $user= Auth::user();
        $input =$request->validated();
        $addressRecord = \App\Models\Address::create([
            'code' =>$input['code'],
            'address' =>$input['address'],
            'building' =>$input['building'] ??null,
        ]);
        $user->name =$request->name;
        $user->address_id =$addressRecord->id;
        if($request->hasFile('image')){
            $user->img_url =$request->file('image')->store('profile','public');
        }
        $user->save();
        return redirect('/');
    }
    public function showLogin()
    {
        return view('auth.login');
    }
    public function login(LoginRequest $request)
    {
        $credentials =$request->validated();
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect('/');
        }
        return back()->withErrors([
            'auth_failed' =>'ログイン情報が登録されていません',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
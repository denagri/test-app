<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\Address;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('update',['user'=>$user,
        ]);
    }
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $address = Address::updateOrCreate(
            ['id' => $user->address_id],
            [
                'code' => $request->code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        );
        $userData =[
            'name' => $request->name,
            'address_id' => $address->id,
        ];
        if($request->hasFile('image')){
            $path = $request->file('image')->store('profiles','public');
            $userData['image'] = $path;
        }

        $user->update($userData);
        return redirect()->route('profile.edit');
    }
}

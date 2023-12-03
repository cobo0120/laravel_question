<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\EditPasswordMail;

class UserController extends Controller
{
    public function mypage()
    {
        $user = Auth::user();

        return view('users.mypage',compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user = Auth::user();

        
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->name = $request->input('name') ? $request->input('name') : $user->name;
        $user->email = $request->input('email') ? $request->input('email') : $user->email;
        $user->save();

        return to_route('mypage');

    }

    // プロフィール画面
    public function profile(){
    $profiles = Auth::user();
    return view('users.profile',compact('profiles'));
    }


   // パスワード変更画面（プロフィール画面から遷移させる設定をビューで行う）
    public function edit_password(){
    
    return view('users.edit_password');
    }


    // パスワードアップデート
    public function update_password(Request $request)
    {
    $validatedData = $request->validate([
        'password' => 'required|confirmed',
    ]);

    $user = Auth::user();


    if ($request->input('password') == $request->input('password_confirmation')) {
        $user->password = Hash::make($request->input('password'));
        $user->save();
        // dd($user);
        Mail::to($user->email)->send(new EditPasswordMail($user));
    } else {
        return to_route('posts.edit_password');
    }

    return to_route('posts.index');
}


    
}

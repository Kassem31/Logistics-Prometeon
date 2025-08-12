<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;



class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    protected function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
        ];
    }

    protected function guard()
    {
        return AuthFacade::guard();
    }

    protected function resetPassword(Request $request)
    {
        $this->validate($request,$this->rules());
        $user = AuthFacade::user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        event(new PasswordReset($user));
        $this->guard()->login($user);
        return redirect()->route('user.resetpassword')->with('success','Password Updated Successfully');
    }


}

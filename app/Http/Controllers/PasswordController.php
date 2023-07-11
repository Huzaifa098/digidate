<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
       * Write code on Method
       *
       * @return response()
       */
      public function showForgetPasswordForm()
      {
         return view('reset.forgetPassword');
      }


      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitResetPassword(Request $request)
      {
        if (Auth::check()) {
            $user_email = Auth::user()->email;
        } else {
            $request->validate([
                'email' => 'required|email|exists:users',
            ]);
            $user_email = $request->email;
        }

         $token = Str::random(64);
         DB::table('password_resets')->insert([
              'email' => $user_email,
              'token' => $token,
              'created_at' => Carbon::now()
            ]);

          Mail::send('reset.reset_password', ['token' => $token], function($message) use ($user_email){
              $message->to($user_email);
              $message->subject('Wachtwoord herstellen');
          });

          return back()->with('message', 'We hebben de link voor het opnieuw instellen van uw wachtwoord per e-mail verzonden!');

      }
      /**
       * Write code on Method
       *
       * @return response()
       */
      public function showResetPasswordForm($token) {
        $check_token = DB::table('password_resets')
                              ->where([
                                'token' => $token
                              ])
                              ->first();
        if($check_token)
        {
            return view('reset.forgetPasswordLink', ['token' => $token]);
        }
        return redirect()->route('home');
      }

      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitResetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
              'password' =>
                [
                    'required','confirmed',
                    Password::min(8)
                                   ->mixedCase()
                                   ->letters()
                                   ->numbers()
                                   ->symbols()
                                   ->uncompromised(),
                ]

          ]);

          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email,
                                'token' => $request->token
                              ])
                              ->first();

          if(!$updatePassword){
              return back()->with('error', 'Invalid token!');
          }

          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);

         DB::table('password_resets')->where(['email'=> $request->email])->delete();

          return redirect()->route('home')->with('message', 'Your password has been changed!');
      }
}

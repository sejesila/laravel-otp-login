<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use http\Env\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthOtpController extends Controller
{
    //Returns the view otp login form
    public function login()
    {
        return view('auth.otp-login');
    }
    //Generate otp
    public function generate(Request $request)
    {
        $request->validate([
            'mobile_no'=>'required|exists:users,mobile_no',
        ]);

        //generate otp
        $verificationCode = $this->generateOTP($request->mobile_no);

        $message = "Your OTP Login is - " .$verificationCode->otp;

        //return with otp
        return redirect()->route('otp.verification',['user_id'=>$verificationCode->user_id])->with('success',$message);

    }

    public function verification($user_id)
    {
        return view('auth.otp-verification')->with([
            'user_id' => $user_id
        ]);
    }

    public function loginWithOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required'
        ]);

        $verificationCode = VerificationCode::where('user_id', $request->user_id)->where('otp', $request->otp)->first();


        $now = Carbon::now();
        if (!$verificationCode){
            return redirect()->back()->with('error', 'Your otp verification code is not valid');
        }
        if ($verificationCode && $now->isAfter($verificationCode->expire_at)) {

            return redirect()->route('otp.login')->with('error', 'Your otp verification code has expired. Please try again');
        }

        $user = User::whereId($request->user_id)->first();
        if($user){
            //expired the otp

            $verificationCode->update([
                'expire_at' => Carbon::now()

            ]);
            Auth::login($user);
            return redirect('/dashboard');
        }

        return redirect()->route('otp.login')->with('error', 'Your otp verification code has expired. Please try again');
    }

    public function generateOTP($mobile_no)
    {
        $user = User::where('mobile_no', $mobile_no)->first();

        //User does have any existing otp

        $verificationCode = VerificationCode::where('user_id', $user->id)->latest()->first();

        $now = Carbon::now();
        if ($verificationCode && $now->isBefore($verificationCode->expire_at)) {
            return $verificationCode;
        }

        //create new otp
        return VerificationCode::create([
            'user_id' => $user->id,
            'otp' => rand(12345,99999),
            'expire_at' =>Carbon::now()->addMinutes(10)
        ]);

    }
}

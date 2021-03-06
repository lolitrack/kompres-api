<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Alert;
use App\Models\User;
use App\Models\Facilitator;
use Carbon\Carbon;
use Validator;
use Illuminate\Auth\Events\Registered;

use App\Mail\userRegistered;
use Illuminate\Support\Facades\Mail;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);
        // var_dump($credentials);
        // dd($this->guard()->attempt($credentials, $request->has('remember')));
        if ($this->guard()->attempt($credentials, $request->has('remember'))) {
            $info = 'akun belum aktif';
            // dd($credentials);
            if (Auth::user()->status == 0) {
                //  if(Auth::user()->facilitator == null){
                //  Auth::user()->facilitator()->create([
                //     'nama_instansi' => "",
                //     'deskripsi_instansi' => "",
                //     'token_facilitator'   => str_random(20)
                // ]);
                //                //mengirim email
                //     Mail::to(Auth::user()->email)->send(new userRegistered(Auth::user()));
                //     $info ="silahkan lihat email anda";
                //     }
                Auth::logout();
                return redirect('/login')->with('warning', $info );
            }

           
          Auth::user()->update([
            "aktifitas_terakhir" => Carbon::now('Asia/Jakarta')
          ]);

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

}

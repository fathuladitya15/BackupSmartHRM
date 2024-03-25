<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Divisi;
use App\Models\UserLogins;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);


        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        $datauser       = User::where('username', $request->get('username'));
        if($datauser->count() == 0) {
            return response()->json(['status' => FALSE,'messages' => 'Akun ini tidak terdaftar']);
        }else {
            if($datauser->first()->roles != 'superadmin') {
                $dataKaryawan   = Karyawan::where('id_karyawan',$datauser->first()->id_karyawan)->first();
                $user   = $datauser->first();
                $roles  = $user->roles;


                $data   = User::find($user->id);

                if($request->get('roles') == 'karyawan') {
                    if($roles == 'admin') {
                        $data->roles = 'kr-project';
                        $data->update();
                    }else if(in_array($roles,['manajer','spv-internal'])){
                        $data->roles = 'kr-pusat';
                        $data->update();
                    }
                }else if($request->get('roles') == 'admin') {
                    if($data->roles == 'karyawan') {
                        return response()->json(['status' => FALSE,'messages' => 'Akun anda tidak terdaftar dimanagement']);
                    }else {
                        $jabatan  = Jabatan::find($dataKaryawan->jabatan);
                        $divisi   = Divisi::find($dataKaryawan->divisi);
                        // dd($data);

                        if($jabatan->nama_jabatan == 'Supervisor') {
                            if($divisi->id == 4) {
                                $data->roles == 'hrd';
                            }else {
                                $data->roles = 'spv-internal';
                            }
                            $data->update();
                        }else if($jabatan->nama_jabatan == 'Admin') {
                            $data->roles = 'admin';
                            $data->update();
                        }else if($jabatan->nama_jabatan == 'Koordinator Lapangan') {
                            $data->roles = 'korlap';
                            $data->update();
                        }else if($jabatan->nama_jabatan == 'Manager') {
                            $data->roles == 'manajer';
                            $data->update();
                        }

                    }
                }

            }


        }


        if ($this->attemptLogin($request)) {

            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }
        return $this->sendFailedLoginResponse($request);

    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->boolean('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogins::where('user_ip',$ip)->first();
        $userLogin = new UserLogins();
        if ($exist) {
            $userLogin->longitude       =  $exist->longitude;
            $userLogin->latitude        =  $exist->latitude;
            $userLogin->city            =  $exist->city;
            $userLogin->country_code    =  $exist->country_code;
            $userLogin->country         =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = getOsBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();

        User::find(Auth::id())->update(['last_session'=>Session::getId()]);

        return response()->json(['status' => TRUE,'title' => 'Berhasil Login','pesan' => 'Melanjutkan ke Beranda']);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/login')->with('message','Berhasil Logout');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}

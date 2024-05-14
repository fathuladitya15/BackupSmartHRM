<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\Models\Karyawan;
use App\Models\Clients;
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

    use AuthenticatesUsers;

    protected $redirectTo = '/home';

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


        $datauser           = User::where('username', $request->get('username')); // Ambil data dari table user


        if($datauser->count() == 0) { // Cek apabila data tidak tersedia

            return response()->json(['status' => FALSE,'messages' => 'Akun anda belum terdaftar.' ]);
        }

        $dataKaryawan       = Karyawan::where('id_karyawan',$datauser->first()->id_karyawan)->first();
        $divisi             = $dataKaryawan->divisi()->first()->nama_divisi;
        $jabatan            = $dataKaryawan->jabatan()->first()->nama_jabatan;
        $namaClient         = Clients::find($dataKaryawan->lokasi_kerja);
        // Cek apabila data tersedia


        $userLoginsRoles    = $datauser->first()->roles;    // Sebagai
        $asLogin            = $request->get('roles') == 'admin' ? 'project' : 'internal';

        if(in_array($userLoginsRoles,['spv-internal','admin','korlap'])) {
            if($asLogin == 'internal'){
                $updateData         = User::find($datauser->first()->id);
                if($userLoginsRoles == 'spv-internal') {
                    $updateData->roles  = 'kr-pusat';
                    $updateData->update();
                }else {
                    $updateData->roles  = 'kr-project';
                    $updateData->update();
                }
            }
        }

        if($userLoginsRoles == 'karyawan') {
            if($asLogin == 'project') {
                $r = 'Anda tidak terdaftar dalam management PFI';
                return response()->json(['status' => FALSE,'messages' => $r]);
            }
        }

        if(in_array($userLoginsRoles,['hrd','direktur','manajer','general-affair'])) {
            if($asLogin == 'project') {
                $r = 'Anda login sebagai Atasan';
            }else if($asLogin == 'internal') {
                $r = 'Anda login sebagai Karyawan Biasa';
            }
        }

        if(in_array($userLoginsRoles, ['kr-project','kr-pusat'])) {
            $updateData         = User::find($datauser->first()->id);
            if($asLogin == 'project'){
                if($jabatan == "Admin") {
                     $updateData->roles = 'admin';
                     $updateData->update();
                }
                if($jabatan == 'Supervisor') {
                    $updateData->roles = 'spv-internal';
                    $updateData->update();
                }
                if($jabatan == 'Koordinator Lapangan') {
                    $updateData->roles = 'korlap';
                    $updateData->update();
                }
                if(divisi($datauser->first()->id_karyawan) == 3) {

                    return response()->json(['pesan' => 'Anda anda terdaftar sebagai karyawan']);
                }
            }
        }

        if($userLoginsRoles == 'general-affair') {
            $updateData         = User::find($datauser->first()->id);
            if($asLogin == 'internal') {
                $updateData->roles  = 'kr-pusat';
                $updateData->update();
                // return response()->json(['roles' => $userLoginsRoles ,'asLogin' => $asLogin]);
            }else if($asLogin == 'project') {
                if($jabatan == 'Manager')
                $updateData->roles  = 'general-affair';
                $updateData->update();
                // return response()->json(['roles' => $userLoginsRoles,'asLogin' => $asLogin]);
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

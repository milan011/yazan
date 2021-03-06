<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Registration & Login Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller handles the registration of new users, as well as the
		    | authentication of existing users. By default, this controller uses
		    | a simple trait to add these behaviors. Why don't you explore it?
		    |
	*/

	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	/**
	 * Where to redirect users after login / registration.
	 * 登录跳转地址
	 * @var string
	 */
	protected $redirectTo = '/';

	// 退出后跳转地址
	protected $redirectAfterLogout = '/login';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct() {

		$this->middleware('guest', ['except' => 'logout']);
	}

	public function getLogin() {

		return $this->showLoginForm();
	}

	/**
	 * Show the application login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showLoginForm() {
		// dd('先登录');
		$view = property_exists($this, 'loginView')
		? $this->loginView : 'auth.authenticate';

		if (view()->exists($view)) {
			return view($view);
		}

		return view('auth.login');
	}

	//login rewrite
	public function login(Request $request) {

		$name = $request->input('name');
		$password = $request->input('password');

		if (empty($remember)) {
			//remember表示是否记住密码
			$remember = 0;
		} else {
			$remember = $request->input('remember');
		}

		//如果要使用记住密码的话，需要在数据表里有remember_token字段
		if (Auth::attempt(['name' => $name, 'password' => $password, 'status'=>'1'], $remember)) {

			return redirect()->intended('/');
		}

		return redirect('login')->withInput($request->except('password'))->with('msg', '用户名或密码错误')->withErrors('用户名或密码错误');
	}

	/**@pma
		     * Get a validator for an incoming registration request.
		     *
		     * @param  array  $data
		     * @return \Illuminate\Contracts\Validation\Validator
	*/
	protected function validator(array $data) {
		return Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data) {
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);
	}
}

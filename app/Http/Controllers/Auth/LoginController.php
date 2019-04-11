<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Models\User;
use Braintree\Customer;
use Illuminate\Http\Request;
use App\Services\ActivationService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
  use AuthenticatesUsers;

  protected $redirectTo = '/dashboard';
  protected $activationService;

  public function __construct (ActivationService $activationService) {
    $this->middleware('guest')->except('logout');
    $this->activationService = $activationService;
  }

  public function activateUser ($token) {
    if ($user = $this->activationService->activateUser($token)) {
      auth()->login($user);
      return redirect($this->redirectPath());
    }
    abort(404);
  }

  protected function validateLogin(Request $request) {
    $this->validate($request, [
      'email' => 'required|string',
      'password' => 'required|string'
    ]);
  }

  public function authenticated (Request $request, $user) {
    if (!$user->activated) {
      $this->activationService->sendActivationMail($user);
      auth()->logout();
      return back()->with('notification', 'We\'ve sent you an activation code. Please check your email for confirmation.');
    }
    return redirect()->intended($this->redirectPath());
  }

  public function redirect_to_provider ($social_network) {
    return Socialite::driver($social_network)->redirect();
  }

  public function handle_provider_callback ($social_network) {
    try {
      $social_user = Socialite::driver($social_network)->user();
    }
    catch (\Exception $e ) {
      return redirect('/');
    }

    $field = $social_network . '_id';
    $user = User::where($field, $social_user->id)->first();

    if (!$user) {
      $user = User::create([
        'name' => $social_user->name,
        'email' => $social_user->email,
        'image' => $social_user->avatar_original,
        'braintree_customer_id' => Customer::create([
          'firstName' => strtok($social_user->name, ' '),
          'lastName' => strstr($social_user->name, ' '),
          'email' => $social_user->email
        ])->customer->id,
        $field => $social_user->id,
        'activated' => true
      ]);

      $user->setting()->create();
    }

    auth()->login($user);
    return redirect('dashboard');
  }
}

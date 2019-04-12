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

  public function redirectToProvider ($socialNetwork) {
    return Socialite::driver($socialNetwork)->redirect();
  }

  public function handleProviderCallback ($socialNetwork) {
    try {
      $socialUser = Socialite::driver($socialNetwork)->user();
    }
    catch (\Exception $e ) {
      return back()->with('notification', $e->getMesage());
    }

    $field = $socialNetwork . '_id';
    $user = User::where($field, $socialUser->getId())->orWhere('email', $socialUser->getEmail())->first();

    if (!$user) {
      $user = User::create([
        'name' => $socialUser->getName(),
        'email' => $socialUser->getEmail(),
        'image' => $socialUser->getAvatar(),
        'braintree_customer_id' => Customer::create([
          'firstName' => strtok($socialUser->getName(), ' '),
          'lastName' => strstr($socialUser->getName(), ' '),
          'email' => $socialUser->getEmail()
        ])->customer->id,
        $field => $socialUser->getId(),
        'activated' => true
      ]);

      $user->setting()->create();
    }

    if ($user->$field != $socialUser->getId() && $user->email == $socialUser->getEmail()) {
      return back()->with('notification', 'Email address has already been taken.');
    }

    auth()->login($user);
    return redirect('dashboard');
  }
}

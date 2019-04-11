<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

class ResetPasswordController extends Controller
{
  use ResetsPasswords;

  protected $redirectTo = '/dashboard';

  public function __construct()
  {
    $this->middleware('guest');
  }

  protected function rules()
  {
      return [
          'token' => 'required',
          'email' => 'required|email',
          'password' => [
            'required',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
          ]   
      ];
  }
}

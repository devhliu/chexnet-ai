<?php

namespace App\Http\Requests;

use URL;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class AccountUpdateRequest extends FormRequest
{
  public function authorize () {
    return true;
  }

  public function rules () {
    $user_id = Auth::user()->id;
    $section = array_values(array_slice(explode('/', URL::previous()), -1))[0];

    if ($section === 'profile') {
      return [
        'name' => 'required|max:255',
      ];
    }
    else if ($section === 'payment') {
      return [
        'number' => 'required',
        'name' => 'required|string',
        'date' => 'required',
        'cvv' => 'required'
      ];
    }
    else if ($section === 'password') {
      return [
        'password' => 'bail|required|min:8',
        'new_password' => [
          'required',
          'min:8',
          'confirmed',
          'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
        ],
        'new_password_confirmation' => 'required|min:8'
      ];
    } 
    else if ($section === 'settings') {
      return [];
    }

    abort(404);
  }
}

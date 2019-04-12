<?php

namespace App\Models;

use App\Models\Card;
use App\Models\Setting;
use App\Models\Diagnosis;
use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Billable;
  use Notifiable;

  protected $fillable = [
    'name', 
    'email', 
    'password',
    'iamge',
    'braintree_customer_id',
    'facebook_id',
    'google_id',
    'twitter_id',
    'activated'
  ];

  protected $hidden = [
    'password', 
    'remember_token',
  ];

  public function cards () {
    return $this->hasMany(Card::class);
  }

  public function setting () {
    return $this->hasOne(Setting::class);
  } 

  public function diagnoses () {
    return $this->hasMany(Diagnosis::class);
  }
}

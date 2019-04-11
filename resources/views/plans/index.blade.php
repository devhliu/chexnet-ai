@extends('layouts.app')

@section('title')
  {{ __('Plans') }}
@endsection

@section('content')
  <div class="container m--margin-bottom-35">
    {{ Breadcrumbs::render('plans') }}

    <form id="credit-card-form" 
      action="{{ route('subscribe') }}"
      class="m-form m-form--fit m-form--label-align-right m-form--state m--margin-top-10"
      method="POST" 
      autocomplete="off"
    >
      {{ csrf_field() }}

      <input id="nonce" type="hidden" name="nonce">

      <div class="row">
        <div class="col-lg-6 m--margin-bottom-35">
          <div class="m-portlet">
            <div class="m-portlet__head">
              Your Details
            </div>
            <div class="m-portlet__body m--margin-bottom-20">
              <div class="form-group m-form__group row {{ $errors->has('name') ? 'has-danger' : '' }}">
                <label class="col-form-label col-lg-2 col-sm-12">
                  Full Name
                </label>
                <div class="col-lg-10 col-md-12 col-sm-12">
                  <div class="m-input-icon m-input-icon--left">
                    <input 
                      class="form-control m-input {{ $errors->has('name') ? 'form-control-danger' : '' }}" 
                      type="text" 
                      name="name" 
                      placeholder="Full name" 
                      value="{{ old('name', $user->name) }}" 
                      disabled
                    />
                    <span class="m-input-icon__icon m-input-icon__icon--left">
                      <span>
                        <i class="la la-user"></i>
                      </span>
                    </span>
                  </div>
                  @if ($errors->has('name'))
                    <div class="form-control-feedback">
                      {{ $errors->first('name') }}
                    </div>
                  @endif
                </div>
              </div>

              <div class="form-group m-form__group row">
                <label class="col-form-label col-lg-2 col-sm-12">
                  Email Address
                </label>
                <div class="col-lg-10 col-md-12 col-sm-12">
                  <div class="m-input-icon m-input-icon--left">
                    <input 
                      type="email" 
                      class="form-control m-input" 
                      value="{{ $user->email }}" 
                      disabled
                    />
                    <span class="m-input-icon__icon m-input-icon__icon--left">
                      <span>
                        <i class="la la-at"></i>
                      </span>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group m-form__group row">
                <label class="col-lg-2 col-form-label">
                  Choose a Plan
                </label>
                <div class="col-lg-10">
                  <div class="row">
                    @foreach ($plans as $index => $plan)
                      <div class="col-lg-12">
                        <label class="m-option">
                          <span class="m-option__control">
                            <span class="m-radio m-radio--focus">
                              <input 
                                type="radio" 
                                name="plan" 
                                value="{{ $plan->id }}"
                                {{ $index == 0 ? "checked" : "" }}
                              />
                              <span></span>
                            </span>
                          </span>
                          <span class="m-option__label">
                            <span class="m-option__head">
                              <span class="m-option__title">
                                {{ $plan->name }}
                              </span>
                              <span class="m-option__focus">
                                ${{ $plan->cost }}
                              </span>
                            </span>
                            <span class="m-option__body">
                              {{ $plan->description }}
                            </span>
                          </span>
                        </label>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            <div class="m-portlet__body" style="border-top: 1px dashed #ebedf2; box-shadow: 0 1px 0 rgba(0,0,0,0.05) inset, 0 2px 0 rgba(0,0,0,0.1); padding: 0px;">
              <div class="alert m-alert--default m-alert--icon" style="margin-bottom:0; background-color: #f9fafa;">
                <div class="m-alert__icon">
                  <i class="flaticon-exclamation-2"></i>
                </div>
                <div class="m-alert__text">
                  CheXNet: Radiologist-Level Pneumonia Detection on Chest X-Rays with Deep Learning. <br>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="m-portlet">
            <div class="m-portlet__head">
              Checkout
            </div>
            <div class="m-portlet__body m--margin-bottom-20">
              <div id="card-wrapper" class="m--hide"></div>

              @if (count($cards))
                <div id="number-form" class="form-group m-form__group row {{ $errors->has('card') ? 'has-danger' : '' }}">
                  <label class="col-form-label col-lg-3 col-sm-12">
                    Card No.
                  </label>
                  <div class="col-lg-9 col-md-12 col-sm-12">
                    <select id="card" name="card" class="form-control {{ $errors->has('card') ? 'form-control-danger' : '' }}">
                      @foreach($cards as $index => $card)
                        <option value="{{ $card->token }}" 
                          data-content="<span class='m--margin-right-10'>
                            <img src='{{ $card->imageUrl }}' height='20'></span> 
                            {{ chunk_split(str_replace('******', $card->bin, $card->maskedNumber), 4, ' ') }}" {{ $card->default ? 'selected' : '' }}
                        >
                          {{ $index }}
                        </option>
                      @endforeach
                    </select>
                    @if ($errors->has('card'))
                      <div class="form-control-feedback">
                        {{ $errors->first('card') }}
                      </div>
                    @endif
                    <div id="number-form-input" class="form-control-feedback m--hide"></div>
                  </div>
                </div>
              @else
                <div id="number-form" class="form-group m-form__group row {{ $errors->has('number') ? 'has-danger' : '' }}">
                  <label class="col-form-label col-lg-3 col-sm-12">
                    Card No.
                  </label>
                  <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="m-input-icon m-input-icon--left">
                      <input
                        id="card-number" 
                        class="form-control m-input {{ $errors->has('number') ? 'form-control-danger' : '' }}" 
                        type="text"
                        placeholder="Card Number" 
                        value="{{ old('number') }}" 
                        autoselect
                      />
                      <span class="m-input-icon__icon m-input-icon__icon--left">
                        <span>
                          <i class="la la-credit-card"></i>
                        </span>
                      </span>
                    </div>
                    @if ($errors->has('number'))
                      <div class="form-control-feedback">
                        {{ $errors->first('number') }}
                      </div>
                    @endif
                    <div id="number-form-input" class="form-control-feedback m--hide"></div>
                  </div>
                </div>

                <div id="name-form" class="form-group m-form__group row {{ $errors->has('name') ? 'has-danger' : '' }}">
                  <label class="col-form-label col-lg-3 col-sm-12">
                    Name
                  </label>
                  <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="m-input-icon m-input-icon--left">
                      <input 
                        id="name" 
                        class="form-control m-input {{ $errors->has('name') ? 'form-control-danger' : '' }}" 
                        type="text" 
                        placeholder="Full name" 
                      />
                      <span class="m-input-icon__icon m-input-icon__icon--left">
                        <span>
                          <i class="la la-user"></i>
                        </span>
                      </span>
                    </div>
                    @if ($errors->has('name'))
                      <div class="form-control-feedback">
                        {{ $errors->first('name') }}
                      </div>
                    @endif
                    <div id="name-form-input" class="form-control-feedback m--hide"></div>
                  </div>
                </div>
              @endif

              <div id="date-form" class="form-group m-form__group row {{ $errors->has('date') ? 'has-danger' : '' }}">
                <label class="col-form-label col-lg-3 col-sm-12">
                  Exp. Date
                </label>
                <div class="col-lg-9 col-md-12 col-sm-12">
                  <div class="m-input-icon m-input-icon--left">
                    <input 
                      id="date" 
                      class="form-control m-input {{ $errors->has('date') ? 'form-control-danger' : '' }}" 
                      type="text" 
                      placeholder="Expiration date" 
                    />
                    <span class="m-input-icon__icon m-input-icon__icon--left">
                      <span>
                        <i class="la la-calendar"></i>
                      </span>
                    </span>
                  </div>
                  @if ($errors->has('date'))
                    <div class="form-control-feedback">
                      {{ $errors->first('date') }}
                    </div>
                  @endif
                  <div id="date-form-input" class="form-control-feedback m--hide"></div>
                </div>
              </div>

              <div id="cvv-form" class="form-group m-form__group row {{ $errors->has('cvv') ? 'has-danger' : '' }}">
                <label class="col-form-label col-lg-3 col-sm-12">
                  CVV
                </label>
                <div class="col-lg-9 col-md-12 col-sm-12">
                  <div class="m-input-icon m-input-icon--left">
                    <input 
                      id="cvv" 
                      class="form-control m-input {{ ($errors->has('cvv')) ? 'form-control-danger' : '' }}" 
                      type="text" 
                      placeholder="CVV"
                    />
                    <span class="m-input-icon__icon m-input-icon__icon--left">
                      <span>
                        <i class="la la-unlock-alt"></i>
                      </span>
                    </span>
                  </div>
                  @if ($errors->has('cvv'))
                    <div class="form-control-feedback">
                      {{ $errors->first('cvv') }}
                    </div>
                  @endif
                  <div id="cvv-form-input" class="form-control-feedback m--hide"></div>
                </div>
              </div>

              <div class="m-form__seperator m-form__seperator--dashed m-form__seperator--space-2x"></div>

              <div id="address-form" class="form-group m-form__group row {{ $errors->has('address') ? 'has-danger' : '' }}">
                <label class="col-form-label col-lg-3 col-sm-12">
                  Address
                </label>
                <div class="col-lg-9 col-md-12 col-sm-12">
                  <input 
                    id="address" 
                    class="form-control m-input {{ $errors->has('address') ? 'form-control-danger' : '' }}" 
                    type="text" 
                    name="address" 
                    placeholder="Billing Address" 
                    value="{{ old('address', '') }}"
                  />
                  @if ($errors->has('address'))
                    <div class="form-control-feedback">
                      {{ $errors->first('address') }}
                    </div>
                  @endif
                  <div id="address-form-input" class="form-control-feedback m--hide"></div>
                </div>
              </div>

              <div id="zipcode-form" class="form-group m-form__group row {{ $errors->has('zipcode') ? 'has-danger' : '' }}">
                <label class="col-form-label col-lg-3 col-sm-12">
                  Zipcode
                </label>
                <div class="col-lg-9 col-md-12 col-sm-12">
                  <input 
                    id="zipcode" 
                    class="form-control m-input {{ $errors->has('zipcode') ? 'form-control-danger' : '' }}" 
                    type="text" 
                    name="zipcode" 
                    placeholder="Zipcode" 
                    value="{{ old('zipcode', '') }}"
                  />
                  @if ($errors->has('zipcode'))
                    <div class="form-control-feedback">
                      {{ $errors->first('zipcode') }}
                    </div>
                  @endif
                  <div id="zipcode-form-input" class="form-control-feedback m--hide"></div>
                </div>
              </div>
            </div>

            <div class="m-portlet__foot">
              <div class="m-form__actions">
                <div class="row">
                  <div class="col-sm-2"></div>
                  <div class="col-sm-7">
                    <button type="submit" class="btn btn-focus m-btn m-btn--custom m-btn--wide  m-btn--air m-btn--icon join-plan">
                      Go premium
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('scripts')
  <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>
  <script src="{{ asset('js/jquery-credit-card-validator.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/places.js@1.4.18"></script>
  <script src="{{ asset('js/smooth-scroll.js') }}"></script>
  <script src="{{ asset('js/jquery-card.js') }}"></script>
  @include('plans.scripts')
@endsection

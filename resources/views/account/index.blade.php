@extends('layouts.app')

@section('title')
  Account
@endsection

@section('content')
  <div class="container m--margin-bottom-25">
    {{ Breadcrumbs::render($section) }}

    <div class="row m--margin-top-10">
      <div class="col-xl-3 m--margin-bottom-25">
        <div class="m-portlet">
          <div class="m-portlet__body" style="padding: 2.2rem 2.2rem 2rem;">
            <div class="m-card-profile">
              <div class="m-card-profile__pic">
                <div class="m-card-profile__pic-wrapper">
                  <a href="#" data-toggle="modal" data-target="#upload-image-modal">
                    <img id="user-image" src="{{ $user->image }}" class="img-link img-fluid">
                  </a>
                </div>
              </div>

              <div class="m-card-profile__details">
                <span class="m--font-bolder lead">
                  {{ $user->name }}
                </span>
                <br>
                <a href="#" class="m-card-profile__email m-link m--margin-bottom-20">
                  {{ $user->email }}
                </a>
                <br>
                <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#upload-image-modal">
                  Change Image
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-9">
        <div class="m-portlet m-portlet--tabs">
          <div class="m-portlet__head">
            <div class="m-portlet__head-tools">
              <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--left m-tabs-line--focus">
                <li class="nav-item m-tabs__item">
                  <a href="/account/profile" 
                    class="nav-link m-tabs__link {{ $section === 'profile' ? 'active' : '' }}">
                    <i class="flaticon-user"></i>
                    Profile
                  </a>
                </li>

                <li class="nav-item m-tabs__item">
                  <a href="/account/payment" 
                    class="nav-link m-tabs__link {{ $section === 'payment' ? 'active' : '' }}">
                    <i class="flaticon-coins"></i>
                    Payments
                  </a>
                </li>

                @if ($user->password)
                  <li class="nav-item m-tabs__item">
                    <a href="/account/password" 
                      class="nav-link m-tabs__link {{ $section === 'password' ? 'active' : '' }}">
                      <i class="flaticon-lock-1"></i>
                      Password
                    </a>
                  </li>
                @endif

                <li class="nav-item m-tabs__item">
                  <a href="/account/settings" 
                    class="nav-link m-tabs__link {{ $section === 'settings' ? 'active' : '' }}">
                    <i class="flaticon-settings"></i>
                    Settings
                  </a>
                </li>
              </ul>
            </div>

            <div class="m-portlet__head-tools">
              <ul class="m-portlet__nav" style="list-style-type: none;">
                <li class="m-portlet__nav-item">
                  <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="click">
                    <a href="#" class="m-portlet__nav-link btn btn-lg btn-focus m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill m-dropdown__toggle" style="width: 35px; height: 35px;">
                      <i class="la la-plus"></i>
                    </a>
                    <div class="m-dropdown__wrapper" style="margin-right: 0px;">
                      <div class="m-dropdown__inner">
                        <div class="m-dropdown__body">
                          <div class="m-dropdown__content">
                            <ul class="m-nav">
                              <li class="m-nav__section m-nav__section--first">
                                <span class="m-nav__section-text">
                                  Quick Actions
                                </span>
                              </li>
                              <li class="m-nav__item">
                                <a href="{{ route('dashboard') }}" class="m-nav__link">
                                  <i class="m-nav__link-icon flaticon-line-graph"></i>
                                  <span class="m-nav__link-text">
                                    Dashboard
                                  </span>
                                </a>
                              </li>

                              <li class="m-nav__item">
                                <a href="/account/payment" class="m-nav__link">
                                  <i class="m-nav__link-icon flaticon-coins"></i>
                                  <span class="m-nav__link-text">
                                    Add Payment
                                  </span>
                                </a>
                              </li>

                              <li class="m-nav__item">
                                <a href="#" class="m-nav__link" data-toggle="modal" data-target="#upload-image-modal">
                                  <i class="m-nav__link-icon flaticon-multimedia-2"></i>
                                  <span class="m-nav__link-text">
                                    Change Image
                                  </span>
                                </a>

                                <form id="image-form" action="#" method="post" class="m--hide">
                                  <input type="hidden" name="image" role="uploadcare-uploader">
                                </form>
                              </li>
                              <li class="m-nav__section">
                                <span class="m-nav__section-text">
                                  Useful Links
                                </span>
                              </li>
                              <li class="m-nav__item">
                                <a href="#" class="m-nav__link" data-toggle="modal" data-target="#about-modal">
                                  <i class="m-nav__link-icon flaticon-info"></i>
                                  <span class="m-nav__link-text">
                                    About
                                  </span>
                                </a>
                              </li>
                              <li class="m-nav__item">
                                <a href="/support" class="m-nav__link">
                                  <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                  <span class="m-nav__link-text">
                                    Support
                                  </span>
                                </a>
                              </li>
                              <li class="m-nav__item">
                                <a href="#" class="m-nav__link" data-toggle="modal" data-target="#contact-modal">
                                  <i class="m-nav__link-icon flaticon-paper-plane"></i>
                                  <span class="m-nav__link-text">
                                    Contact
                                  </span>
                                </a>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <div class="tab-content">
            <div class="tab-pane {{ $section === 'profile' ? 'active' : '' }}">
              @include('account.partials.profile')
            </div>

            <div class="tab-pane {{ $section === 'payment' ? 'active' : '' }}">
              @include('account.partials.payment')
            </div>

            <div class="tab-pane {{ $section === 'password' ? 'active' : '' }}">
              @include('account.partials.password')
            </div>

            <div class="tab-pane {{ $section === 'settings' ? 'active' : '' }}">
              @include('account.partials.settings')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @include('account.partials.scripts._master_scripts')
  @include('account.partials.scripts._payment_scripts')
@endsection

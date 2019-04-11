<div class="top-border"></div>
<nav id="navbar" class="navbar navbar-light navbar-expand-sm" style="border-bottom: 0.07rem solid #ebedf2;">
  <div class="container">
    <a class="navbar-brand m--margin-right-10" href="{{ route('home') }}">
      <img src="{{ asset('img/logo.png')  }}" class="mr-2" width="25" height="30">
      CheXNet
    </a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item m-dropdown m-dropdown--inline m-dropdown--small m-dropdown--arrow m-dropdown--align-right" data-dropdown-toggle="click">
          <a class="nav-link dropdown-toggle m-dropdown__toggle" href="#"> 
            <img src="{{ $user->image }}" class="m--img-rounded mr-1 img-link avatar">
            Hi, {{ strtok($user->name, ' ') }}
          </a>

          <div class="m-dropdown__wrapper">
            <div class="m-dropdown__inner">
              <div class="m-dropdown__body">
                <div class="m-dropdown__content">
                  <ul class="m-nav">
                    <li class="m-nav__item">
                      <a href="/dashboard" class="m-nav__link">
                        <i class="m-nav__link-icon flaticon-line-graph"></i>
                        <span class="m-nav__link-text">
                          Dashboard
                        </span>
                      </a>
                    </li>

                    <li class="m-nav__item">
                      <a href="/account/profile" class="m-nav__link">
                        <i class="m-nav__link-icon flaticon-user"></i>
                        <span class="m-nav__link-title">
                          <span class="m-nav__link-wrap">
                            <span class="m-nav__link-text">
                              My Account
                            </span>
                          </span>
                        </span>
                      </a>
                    </li>

                    <li class="m-nav__item">
                      <a href="/plans" class="m-nav__link">
                        <i class="m-nav__link-icon flaticon-piggy-bank"></i>
                        <span class="m-nav__link-title">
                          <span class="m-nav__link-wrap">
                            <span class="m-nav__link-text">
                              Go Premium
                            </span>
                          </span>
                        </span>
                      </a>
                    </li>

                    <li class="m-nav__separator m-nav__separator--dashed m-nav__separator--fit"></li>

                    <li class="m-nav__item">
                      <a href="/dashboard#history" class="m-nav__link">
                        <i class="m-nav__link-icon flaticon-open-box"></i>
                        <span class="m-nav__link-text">
                          My Xrays
                        </span>
                      </a>
                    </li>

                    <li class="m-nav__item">
                      <a href="/account/settings" class="m-nav__link">
                        <i class="m-nav__link-icon flaticon-settings"></i>
                        <span class="m-nav__link-text">
                          Settings
                        </span>
                      </a>
                    </li>

                    <li class="m-nav__separator m-nav__separator--dashed m-nav__separator--fit"></li>

                    <li class="m-nav__item">
                      <a href="#" 
                        class="btn btn-outline-danger m-btn m-btn--wide btn-sm" 
                        onclick="event.preventDefault(); document.getElementById('sign-out-form').submit();"
                      >
                        Sign out
                      </a>

                      <form id="sign-out-form" action="{{ route('logout') }}" class="m--hide" method="POST">
                        {{ csrf_field() }}
                      </form>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

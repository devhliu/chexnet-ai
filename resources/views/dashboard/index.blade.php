@extends('layouts.app')

@section('title')
  Dashboard
@endsection

@section('content')
  <div class="container m--margin-bottom-50">
    {{ Breadcrumbs::render('dashboard') }}

    <div class="row m--margin-top-10">
      <div id="model" class="col-xl-12 m--padding-bottom-30">
        <span class="m-badge m-badge--focus m-badge--wide m-badge--rounded m--font-boldest">
          Model
        </span>
      </div>

      <div class="col-xl-12 m--padding-bottom-60">
        <xray-upload></xray-upload>
      </div>

      <div id="history" class="col-xl-12 m--padding-bottom-30">
        <span class="m-badge m-badge--focus m-badge--wide m-badge--rounded m--font-boldest">
          History
        </span>
      </div>

      <div class="col-xl-12">
        <div class="m-portlet">
          <div
            class="m-portlet__head"
            style="padding: 0 20px 0 20px; height: 4.0rem;"
          >
            <div class="m-portlet__head-caption">
              <div class="m-portlet__head-title">
                <h4 
                  class="m-portlet__head-text"
                  style="font-size: 16px !important;"
                >
                  History
                </h4>
              </div>
            </div>

            <div class="m-portlet__head-tools">
              <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item m-portlet__nav-item--last">
                  <form class="form-inline" action="" method="GET" autocomplete="off">
                    <div class="m-input-icon m-input-icon--left">
                      <input id="search" type="text" name="search_query" class="form-control m-input" placeholder="Live search...">
                      <span class="m-input-icon__icon m-input-icon__icon--left">
                        <span>
                          <i class="flaticon-search-1" style="font-size: 1rem; padding-right: 5px;"></i>
                        </span>
                      </span>
                    </div>
                  </form>
                </li>
              </ul>
            </div>
          </div>

          <div class="m-portlet__body">
            <div class='m_datatable'></div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('js/datatable.js') }}"></script>
@endsection

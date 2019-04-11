@extends('layouts.app')

@section('title')
  Home
@endsection

@section('content')
  <div class="container vertical-center">
    <div class="row">
      <div class="col-md-1"></div>
      <div class="col-md-10">
        <h1 class="m--font-bolder">
          CheXNet: Radiologist-Level<br>Pneumonia Detection on Chest X-Rays with Deep Learning
        </h1>
        <br>
        <a id="get-started"
          href="{{ route('login') }}"
          class="btn btn-lg btn-focus m-btn--wide m-btn--air m-btn--bolder m-btn m-btn--icon m-btn--icon-right"
        >
          <span>
            <span>
              GET STARTED
            </span>
            &nbsp;
            <i id="btn-icon" class="la la-long-arrow-right" style="font-weight: 600;"></i>
          </span>
        </a>
      </div>
    </div>
  </div>
@endsection

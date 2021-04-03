@extends('layouts.auth')

@section('title', 'Cardvest Forgot Password')
@section('content')

<div class="container-fluid">
  <div class="row h-full align-items-center" style="background: url(../images/ath-gfx.png) #1c4938 no-repeat;">
    <div class="col-md-8 col-lg-5 mx-auto">
      <div class="d-flex justify-content-center">
        <a href="./" class="page-ath-logo"><img src="images/logo.png" srcset="images/logo2x.png 2x" alt="logo"></a>
      </div>
      <div class="bg-white radius-secondary p-4 p-md-5 mt-3">
        @if (session('status'))
        <div class="alert alert-success">
          <p>{{ session('status') }}</p>
        </div>
        @endif
        <h2 class="page-ath-heading">Reset password <span>If you forgot your password, well, then we’ll email you
            instructions to reset your password.</span></h2>
        <form action="{{ route('password.email') }}" method="post">
          @csrf
          <div class="input-item">
            <input type="text" placeholder="Your Email" name="email"
              class="input-bordered @error('email') is-invalid @enderror">
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <button class="btn btn-primary btn-block">Send Reset Link</button>
            </div>
            <div>
              <a href="{{ route('login') }}">Return to login</a>
            </div>
          </div>
          <div class="gaps-2x"></div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection()
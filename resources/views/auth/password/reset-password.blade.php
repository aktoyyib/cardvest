@extends('layouts.auth')

@section('title', 'Cardvest - Reset Password')
@section('content')
<div class="container-fluid">
  <div class="row h-full align-items-center" style="background: #1c4938 no-repeat;">
    <div class="col-md-8 col-lg-5 mx-auto">
      <div class="d-flex justify-content-center">
        <a href="/" class="page-ath-logo"><img src="{{ asset('images/logo.png') }}" alt="logo"></a>
      </div>
      <div class="bg-white radius-secondary p-4 p-md-5 mt-3">
        <h2 class="page-ath-heading">Reset your password <span>Enter a new password for your account.</span></h2>
        <form action="{{ route('password.update') }}" method="post">
          @csrf
          <input type="hidden" name="token" value="{{ request()->route('token') }}">
          <div class="input-item">
            <input id="email" type="email" class="input-bordered @error('email') is-invalid @enderror" name="email"
              value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <div class="input-item">
            <input type="password" placeholder="Password" name="password"
              class="input-bordered @error('password') is-invalid @enderror" required autocomplete="new-password">
            @error('password')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <div class="input-item">
            <input type="password" placeholder="Confirm Password" name="password_confirm" class="input-bordered"
              required autocomplete="new-password">
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <button class="btn btn-primary btn-block">Reset Password</button>
            </div>
          </div>
          <div class="gaps-2x"></div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection()
@extends('layouts.auth')

@section('title', 'Cardvest Sign In')
@section('content')
<div class="container-fluid">
  <div class="row h-full align-items-center" style="background: #1c4938 no-repeat;">
    <div class="col-md-8 col-lg-5 mx-auto">
      <div class="d-flex justify-content-center">
        <a href="/" class="page-ath-logo"><img src="{{ asset('images/logo.png') }}" alt="logo"></a>
      </div>
      <div class="bg-white radius-secondary p-4 p-md-5 mt-3">
        <h2 class="page-ath-heading">Sign in</h2>
        @if (session('status'))
        <div class="alert alert-success">
          <p>{{ session('status') }}</p>
        </div>
        @endif
        <form action="{{ route('login') }}" method="post">
          @csrf
          <div class="input-item">
            <input type="text" placeholder="Your Email" name="email" value="{{ old('username') }}"
              class="input-bordered" required autocomplete="email" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="input-item">
            <input type="password" placeholder="Password" name="password"
              class="input-bordered @error('password') is-invalid @enderror">
            @error('password')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="input-item text-left">
              <input class="input-checkbox input-checkbox-md" name="remember" id="remember-me" type="checkbox"
                {{ old('remember') ? 'checked' : '' }}>
              <label for="remember-me">Remember Me</label>
            </div>
            @if (Route::has('password.request'))
            <div>
              <a href="{{ route('password.request') }}">Forgot password?</a>
              <div class="gaps-2x"></div>
            </div>
            @endif
          </div>
          <button class="btn btn-primary btn-block">Sign In</button>
        </form>

        <div class="gaps-2x"></div>
        <div class="gaps-2x"></div>
        <div class="form-note">
          Don’t have an account? <a href="{{ route('register') }}"> <strong>Sign up here</strong></a>
        </div>
        <div class="d-flex justify-content-center mt-1">
          <a href="https://cardvest.ng" class="btn btn-primary text-center">Back to Home</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection()
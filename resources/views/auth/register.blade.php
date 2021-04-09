@extends('layouts.auth')

@section('title', 'Cardvest Sign In')
@section('content')
<div class="container-fluid">
  <div class="row h-full align-items-center" style="background:  #1c4938 no-repeat;">
    <div class="col-md-8 col-lg-5 mx-auto">
      <div class="d-flex justify-content-center">
        <a href="/" class="page-ath-logo"><img src="{{ asset('images/logo.png') }}" height="48px"
            srcset="images/logo2x.png 2x" alt="logo"></a>
      </div>
      <div class="bg-white radius-secondary p-4 p-md-5 mt-3">
        <h2 class="page-ath-heading">Sign up</h2>
        <form action="{{ route('register') }}" method="post">
          @csrf
          <div class="row">
            <div class="col">
              <div class="input-item">
                <input type="text" placeholder="Username" name="username"
                  class="input-bordered @error('username') is-invalid @enderror" value="{{ old('username') }}" required
                  autocomplete="username" autofocus>
                @error('username')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="col">
              <div class="input-item">
                <input type="text" placeholder="Your Email" name="email"
                  class="input-bordered @error('email') is-invalid @enderror" value="{{ old('email') }}" required
                  autocomplete="email">
                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
          </div>
          <div class="input-item">
            <input type="text" placeholder="Phone Number" name="phonenumber"
              class="input-bordered @error('phonenumber') is-invalid @enderror" value="{{ old('phonenumber') }}"
              required autocomplete="phonenumber">
            @error('phonenumber')
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
            <input type="password" placeholder="Confirm Password" name="password_confirmation" class="input-bordered"
              required autocomplete="new-password">
          </div>
          <div class="input-item text-left">
            <input class="input-checkbox input-checkbox-md" name="terms" id="term-condition" type="checkbox">
            <label for="term-condition">I agree to Cardvest's <a href="https://cardvest.ng/privacy-policy-2">Privacy
                Policy</a> &amp; <a href="https://cardvest.ng/terms-and-conditions"> Terms.</a></label>
            @error('terms')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <button class="btn btn-primary btn-block">Create Account</button>
        </form>
        <div class="gaps-2x"></div>
        <div class="gaps-2x"></div>
        <div class="form-note">
          Already have an account ? <a href="{{ route('login') }}"> <strong>Sign in instead</strong></a>
        </div>
        <div class="d-flex justify-content-center mt-1">
          <a href="https://cardvest.ng" class="btn btn-primary text-center">Back to Home</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection()
@extends('layouts.auth')

@section('title', 'Cardvest 2FA Authentication')
@section('content')

<div class="container-fluid">
  <div class="row h-full align-items-center" style="background: #1c4938 no-repeat;">
    <div class="col-md-8 col-lg-5 mx-auto">
      <div class="d-flex justify-content-center">
        <a href="/" class="page-ath-logo"><img src="{{ asset('images/logo.png') }}"  alt="logo"></a>
      </div>
      <div class="bg-white radius-secondary p-4 p-md-5 mt-3">
        @if (session('status'))
        <div class="alert alert-success">
          <p>{{ session('status') }}</p>
        </div>
        @endif
        <h2 class="page-ath-heading">2FA Authentication code</h2>
        <form action="" method="post">
          @csrf
          <div class="input-item">
            <input type="text" placeholder="2FA code" name="code"
              class="input-bordered @error('code') is-invalid @enderror" autofocus>
            @error('code')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <button class="btn btn-primary btn-block">Submit</button>
            </div>
            <div>
              <a href="{{ url()->previous() }}">Back</a>
            </div>
          </div>
          <div class="gaps-2x"></div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection()
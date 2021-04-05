@extends('layouts.auth')

@section('title', 'Cardvest - Page not found')
@section('content')
<div class="vh100 d-flex align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-7 col-xl-6 text-center">
        <div class="error-content">
          <span class="error-text-large">503</span>
          <h4 class="text-dark">Opps! Why you’re here?</h4>
          <p>Unauthorized Access.</p>
          <a href="/" class="btn btn-primary">Back to Home</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
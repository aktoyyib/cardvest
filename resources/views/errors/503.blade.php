@extends('layouts.auth')

@section('title', 'Cardvest - Page not found')
@section('content')
<div class="vh100 d-flex align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-7 col-xl-6 text-center">
        <div class="error-content">
          <h2 class="text-primary font-weight-bold">Relax 👀</h2>
          <img src="https://media.giphy.com/media/SpopD7IQN2gK3qN4jS/giphy.gif" alt="" style="max-height: 200px;">
          <h4 class="text-dark">Cardvest is down for maintenance.</h4>
          <p>Time to chillax</p>
          <a href="/" class="btn btn-primary">Back to Home</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
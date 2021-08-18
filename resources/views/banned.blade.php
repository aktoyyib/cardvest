@extends('layouts.app')

@section('title', 'Cardvest - Your account has been suspended')
@section('content')
<div class="page-content">
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-lg-10 col-xl-8">
              <div class="card content-area">
                  <div class="card-innr">
                      <div class="status border-warning px-md-5">
                          <div class="status-icon d-flex justify-content-center align-items-center">
                              <em class="fa fa-exclamation-triangle fa-3x text-warning"></em>
                          </div>
                          <span class="status-text large text-dark"><span class="text-capitalize font-bold">{{ auth()->user()->username }}</span>, your account has been {{ auth()->user()->banTerms() }} suspended.</span>
                          <p class="px-md-5">We received your data, our team will check and get back you via email.</p>
                      </div>
                  </div>
              </div><!-- .card -->
              <div class="gaps-1x"></div>
              <div class="gaps-3x d-none d-sm-block"></div>
          </div>
      </div>
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection
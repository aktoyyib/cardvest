@extends('layouts.app')

@section('title', 'Cardvest - Admin User\'s List')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 aside sidebar-left">
        @include('partials.admin_sidebar')
      </div>
      <div class="col-lg-9 main-content">
        <form action="{{ route('settings.store') }}" method="POST">
            @csrf
            <div class="card content-area">
              <div class="card-innr">
                <div class="card-head">
                  <h4 class="card-title">App Settings</h4>
                  <hr>
                </div>

                <div class="d-flex mb-3">
                    <div class="d-flex flex-column pr-3">
                        <h5 class="mb-0">{{ $cedis_to_naira->label }}</h5>
                        <small>{{ $cedis_to_naira->description }}</small>
                    </div>
                    <div class="input-item input-with-label">
                        <input class="input-bordered" type="text" value="{{ $cedis_to_naira->value }}" name="{{ $cedis_to_naira->key }}" placeholder="{!! $cedis_to_naira->inputLabel !!}">
                    </div><!-- .input-item -->
                </div>
              </div><!-- .card-innr -->
              <div class="card-footer bg-white">
                <div class="d-sm-flex justify-content-between align-items-center">
                    <button class="btn btn-primary">Update</button>
                    <div class="gaps-2x d-sm-none"></div>
                </div>
              </div>
            </div><!-- .card -->
        </form>

      </div>
    </div>
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script>

</script>
@endpush

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

        <div class="card content-area">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">App Settings</h4>
              <hr>
            </div>

            {{-- <div class="pdb-1-5x">
                <h5 class="card-title card-title-sm text-dark">Security Settings</h5>
            </div> --}}
            <div class="d-flex mb-3">
                <div class="d-flex flex-column pr-3">
                    <h5 class="mb-0">Wallet</h5>
                    <small>Save my Activities log</small>
                </div>
                <div class="input-item">
                    <input type="checkbox" class="input-switch input-switch-sm" id="save-log" checked>
                    <label for="save-log"></label>
                </div>
                <div class="input-item input-with-label">
                    <label for="mobile-number" class="input-item-label">Mobile Number</label>
                    <input class="input-bordered" type="text" id="mobile-number" name="mobile-number">
                </div><!-- .input-item -->
            </div>
          </div><!-- .card-innr -->
          <div class="card-footer bg-white">

          </div>
        </div><!-- .card -->

      </div>
    </div>
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script>

</script>
@endpush

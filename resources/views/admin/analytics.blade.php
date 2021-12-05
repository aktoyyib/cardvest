@extends('layouts.app')

@section('title', 'Cardvest - Admin Dashboard')
@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="token-information card card-full-height">
                    <div class="token-info">
                        <i class="fa fa-calculator fa-2x"></i>
                        <div class="gaps-2x"></div>
                        <h5 class="token-info-sub">Total Balance by Users</h5>
                        <h1 class="token-info-head text-light">&#8358;{{ to_naira($totalHeld) }}</h1>
                    </div>
                </div><!-- .card -->
            </div><!-- .col -->
        </div><!-- .row -->
    </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script>

</script>
@endpush

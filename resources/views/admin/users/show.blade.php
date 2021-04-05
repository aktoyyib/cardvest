@extends('layouts.app')

@section('title', 'Cardvest - User')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 aside sidebar-left">
        @include('partials.admin_sidebar')
      </div>
      <div class="col-lg-9 main-content">
        <div class="card content-area">
          <div class="card-innr card-innr-fix">
            <div class="card-head d-flex justify-content-between align-items-center">
              <h4 class="card-title mb-0">User Details</h4>
              <div class="d-flex align-items-center guttar-20px">
                <div class="flex-col d-sm-block d-none">
                  <a href="{{ route('users.index') }}" class="btn btn-sm btn-auto btn-primary"><em
                      class="fas fa-arrow-left mr-3"></em>Back</a>
                </div>
                <div class="flex-col d-sm-none">
                  <a href="user-list.html" class="btn btn-icon btn-sm btn-primary"><em
                      class="fas fa-arrow-left"></em></a>
                </div>
                <div class="relative d-inline-block">
                  <a href="#" class="btn btn-dark btn-sm btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                  <div class="toggle-class dropdown-content dropdown-content-top-left">
                    <ul class="dropdown-list">
                      <li><a href="mailto:{{ $user->email }}"><em class="far fa-envelope"></em> Send Mail</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <h6 class="card-sub-title">User Information</h6>
            <ul class="data-details-list">
              <li>
                <div class="data-details-head">Full Name</div>
                <div class="data-details-des">{{ $user->username }}</div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">Email Address</div>
                <div class="data-details-des">{{ $user->email }}</div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">Mobile Number</div>
                <div class="data-details-des">{{ $user->phonenumber }}</div>
              </li><!-- li -->
            </ul>
            <div class="gaps-3x"></div>
            <h6 class="card-sub-title">More Information</h6>
            <ul class="data-details-list">
              <li>
                <div class="data-details-head">Joining Date</div>
                <div class="data-details-des">{{ $user->created_at }}</div>
              </li><!-- li -->
            </ul>

            <div class="gaps-3x"></div>
            <h6 class="card-sub-title">Transaction Information</h6>
            <ul class="data-details-list">
              <li>
                <div class="data-details-head">Balance</div>
                <div class="data-details-des">&#8358;{{ $user->balance() }}</div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">Total Sold</div>
                <div class="data-details-des">&#8358;{{ to_naira($user->getTotalSold()) }}</div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">Total Bought</div>
                <div class="data-details-des">&#8358;{{ to_naira($user->getTotalBought()) }}</div>
              </li><!-- li -->
            </ul>
          </div><!-- .card-innr -->
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
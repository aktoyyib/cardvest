@extends('layouts.app')

@section('title', 'Cardvest - Admin Dashboard')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <div class="token-statistics card card-token height-auto">
          <div class="card-innr">
            <div class="token-balance token-balance-with-icon">
              <div class="token-balance-icon">
                <!-- <img src="{{ asset('images/logo-sm.png') }}" alt="logo"> -->
                <span class="fa fa-users fa-2x"></span>
              </div>
              <div class="token-balance-text">
                <span class="lead"><span>{{ $users }}</span></span>
                <h6 class="card-sub-title"><a href="{{ route('users.index') }}">Users</a></h6>
              </div>
            </div>
          </div>
        </div>
      </div><!-- .col -->

      <div class="col-lg-4">
        <div class="token-statistics card card-token height-auto">
          <div class="card-innr">
            <div class="token-balance token-balance-with-icon">
              <div class="token-balance-icon">
                <span class="fa fa-history fa-2x"></span>
              </div>
              <div class="token-balance-text">
                <span class="lead"><span>{{ $transactions }}</span></span>
                <h6 class="card-sub-title"><a href="{{ route('transactions.index') }}">Transactions</a></h6>
              </div>
            </div>
          </div>
        </div>
      </div><!-- .col -->

      <div class="col-lg-4">
        <div class="token-statistics card card-token height-auto">
          <div class="card-innr">
            <div class="token-balance token-balance-with-icon">
              <div class="token-balance-icon">
                <span class="fa fa-credit-card fa-2x"></span>
              </div>
              <div class="token-balance-text">
                <span class="lead"><span>{{ $withdrawals }}</span></span>
                <h6 class="card-sub-title"><a href="{{ route('withdrawals.index') }}">Withdrawals</a></h6>
              </div>
            </div>
          </div>
        </div>
      </div><!-- .col -->

      <div class="col-lg-4">
        <div class="token-statistics card card-token height-auto">
          <div class="card-innr">
            <div class="token-balance token-balance-with-icon">
              <div class="token-balance-icon">
                <span class="fa fa-tags fa-2x"></span>
              </div>
              <div class="token-balance-text">
                <span class="lead"><span>{{ $categories }}</span></span>
                <h6 class="card-sub-title"><a href="{{ route('categories.index') }}">Card Categories</a></h6>
              </div>
            </div>
          </div>
        </div>
      </div><!-- .col -->


      <div class="col-lg-4">
        <div class="token-statistics card card-token height-auto">
          <div class="card-innr">
            <div class="token-balance token-balance-with-icon">
              <div class="token-balance-icon">
                <img src="{{ asset('images/logo-sm.png') }}" alt="logo">
              </div>
              <div class="token-balance-text">
                <span class="lead"><span>Payout</span></span>
                <h6 class="card-sub-title"><a href="{{ route('transactions.create') }}">Credit Users</a></h6>
              </div>
            </div>
          </div>
        </div>
      </div><!-- .col -->

      <div class="col-lg-4">
        <div class="token-statistics card card-token height-auto">
          <div class="card-innr">
            <div class="token-balance token-balance-with-icon">
              <div class="token-balance-icon">
                <img src="{{ asset('images/logo-sm.png') }}" alt="logo">
              </div>
              <div class="token-balance-text">
                <span class="lead"><span>Administrators</span></span>
                <h6 class="card-sub-title"><a href="{{ route('roles.index') }}">View Admins</a></h6>
              </div>
            </div>
          </div>
        </div>
      </div><!-- .col -->

      <div class="col-lg-4">
        <div class="token-statistics card card-token height-auto">
          <div class="card-innr">
            <div class="token-balance token-balance-with-icon">
              <div class="token-balance-icon">
                <span class="fa fa-bell fa-2x"></span>
              </div>
              <div class="token-balance-text">
                <span class="lead"><span>Push Notification Campaign</span></span>
                <h6 class="card-sub-title"><a href="{{ route('push-notification-campaign') }}">Create Campaign</a></h6>
              </div>
            </div>
          </div>
        </div>
      </div><!-- .col -->

      <div class="col-lg-4">
        <div class="token-statistics card card-token height-auto">
          <div class="card-innr">
            <div class="token-balance token-balance-with-icon">
              <div class="token-balance-icon">
                <span class="fa fa-chart-bar fa-2x"></span>
              </div>
              <div class="token-balance-text">
                <span class="lead"><span>Analytics</span></span>
                <h6 class="card-sub-title"><a href="{{ route('analytics') }}">View Stats</a></h6>
              </div>
            </div>
          </div>
        </div>
      </div><!-- .col -->

      <div class="col-lg-4">
        <div class="token-statistics card card-token height-auto">
          <div class="card-innr">
            <div class="token-balance token-balance-with-icon">
              <div class="token-balance-icon">
                <span class="fa fa-gear fa-2x"></span>
              </div>
              <div class="token-balance-text">
                <span class="lead"><span>Settings</span></span>
                <h6 class="card-sub-title"><a href="{{ route('settings') }}">Update Settings</a></h6>
              </div>
            </div>
          </div>
        </div>
      </div><!-- .col -->

    </div>

  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script>

</script>
@endpush

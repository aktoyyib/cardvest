@extends('layouts.app')

@section('title', 'Cardvest - Referrals')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="main-content col-lg-8">
        <div class="token-statistics card card-token height-auto">
          <div class="card-innr">
            <div class="token-balance token-balance-with-icon">
              <div class="token-balance-icon">
                <img src="images/logo-sm.png" alt="logo">
              </div>
              <div class="token-balance-text">
                <h6 class="card-sub-title">Wallet Balance</h6>
                <span class="lead">{{ to_naira($user->balance()) }} <span>NGN</span></span>
              </div>
            </div>
          </div>
        </div>

        <div class="content-area card">
          <div class="card-innr">
            <div class="card-head has-aside">
              <h4 class="card-title">List of your referrals</h4>
            </div>
            <table class="table tnx-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th class="d-none d-sm-table-cell tnx-date">User Referred</th>
                  <th class="tnx-type">
                    <div class="tnx-type-text"></div>
                  </th>
                </tr><!-- tr -->
              </thead><!-- thead -->
              <tbody>
                @forelse($details['referrals'] as $referral)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <span class="lead">{{ $referral->created_at }}</span>
                    </div>
                  </td>
                  <td class="d-sm-table-cell">
                    <span class="lead tnx-id">{{ ucfirst($referral->username) }}</span>
                  </td>
                  <td class="data-col" class="align-middle">
                    <i
                      class="fa fa-{{ $referral->referrer_settled ? 'check-circle text-success' : 'spinner text-info' }}"></i>
                    {{ $referral->referrer_settled ? '' : 'Pending'}}
                  </td>
                </tr><!-- tr -->
                @empty
                <tr class="data-item">
                  <td colspan="6" class="data-col dt-tnxno">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="fake-class text-center">
                        <p><span class="fa fa-history"></span> Share your referrral link to friends.</p>
                      </div>
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @endforelse
              </tbody><!-- tbody -->
            </table><!-- .table -->
          </div>
        </div><!-- .card -->


      </div><!-- .col -->
      <div class="aside sidebar-right col-lg-4">

        <div class="referral-info card">
          <div class="card-innr">
            <h6 class="card-title card-title-sm">Earn with Referral</h6>
            <p class=" pdb-0-5x">Invite your friends &amp; family and receive a <strong><span class="text-primary">bonus
                </span> of &#8358; 500.</strong></p>
            <div class="copy-wrap mgb-0-5x">
              <span class="copy-feedback"></span>
              <em class="fas fa-link"></em>
              <input type="text" class="copy-address" value="{{ $user->getReferralLinkAttribute() }}" disabled>
              <button class="copy-trigger copy-clipboard"
                data-clipboard-text="{{ $user->getReferralLinkAttribute() }}"><em class="ti ti-files"></em></button>
            </div><!-- .copy-wrap -->

            <div class="mt-3">
              <div class="jumbotron py-3 earning-box">
                <h4 class="text-secondary">&#8358;{{ to_naira($details['referral_bonus']) }}</h4>
                <p>My Referral Earnings</p>
              </div>
            </div>
          </div>
        </div>

      </div><!-- .col -->
    </div><!-- .container -->
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()
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
                <h6 class="card-sub-title">Main Balance</h6>
                <span class="lead">{{ to_naira($user->balance()) }} <span>{{ auth()->user()->wallet->currency }}</span></span>
              </div>
              <div class="token-pay-currency border-0">
                <a href="#" class="link ucap link-light toggle-tigger toggle-caret text-white">{{ auth()->user()->wallet->currency }}</a>
                <div class="toggle-class dropdown-content" style="right: -40%; left: unset;">
                    <ul class="dropdown-list">
                        @foreach($wallets as $wallet)
                        <li><a href="{{ route('wallet.set-currency') }}" onclick="event.preventDefault();
                                                document.getElementById('{{ $wallet->currency }}').submit();">{{ $wallet->currency }}</a></li>
                        <form id="{{ $wallet->currency }}" action="{{ route('wallet.set-currency') }}">
                          <input type="hidden" name="currency" value="{{ $wallet->currency }}">
                        </form>
                        @endforeach
                    </ul>
                </div>
              </div>
            </div>

            <div class="token-balance token-balance-s2">
              <hr class="bg-warning">
              <!-- <h6 class="card-sub-title">Your Wallets</h6> -->
              <ul class="token-balance-list justify-content-between">
                  <li class="token-balance-sub">
                      <span class="lead">2,646</span>
                      <span class="sub"><a href="{{ route('wallet.show', 'GHS') }}">&#8373; (CEDIS) <i class="fa fa-external-link"></i></a> </span>
                  </li>
                  <li class="token-balance-sub">
                      <span class="lead">1,265</span>
                      <span class="sub"><a href="{{ route('wallet.show', 'NGN') }}">&#8358; (NAIRA) <i class="fa fa-external-link"></i></a> </span>
                  </li>
              </ul>
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
                      class="fa fa-{{ $referral->referrer_settled ? 'check-circle text-success' : 'spinner text-info fa-spin' }}"></i>
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
                </span> of &#8358; 500</strong> when they initiate their first trade.</p>
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
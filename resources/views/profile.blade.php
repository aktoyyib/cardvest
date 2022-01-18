@extends('layouts.app')

@section('title', 'Cardvest - Personal Profile')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="main-content col-lg-8">
        @if (session('status') == 'two-factor-authentication-enabled') 
            <div class="alert alert-success">
              <p>Two factor authentication has been enabled.</p>
            </div>
        @endif
        @if (session('status') == 'success_msg') 
            <div class="alert alert-success">
              <p>Two factor authentication has been activated successfully.</p>
            </div>
        @endif
        <div class="token-statistics card card-token height-auto d-lg-none">
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
            <div class="card-head">
              <h4 class="card-title">Profile Details</h4>
            </div>
            <ul class="nav nav-tabs nav-tabs-line" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#personal-data">Personal Data</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#password">Password</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#security">Security</a>
              </li>
            </ul><!-- .nav-tabs-line -->
            <div class="tab-content" id="profile-details">
              <div class="tab-pane fade show active" id="personal-data">
                <form action="{{ route('user-profile-information.update') }}" method="post">
                  @csrf
                  @method('put')
                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-item input-with-label">
                        <label for="full-name" class="input-item-label">Username</label>
                        <input class="input-bordered" type="text" id="username" name="username"
                          value="{{ $user->username }}">
                      </div><!-- .input-item -->
                    </div>
                    <div class="col-md-6">
                      <div class="input-item input-with-label">
                        <label for="email-address" class="input-item-label">Email Address</label>
                        <input class="input-bordered" type="email" id="email" value="{{ $user->email }}" disabled>
                      </div><!-- .input-item -->
                    </div>
                    <input type="hidden" name="email" id="email" value="{{ $user->email }}">
                    <div class="col-md-6">
                      <div class="input-item input-with-label">
                        <label for="mobile-number" class="input-item-label">Mobile Number</label>
                        <input class="input-bordered" type="text" id="phonenumber" name="phonenumber"
                          value="{{ $user->phonenumber }}">
                      </div><!-- .input-item -->
                    </div>
                  </div><!-- .row -->
                  <div class="gaps-1x"></div><!-- 10px gap -->
                  <div class="d-sm-flex justify-content-between align-items-center">
                    <button class="btn btn-primary">Update Profile</button>
                    <div class="gaps-2x d-sm-none"></div>
                    <!-- <span class="text-success"><em class="ti ti-check-box"></em> All Changes are saved</span> -->
                  </div>
                </form><!-- form -->
              </div><!-- .tab-pane -->

              <div class="tab-pane fade" id="password">
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-item input-with-label">
                      <label for="old-pass" class="input-item-label">Old Password</label>
                      <input class="input-bordered" type="password" id="old-pass" name="old-pass">
                    </div><!-- .input-item -->
                  </div><!-- .col -->
                </div><!-- .row -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-item input-with-label">
                      <label for="new-pass" class="input-item-label">New Password</label>
                      <input class="input-bordered" type="password" id="new-pass" name="new-pass">
                    </div><!-- .input-item -->
                  </div><!-- .col -->
                  <div class="col-md-6">
                    <div class="input-item input-with-label">
                      <label for="confirm-pass" class="input-item-label">Confirm New Password</label>
                      <input class="input-bordered" type="password" id="confirm-pass" name="confirm-pass">
                    </div><!-- .input-item -->
                  </div><!-- .col -->
                </div><!-- .row -->
                <div class="note note-plane note-info pdb-1x">
                  <em class="fas fa-info-circle"></em>
                  <p>Password should be minmum 8 letter and include lower and uppercase letter.</p>
                </div>
                <div class="gaps-1x"></div><!-- 10px gap -->
                <div class="d-sm-flex justify-content-between align-items-center">
                  <button class="btn btn-primary">Update</button>
                  <div class="gaps-2x d-sm-none"></div>
                  <!-- <span class="text-success"><em class="ti ti-check-box"></em> Changed Password</span> -->
                </div>
              </div><!-- .tab-pane -->
              <div class="tab-pane fade" id="security"> 
                <h6 class="card-title card-title-sm">TWO FACTOR AUTHENTIICATION</h6> 
                
                <div class="mt-3">
                    @if(auth()->user()->two_factor_confirmed)
                    <h6 class="card-title card-title-sm">BACKUP CODES</h6>
                    <p class=" pdb-0-5x">Backup codes are useful if you lose access to the connected TFA app.</p> 
                    <div class="card-opt">
                      <a href="" class="link ucap">Backup Codes <em class="fas fa-angle-right ml-2"></em></a>
                      <ul>
                         @if(count(auth()->user()->recoveryCodes()) > 0)
                          @foreach(auth()->user()->recoveryCodes() as $backupCode)
                            <li class="mb-2">{{ $backupCode }} </li>
                          @endforeach
                         @endif 
                       </ul>
                    </div>
                    <div class="gaps-1x"></div><!-- 10px gap -->
                        <form action="{{ url('/user/two-factor-authentication') }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-primary">Disable 2FA</button>
                        </form>
                    <!-- 2FA enabled but not yet confirmed, we show the QRcode and ask for confirmation : -->
                    @elseif(auth()->user()->two_factor_secret)
                      <div class="d-sm-flex justify-content-between align-items-center">
                        <p class=" pdb-0-5x">TFA is not active on your account. <br>
                        Validate 2FA by scanning the following QRcode and entering the TOTP</p>  
                      </div>
                      <div class="d-sm-flex justify-content-between align-items-center">
                        {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        <form action="{{route('two-factor.confirm')}}" method="post">
                            @csrf
                            <input name="code" required/>
                            <button type="submit" class="btn btn-primary">Validate 2FA</button>
                        </form>
                      </div>
                    <!-- 2FA not enabled at all, we show an 'enable' button  : -->
                    @else 
                        <p class=" pdb-0-5x">TFA is not active on your account</p> 
                        <form action="{{ url('/user/two-factor-authentication') }}" method="post">
                            @csrf 
                            <button type="submit" class="btn btn-primary">Enable 2FA</button>
                        </form>
                    @endif 
                    <div class="gaps-2x d-sm-none"></div> 
                  </div> 
              </div><!-- .tab-pane -->
            </div><!-- .tab-content -->
          </div><!-- .card-innr -->
        </div><!-- .card -->


      </div><!-- .col -->
      <div class="aside sidebar-right col-lg-4">
        <div class="token-statistics card card-token height-auto d-none d-lg-block">
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

        <div class="card">
          <div class="card-innr">
            <div class="card-head has-aside">
              <h4 class="card-title">Wallet Notifications</h4>
            </div>
            <table class="table tnx-table">
              <thead>
                <tr>
                  <th>Amount</th>
                  <th class="d-none d-sm-table-cell tnx-date">Date</th>
                  <th class="tnx-type">
                    <div class="tnx-type-text"></div>
                  </th>
                </tr><!-- tr -->
              </thead><!-- thead -->
              <tbody>
                @forelse($payouts as $payout)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <span class="lead">{!! cur_symbol($payout->currency) !!} {{ to_naira($payout->amount) }}</span>
                    </div>
                  </td>
                  <td class="d-none d-sm-table-cell tnx-date">
                    <div class="d-flex flex-column">
                      <span class="lead tnx-id">{{ $payout->getDate() }}</span>
                      <span class="sub sub-s2">{{ $payout->getTime() }}</span>
                    </div>
                  </td>
                  <td class="data-col">
                    <a href="{{ route('transaction.show', $payout) }}"><span
                        class="dt-type-md badge badge-primary badge-md text-capitalize">View</span></a>
                  </td>
                </tr><!-- tr -->
                @empty
                <tr class="data-item">
                  <td colspan="6" class="data-col dt-tnxno">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="fake-class text-center">
                        <span class="lead tnx-id text-danger">Empty!</span>
                      </div>
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @endforelse
              </tbody><!-- tbody -->
            </table><!-- .table -->
          </div>
        </div>

      </div><!-- .col -->
    </div><!-- .container -->
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()
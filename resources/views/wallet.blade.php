@extends('layouts.app')

@section('title', 'Cardvest - User\'s Wallet')
@section('content')
<div class="page-content">
  <div class="container">


    <div class="row">
      <div class="col-lg-4">
        <div class="token-statistics card card-token height-auto">
            <div class="card-innr">
              <div class="token-balance token-balance-with-icon">
                <div class="token-balance-icon">
                  <img src="{{ asset('images/logo-sm.png')}}" alt="logo">
                </div>
                <div class="token-balance-text">
                  <h6 class="card-sub-title">Main Balance</h6>
                  <span class="lead">{{ to_naira($wallet->balance) }} <span>{{ $wallet->currency }}</span></span>
                </div>
              </div>
            </div>
          </div>

        <div class="token-calculator card height-auto">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Withdraw Funds</h4>
            </div>
            <form action="{{ route('withdraw') }}" method="post">
              @csrf
              <input type="hidden" name="currency" value="{{ $wallet->currency }}">
              <div class="input-item input-with-label">
                <label class="input-item-label">Enter amount to withdraw</label>
                <input class="input-bordered " type="number" min="0" step="0.01" name="amount"
                  placeholder="Amount" required>
              </div>
              <div class="input-item input-with-label">
                <label class="input-item-label">Select Banking Account</label>
                <div class="select-wrapper">
                  <select class="select select-block select-bordered" name="bank" required>
                    <option>Select</option>
                    @forelse($wallet->bank_accounts as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->bankname }} - {{$bank->banknumber}}</option>
                    @empty
                    <option>---</option>
                    @endforelse
                  </select>
                </div>
              </div>
              @if ($wallet->bank_accounts->count() === 0)
              <small class="text-danger">Add Bank account to enable withdrawal</small>
              @endif
              <div class="token-buy text-center">
                <button class="btn btn-primary"
                  {{ $wallet->bank_accounts->count() === 0 ? 'disabled' : '' }}>Proceed</button>
              </div>
            </form>
          </div>
        </div>

      </div>

      <div class="col-lg-8">
        <div class="token-transaction card card-full-height">
          <div class="card-innr">
            <div class="card-head has-aside">
              <h4 class="card-title">Withdrawal History</h4>

            </div>
            <table class="data-table user-tnx">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-tnxno">Bank Details</th>
                  <th class="data-col dt-token">Amount <small>{{ $wallet->currency }}</small></th>
                  <th class="data-col dt-amount">Date</th>
                  <th class="data-col"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($withdrawals as $withdrawal)
                <tr class="data-item">
                  <td class="data-col dt-tnxno">
                    <div class="d-flex align-items-center">
                      <div class="fake-class">
                        <span class="lead tnx-id">{{ $withdrawal->bank->bankname }}</span>
                        <span class="sub sub-date">{{ $withdrawal->bank->banknumber }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="data-col dt-token">
                    <span class="lead token-amount">{{ to_naira($withdrawal->amount) }}</span>
                    <span class="sub sub-symbol">{{ to_naira($withdrawal->amount) }} <small>({{ $wallet->currency }})</small></span>
                  </td>
                  <td class="dt-type-md data-col dt-amount">
                    <span class="lead amount-pay">{{ $withdrawal->getDate() }}</span>
                    <span class="sub sub-symbol">{{ $withdrawal->getTime() }}</span>
                  </td>
                  <td class="data-col dt-type">
                    <span
                      class="badge badge-{{ $withdrawal->getStatus() }} badge-md text-capitalize">{{ $withdrawal->status }}</span>
                  </td>
                </tr><!-- .data-item -->
                @empty
                <tr class="data-item">
                  <td class="data-col dt-tnxno" colspan="4">
                    <div class="alert alert-danger text-center">
                      No Withdrawals
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @endforelse
              </tbody>
            </table>

          </div>
          <div class="card-footer bg-white text-center my-2">

          </div>
        </div>
      </div>

    </div><!-- .row -->

    <div class="row">

      <div class="col-lg-4">

      </div>


    </div><!-- .row -->

    <div class="row">
      <div class="col-lg-5">
        <div class="token-transaction card card-full-height">
          <div class="card-innr">
            <div class="card-head has-aside">
              <h4 class="card-title">Bank Accounts</h4>
            </div>
            <table class="data-table user-tnx">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-tnxno">Bank Name</th>
                  <th class="data-col dt-token">Account Number </th>
                  <th class="data-col dt-amount">Account Name</th>
                  <th class="data-col"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($banks as $bank)
                <tr class="data-item">
                  <td class="data-col dt-tnxno">
                    <div class="d-flex align-items-center">
                      <div class="fake-class">
                        <span class="lead tnx-id">{{ $bank->bankname }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="data-col dt-token">
                    <span class="lead token-amount">{{ $bank->banknumber }}</span>
                  </td>
                  <td class="data-col dt-amount">
                    <span class="lead amount-pay">{{ $bank->accountname }}</span>
                  </td>
                  <td class="data-col dt-type">
                    <form action="{{ route('wallet.removebank', $bank) }}" method="post"
                      id="remove-bank-{{ $bank->id }}">
                      @csrf
                      @method('delete')
                    </form>
                    <a href="{{ route('wallet.removebank', $bank) }}"
                      onclick="event.preventDefault();document.getElementById('remove-bank-{{ $bank->id }}').submit()"
                      class="btn btn-danger-alt btn-xs btn-icon"><em class="ti ti-trash"></em></a>
                  </td>
                </tr><!-- .data-item -->
                @empty
                <tr class="data-item">
                  <td class="data-col dt-tnxno" colspan="4">
                    <div class="alert alert-danger text-center">
                      You have no active bank account.
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @endforelse

              </tbody>
            </table>

          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="token-calculator card card-full-height">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Add Bank Account</h4>
            </div>
            @if($banks->count() < 3) <form action="{{ route('wallet.store') }}" method="post">
              @csrf
              <div class="input-item input-with-label">
                <label class="input-item-label">Account Number</label>
                <input class="input-bordered " type="text" id="banknumber" name="banknumber"
                  placeholder="Acc No">
              </div>
              <div class="input-item input-with-label">
                <label class="input-item-label">Select Bank Account <span class="fa fa-spinner fa-spin" id="bank-loader"
                    style="diplay: none;"></span></label>
                <div class="select-wrapper">
                  <select class="input-bordered" name="bankname" id="bank-list">

                  </select>
                </div>
              </div>
              <input type="hidden" name="accountname" id="accountname">
              <input type="hidden" name="code" id="bank-code">
              <span class="fa fa-spinner fa-spin" id="verify-loader" style="diplay: none;"></span>

              <div class="d-block">
                <button type="button" class="btn btn-primary btn-block" id="verify-account">Check</button>
              </div>
              <div class="input-item input-with-label">
                <label class="input-item-label">Account Name</label>
                <input class="input-bordered" type="text" id="account-name" placeholder="Acc Name"
                  disabled>
              </div>
              <div class="token-buy d-block">
                <button type="submit" class="btn btn-primary btn-block" id="add-account" disabled>Add Account</button>
              </div>
              </form>
              @endif
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="token-sales card card-full-height">
          <div class="card-innr">
            <a href="https://app.cardvest.ng/referrals" target="_blank">
              <center><img src="{{ asset('images/ads-banner.jpg') }}"></center>
            </a>
          </div>
        </div>
      </div>
    </div><!-- .row -->


  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$(document).ready(function() {

  // Functions

  let loadBanks = function(banks) {
    var options = '<option value="null">Select Bank</option>'

    banks.forEach(bank => {
      options += '<option value=\'' + JSON.stringify(bank) + '\'>' + bank.name + '</option>'
    });

    $('#bank-list').append(options);
    $('#bank-list').prop('disabled', false);

    $('#bank-loader').hide()
  }

  let verifyAccount = function(acc_num, bnk_name) {
    verifyloader.show();

    $.post("{{ route('verify') }}", {
        'banknumber': acc_num,
        'bankname': bnk_name
      },
      function(data, status) {
        verifyloader.hide();
        data = JSON.parse(data)

        if (data.status === 'success') {
          data = data.data
          displayName.val(data.account_name)
          accountNameInput.val(data.account_name)
          submitAccount.prop('disabled', false)
        } else {
          displayName.val(data.message)
          accountNameInput.val('')
          accountNameInput.prop('disabled', true)
          submitAccount.prop('disabled', true)
        }
      })
  }

  let widgetHydration = function() {
    if (wallet.currency == "GHS") {
      verify.hide();
      let inputName = accountNameInput.attr('name');
      // Remove name attribute from accountNameInput
      accountNameInput.attr('name', null);
      // Give the name attribute to displayName
      displayName.attr('name', inputName);
      // Enable displayName to allow user type account name
      displayName.prop('disabled', false)
      submitAccount.prop('disabled', false)
    }
  }


  let wallet = @json($wallet);

  // Data and Logics
  $('#bank-loader').show()
  $('#bank-list').prop('disabled', true);

  $.get(
    "{{ route('banks') }}",
    { currency: wallet.currency },
    function(data, status) {
        // var data = data.json()

        if (data.status === 'success') {
        loadBanks(data.data)
        }
    }
  )


  // Inputs for the banking details
  let banknumber = $('#banknumber');
  let bankname = $('#bank-list');
  let accountNameInput = $('#accountname'); // Auto filled for Naira Wallet

  let verify = $('#verify-account');
  let verifyloader = $('#verify-loader');
  let displayName = $('#account-name');
  let submitAccount = $('#add-account');
  verifyloader.hide();
  widgetHydration();

  verify.click(function(event) {
    // !banknumber.val() || !bankname.val()
    if (!banknumber.val() || (!bankname.val() || bankname.val() === 'null')) {
      return
    }
    var bank = JSON.parse(bankname.val())
    verifyAccount(banknumber.val(), bank.code)
  })

});

</script>
@endpush

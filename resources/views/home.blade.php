@extends('layouts.app')

@section('title', 'Cardvest - User\'sDashboard')
@section('content')
<div class="page-content">
  <div class="container">


    <div class="row">
      <div class="col-lg-4">
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
                  @foreach ($wallets as $wallet)
                  <li class="token-balance-sub">
                      <span class="lead">{{ to_naira($wallet->balance) }}</span>
                      <span class="sub"><a href="{{ route('wallet.show', $wallet->currency) }}">{!! cur_symbol($wallet->currency) !!} <span class="text-uppercase">({{ $wallet->name }}) </span> <i class="fa fa-external-link"></i></a> </span>
                  </li>
                  @endforeach
              </ul>
            </div>
          </div>
        </div>

        <div class="token-transaction card d-none d-lg-block" >
          <div class="card-innr">
            <div class="card-head has-aside">
              <h4 class="card-title">Transaction History</h4>
              <div class="card-opt">
                <a href="{{ route('transaction.index') }}" class="link ucap">View ALL <em
                    class="fas fa-angle-right ml-2"></em></a>
              </div>
            </div>
            <table class="data-table user-tnx">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-amount">Amount</th>
                  <th class="data-col dt-type">
                    <div class="dt-type-text">Type</div>
                  </th>
                  <th class="data-col">Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse($transactions as $transaction)
                <tr class="data-item">
                  <td class="data-col dt-token">
                    <div class="d-flex align-items-center">
                      <div class="data-state data-state-{{ $transaction->getStatus() }}">
                        <span class="d-none">Pending</span>
                      </div>
                      <div>
                        <span class="lead token-amount">{{ to_naira($transaction->amount) }}</span>
                        <span class="sub sub-symbol">{{ $transaction->currency }}</span>
                      </div>
                  </td>
                  <td class="data-col dt-type">
                    <span
                      class="dt-type-md badge badge-outline badge-{{ $transaction->getDescription() }} badge-md text-capitalize">{{ $transaction->type }}</span>
                  </td>
                  <td class="data-col">
                    <a href="{{ route('transaction.show', $transaction) }}"><span
                        class="dt-type-md badge badge-primary badge-md text-capitalize">View</span></a>
                  </td>
                </tr><!-- .data-item -->
                @empty
                <tr class="data-item">
                  <td class="data-col dt-tnxno" colspan="4">
                    <div class="alert alert-danger text-center">
                      Empty Transactions!
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @endforelse
              </tbody>
            </table>

          </div>
        </div>
      </div>

      <div class="col-xl-8">
        <div class="content-area card card-full-height">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Rate Calculator</h4>
            </div>
            <ul class="nav nav-tabs nav-tabs-line" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#sell-card">Sell Card</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#buy-card">Buy Card</a>
              </li>
            </ul><!-- .nav-tabs-line -->
            <div class="tab-content" id="profile-details">
              <div class="tab-pane fade show active" id="sell-card">
                <div class="card-text mb-2">
                  <p>Get the current value for your transaction</p>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-item input-with-label">
                      <label class="input-item-label">Gift Card Category</label>
                      <select class="select select-block select-bordered" id="gift-card-category">
                        <option value="">Select</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="input-item input-with-label">
                      <label class="input-item-label">Gift Card</label>
                      <select class="select select-block select-bordered" id="gift-card">
                      </select>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="input-item input-with-label">
                      <label class="input-item-label">Amount</label>
                      <input class="input-bordered" type="number" id="gift-card-amount" placeholder="Amount in USD">
                    </div>

                    <span>
                      <div class="input-item input-with-label">
                        <label class="input-item-label">Currency</label>
                        <div class="select-wrapper">
                          <select class="select select-block select-bordered" id="currency" name="currency">
                            @foreach($wallets as $wallet)
                            <option value="{{ $wallet->currency }}" {{ $wallet->isDefault ? 'selected' : '' }}>{{ $wallet->name }} ({!! cur_symbol($wallet->currency) !!})</option>
                            @endforeach
                          </select>
                        </div>
                        <span class="error"></span>
                      </div>
                    </span>

                    <div class="token-overview-wrap mt-0">
                      <div class="token-overview">
                        <div class="row">
                          <div class="col">
                            <div class="token-bonus-sale pt-1 mb-0">
                              <span class="token-overview-title"> = </span>
                              <span class="token-overview-value bonus-on-sale" id="gift-card-equiv">0.00</span>
                            </div>
                            <div class="token-bonus token-bonus-sale pt-1" id="sell-payout-box" style="display: none;">
                              <span class="token-overview-value bonus-on-sale">(<span id="sell-payout">0.00</span>)</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- <div class="note note-plane note-danger note-sm pdt-1x pl-0">
                    <p>Your Contribution will be calculated based on exchange rate at the moment your transaction is
                      confirm.</p>
                  </div> -->
                    </div>
                  </div>
                </div>
              </div><!-- .tab-pane -->

              <div class="tab-pane fade" id="buy-card">

                <div class="card-text mb-2">
                  <p>Get the current value for your transaction</p>
                </div>
                <div class="row">
                  <div class="col-md-6">

                    <div class="input-item input-with-label">
                      <label class="input-item-label">Gift Card</label>
                      <select class="select select-block select-bordered" id="buy-gift-card">
                        <option value="">Select</option>
                        @foreach($cardsToBuy as $card)
                        <option value="{{ $card->id }}" data-rate="{{ $card->rate }}">
                          {{ $card->name." - ".$card->rate."/$" }}
                        </option>
                        @endforeach
                      </select>
                    </div>

                    <div class="input-item input-with-label">
                      <label class="input-item-label">Amount</label>
                      <input class="input-bordered" type="number" id="buy-gift-card-amount"
                        placeholder="Amount in USD">
                    </div>

                  </div>
                  <div class="col-md-6">

                    <span>
                      <div class="input-item input-with-label">
                        <label class="input-item-label">Currency</label>
                        <div class="select-wrapper">
                          <select class="select select-block select-bordered" id="currency-buy" name="currency">
                            @foreach($wallets as $wallet)
                            <option value="{{ $wallet->currency }}" {{ $wallet->isDefault ? 'selected' : '' }}>{{ $wallet->name }} ({!! cur_symbol($wallet->currency) !!})</option>
                            @endforeach
                          </select>
                        </div>
                        <span class="error"></span>
                      </div>
                    </span>

                    <div class="token-overview-wrap mt-0">
                      <div class="token-overview">
                        <div class="row">
                          <div class="col">
                            <div class="token-bonus-sale pt-1 mb-0">
                              <span class="token-overview-title"> = </span>
                              <span class="token-overview-value bonus-on-sale" id="buy-gift-card-equiv">0.00</span>
                            </div>
                            <div class="token-bonus token-bonus-sale pt-1" id="buy-payout-box" style="display: none;">
                              <span class="token-overview-value bonus-on-sale">(<span id="buy-payout">0.00</span>)</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- <div class="note note-plane note-danger note-sm pdt-1x pl-0">
                    <p>Your Contribution will be calculated based on exchange rate at the moment your transaction is
                      confirm.</p>
                  </div> -->
                    </div>
                  </div>
                </div>

              </div><!-- .tab-pane -->
            </div><!-- .tab-content -->
            <div class="card-footer bg-white">
              <a class="btn btn-primary" href="{{ route('transaction.create') }}">Proceed to Trade Card</a>
            </div>
          </div><!-- .card-innr -->
        </div><!-- .card -->
      </div>
    </div><!-- .row -->

    <div class="row">
      <div class="col d-lg-none">
        <div class="token-transaction card card-full-height">
          <div class="card-innr">
            <div class="card-head has-aside">
              <h4 class="card-title">Transaction History</h4>
              <div class="card-opt">
                <a href="{{ route('transaction.index') }}" class="link ucap">View ALL <em
                    class="fas fa-angle-right ml-2"></em></a>
              </div>
            </div>
            <table class="data-table user-tnx">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-amount">Amount</th>
                  <th class="data-col dt-type">
                    <div class="dt-type-text">Type</div>
                  </th>
                  <th class="data-col">Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse($transactions as $transaction)
                <tr class="data-item">
                  <td class="data-col dt-token">
                    <span class="lead token-amount">{{ to_naira($transaction->amount) }}</span>
                    <span class="sub sub-symbol">NGN</span>
                  </td>
                  <td class="data-col dt-type">
                    <span
                      class="badge badge-outline badge-{{ $transaction->getDescription() }} badge-md text-capitalize">{{ $transaction->type }}</span>
                  </td>
                  <td class="data-col">
                    <span
                      class="badge badge-{{ $transaction->getDescription($transaction->status) }} badge-md text-capitalize">{{ $transaction->status }}</span>

                    <a href="{{ route('transaction.show', $transaction) }}"><span
                        class="badge badge-primary badge-md text-capitalize">View</span></a>
                  </td>
                </tr><!-- .data-item -->
                @empty
                <tr class="data-item">
                  <td class="data-col dt-tnxno" colspan="4">
                    <div class="alert alert-danger text-center">
                      Empty Transactions!
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @endforelse
              </tbody>
            </table>

          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="token-calculator card card-full-height">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Withdraw Funds</h4>
            </div>
            <form action="{{ route('withdraw') }}" method="post">
              @csrf
              <div class="input-item input-with-label">
                <label class="input-item-label">Select Wallet</label>
                <div class="select-wrapper">
                  <select class="select select-block select-bordered" id="withdrawal-wallet" name="currency" required>
                    @foreach($wallets as $wallet)
                    <option value="{{ $wallet->currency }}" {{ $wallet->isDefault ? 'selected' : '' }}>{{ $wallet->name }} ({!! cur_symbol($wallet->currency) !!} {{ to_naira($wallet->balance) }})</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="input-item input-with-label">
                <label class="input-item-label">Enter amount to withdraw</label>
                <input class="input-bordered" type="number" min="0" step="0.01" name="amount"
                  placeholder="Amount" required>
              </div>
              <div class="input-item input-with-label">
                <label class="input-item-label">Select Banking Account</label>
                <div class="select-wrapper">
                  <select class="select select-block select-bordered" name="bank" id="withdrawal-banks" required>
                    <option value="">Select</option>
                    @foreach($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->bankname }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              @if ($user->wallet->bank_accounts->count() === 0)
              <small class="text-danger">Add Bank account to enable withdrawal</small>
              @endif
              <div class="token-buy text-center">
                <button class="btn btn-primary"
                  {{ $user->wallet->bank_accounts->count() === 0 ? 'disabled' : '' }}>Proceed</button>
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
                  <th class="data-col dt-token">Amount</th>
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
                    <span class="sub sub-symbol">{{ to_naira($withdrawal->amount) }} <small>({{ $withdrawal->currency }})</small></span>
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
            {{ $withdrawals->links() }}
          </div>
        </div>
      </div>
    </div><!-- .row -->

    <div class="row">
      <div class="col-lg-8">
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
  // $('#bank-loader').show()
  // $('#bank-list').prop('disabled', true);

  // $.get("{{ route('banks') }}", function(data, status) {
  //   // var data = data.json()

  //   if (data.status === 'success') {
  //     loadBanks(data.data)
  //   }
  // })

  // let banknumber = $('#banknumber')
  // let bankname = $('#bank-list')
  // let verify = $('#verify-account')

  // verify.click(function(event) {
  //   // !banknumber.val() || !bankname.val()
  //   if (!banknumber.val() || (!bankname.val() || bankname.val() === 'null')) {
  //     return
  //   }
  //   var bank = JSON.parse(bankname.val())
  //   verifyAccount(banknumber.val(), bank.code)
  // })

  // *************************** //
  // Rate Calculator widget begins here
  // ============================== //
  let sellTotal = 0;
  let buyTotal = 0;
  let categories = @json($categories);
  let cardBox = $('#gift-card');
  let cardCategoryBox = $('#gift-card-category');
  let amountBox = $('#gift-card-amount');
  let totalBox = $('#gift-card-equiv');

  let cardBuyBox = $('#buy-gift-card');
  let amountBuyBox = $('#buy-gift-card-amount');
  amountBuyBox.prop('disabled', true)
  let totalBuyBox = $('#buy-gift-card-equiv');
  const CEDIS_RATE = @json(App\Models\Setting::where('key', 'cedis_to_naira')->first()).value;

  let currentCategory = null

  amountBox.attr('disabled', true)

  cardCategoryBox.change(function() {
    var id = this.value
    loadCard(categories, id)
  });

  amountBox.keyup(function() {
    var amt = Number(this.value)
    var id = cardBox.val()

    var curCard = currentCategory.cards.find((card) => {
      return card.id === Number(id)
    })
    // console.log(curCard);
    var rate = curCard.rate
    sellTotal = calculateRate(amt, rate)
  });

  let loadCard = function(categories, id) {
    if (id === undefined || id < 1 || id === '') {
      return
    }

    // Find the category in the categories array
    currentCategory = categories.find((cat) => {
      return cat.id === Number(id)
    })



    var cardOptions = `<option value="">Select</option>`
    // Load the category cards into the card select input
    currentCategory.cards.forEach((card) => {
      cardOptions += `<option value="${card.id}">${card.name} - (${card.rate}/$)</option>`
    })

    cardBox.empty().append(cardOptions)
    amountBox.val("")
    amountBox.attr('disabled', false)
    // console.log(cardOptions)
  }

  // For the BUy Card
  // ********************************* //
  var buyRate = undefined

  cardBuyBox.change(function() {
    var selected = $(this).find('option:selected')
    buyRate = Number(selected.data('rate'))
    amountBuyBox.prop('disabled', false)

    // console.log(buyRate)
  })

  amountBuyBox.keyup(function() {
    if (buyRate == undefined) return
    var amount = this.value

    buyTotal = calculateRate(amount, buyRate, totalBuyBox, tradetype = 'buy')
  })

  // ***********************
  // Wallet Feature

  // Wallets
  let wallets = @json($wallets);
  // Wallet inputs
  let currencyInput = $('#currency'); // For Card Sale
  let buyCurrencyInput = $('#currency-buy'); // For Card Buying

  let buildSelect = function(banks, key = 'id', value = 'bankname') {
    var selectOptions = `<option value="">Select</option>`
    // Load the category cards into the card select input
    banks.forEach((bank) => {
      selectOptions += `<option value="${bank[key]}">${bank[value]}</option>`
    })

    return selectOptions;
  }


  // FOr Sell CaRD
  currencyInput.change(function() {
    let _currency = this.value;


    if (_currency !== 'NGN') {
      showOtherPayout(sellTotal, true);
    } else {
      showOtherPayout(sellTotal, false);
    }
  })


  // For Buy Card
  buyCurrencyInput.change(function() {
    let _currency = this.value;

    if (_currency !== 'NGN') {
      showOtherPayout(buyTotal, true, type = 'buy');
    } else {
      showOtherPayout(buyTotal, false, type = 'buy');
    }
  })

  // ******************************** //

  let calculateRate = function(amount, rate, element = null, tradetype = 'sell') {
    // console.log(rate)
    var total = Number(amount) * rate

    // create a formatter
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'NGN'
    })

    if (element === null)
      totalBox.text(formatter.format(total))
    else
      element.text(formatter.format(total))

    // create a formatter
    let cedisFormatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'GHS'
    })
    // If currency is not NGN
    $(`#${tradetype}-payout`).text(cedisFormatter.format(total / CEDIS_RATE));

    return total;
  }


  let showOtherPayout = function(amount, display = false, type = 'sell') {
    if (!display) return $(`#${type}-payout-box`).hide();
    $(`#${type}-payout-box`).show();
  }

  // *************************** //
  // Rate Calculator widget Ends here
  // ============================== //

  // *************************** //
  // Withdrawal Widget Begins Here
  // ============================== //
  let withdrawalWalletInput = $('#withdrawal-wallet');
  let withdrawalBanksInput = $('#withdrawal-banks');

  withdrawalWalletInput.change(function() {
    let _wallet = wallets.find(w => w.currency === this.value);
    let _banks = _wallet.bank_accounts;
    let _bankOptions = buildSelect(_banks);
    withdrawalBanksInput.empty().append(_bankOptions);
  });

});

// let loadBanks = function(banks) {
//   var options = '<option value="null">Select Bank</option>'

//   banks.forEach(bank => {
//     options += '<option value=\'' + JSON.stringify(bank) + '\'>' + bank.name + '</option>'
//   });

//   $('#bank-list').append(options);
//   $('#bank-list').prop('disabled', false);

//   $('#bank-loader').hide()
// }

// let verifyloader = $('#verify-loader');
// let accountNameInput = $('#accountname');
// let displayName = $('#account-name');
// let codeInput = $('#bank-code');
// let submitAccount = $('#add-account');
// verifyloader.hide();

// let verifyAccount = function(acc_num, bnk_name) {
//   verifyloader.show();

//   $.post("{{ route('verify') }}", {
//       'banknumber': acc_num,
//       'bankname': bnk_name
//     },
//     function(data, status) {
//       verifyloader.hide();
//       data = JSON.parse(data)

//       if (data.status === 'success') {
//         data = data.data
//         displayName.val(data.account_name)
//         accountNameInput.val(data.account_name)
//         submitAccount.prop('disabled', false)
//       } else {
//         displayName.val(data.message)
//         accountNameInput.val('')
//         accountNameInput.prop('disabled', true)
//         submitAccount.prop('disabled', true)
//       }
//     })
// }
</script>
@endpush

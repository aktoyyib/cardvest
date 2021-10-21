@extends('layouts.app')

@section('title', 'Cardvest - Personal Profile')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="main-content col-lg-8">
        <div class="content-area card">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Place an Order</h4>
            </div>
            <ul class="nav nav-tabs nav-tabs-line" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#sell-card" id="sell-toggle">Sell Card</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#buy-card" id="buy-toggle">Buy Card</a>
              </li>
            </ul><!-- .nav-tabs-line -->
            <div class="tab-content" id="profile-details">
              <div class="tab-pane fade show active" id="sell-card">
                <div class="card-head">
                  <h4 class="card-title">Trade Details</h4>
                </div>
                <div class="card-text">
                  <p>Provide more information needed to complete your trade.</p>
                </div>
                <form action="{{ route('transaction.store') }}" method="post" id="sell-form"
                  enctype="multipart/form-data">
                  @csrf
                  <div class="gaps-1x"></div>
                  <div class="row">
                    <div class="col-md-6">
                      <span>
                        <div class="input-item input-with-label">
                          <label class="input-item-label">Category</label>
                          <div class="select-wrapper">
                            <select class="select select-block select-bordered" name="category" id="gift-card-category"
                              required>
                              <option value="">Select</option>
                              @foreach($categories as $category)
                              <option value="{{ $category->id }}">{{ $category->name }}</option>
                              @endforeach
                            </select>
                          </div>
                          <span class="error"></span>
                        </div>
                      </span>
                    </div>
                    <div class="col-md-6">
                      <span>
                        <div class="input-item input-with-label">
                          <label class="input-item-label">Gift Card</label>
                          <div class="select-wrapper">
                            <select name="card_id" id="gift-card" class="select select-block select-bordered" required>
                              <option>Select</option>
                            </select>
                          </div>
                          <span class="error"></span>
                        </div>
                      </span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <span>
                        <div class="input-item input-with-label">
                          <label class="input-item-label">Amount</label>
                          <input type="number" id="gift-card-amount" placeholder="Enter amount" inputmode="numeric"
                            name="amount" disabled="disabled" required class="input-bordered" autocomplete="off">
                          <span class="error"></span>
                        </div>
                      </span>
                    </div>

                    <input type="hidden" id="images" multiple="multiple" name="images">

                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <span>
                        <div class="input-item input-with-label">
                          <label class="input-item-label">Select Wallet ()</label>
                          <div class="select-wrapper">
                            <select class="select select-block select-bordered" id="currency" name="currency">
                              @foreach($wallets as $wallet)
                              <option value="{{ $wallet->currency }}">{{ $wallet->name }} - {!! cur_symbol($wallet->currency) !!} {{ to_naira($wallet->balance) }}</option>
                              @endforeach
                            </select>
                          </div>
                          <span class="error"></span>
                        </div>
                      </span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      
                      <div class="input-item">
                        <input type="checkbox" name="to_bank" id="bank_account_direct"
                          class="input-checkbox input-checkbox-md">
                        <label for="bank_account_direct">
                          Send directly to my bank account
                        </label>
                      </div>
                    </div>

                    <div class="col-md-6" id="banks" style="display: none;">
                      <span>
                        <div class="input-item input-with-label">
                          <label class="input-item-label">Bank Account</label>
                          <div class="select-wrapper">
                            <select class="select select-block select-bordered" id="bank" disabled name="bank">
                              <option value="">Select</option>
                              @foreach($banks as $bank)
                              <option value="{{ $bank->id }}">{{ $bank->bankname }}</option>
                              @endforeach
                            </select>
                          </div>
                          <span class="error"></span>
                        </div>
                      </span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-item input-with-label">
                        <label class="input-item-label">Calculated Amount</label>
                        <div class="content-area card border-primary ">
                          <div class="card-header">
                            <p id="card-name" class="font-weight-bold"></p>
                          </div>
                          <ul class="list-group list-group-flush">
                            <!---->
                            <li class="list-group-item"><strong>Total:</strong> <span
                                class="float-right text-primary font-weight-bold" id="gift-card-equiv">NGN 0.00</span>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-item input-with-label">
                        <label class="input-item-label">Comments (optional)</label>
                        <textarea placeholder="Comments" name="comment"
                          class="input-bordered input-textarea"></textarea>
                      </div>
                    </div>
                  </div>

                </form>

                <div class="row">
                  <div class="col-md-12">
                    <div class="input-item input-with-label">
                      <label class="input-item-label">Gift Card Image <span class="text-danger">*</span> </label>
                      <div id="gift-card-shot">
                        <div class="dz-message" data-dz-message>
                          <span class="dz-message-text">Drag and drop file</span>
                          <span class="dz-message-or">or</span>
                          <button class="btn btn-sm btn-primary">Choose a File</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- .tab-pane -->

              <div class="tab-pane fade" id="buy-card">
                <div class="card-head">
                  <h4 class="card-title">Trade Details</h4>
                </div>
                <div class="card-text">
                  <p>Provide more information needed to complete your trade.</p>
                </div>
                <form action="{{ route('transaction.buy') }}" method="post" id="buy-form" enctype="multipart/form-data">
                  @csrf
                  <div class="gaps-1x"></div>
                  <div class="row">
                    <div class="col-md-6">
                      <span>
                        <div class="input-item input-with-label">
                          <label class="input-item-label">Gift Card</label>
                          <div class="select-wrapper">
                            <select name="card_id" id="buy-gift-card" class="select select-block select-bordered"
                              required>
                              <option>Select</option>
                              @foreach($cardsToBuy as $card)
                              <option value="{{ $card->id }}" data-card="{{ $card }}" data-rate="{{ $card->rate }}">
                                {{ $card->name." - ".$card->rate."/$" }}</option>
                              @endforeach
                            </select>
                          </div>
                          <span class="error"></span>
                        </div>
                      </span>
                    </div>

                    <div class="col-md-6">
                      <span>
                        <div class="input-item input-with-label"><label class="input-item-label">Amount</label>
                          <input type="number" id="buy-gift-card-amount" placeholder="Enter amount" inputmode="numeric"
                            name="amount" disabled="disabled" required class="input-bordered"> <span
                            class="error"></span>
                        </div>
                      </span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-item input-with-label">
                        <label class="input-item-label">Calculated Amount</label>
                        <div class="content-area card border-primary ">
                          <div class="card-header">
                            <p id="buy-card-name" class="font-weight-bold"></p>
                          </div>
                          <ul class="list-group list-group-flush">
                            <!---->
                            <li class="list-group-item"><strong>Total:</strong> <span
                                class="float-right text-primary font-weight-bold" id="buy-gift-card-equiv">NGN
                                0.00</span>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-item input-with-label">
                        <label class="input-item-label">Comments (optional)</label>
                        <textarea placeholder="Comments" name="comment"
                          class="input-bordered input-textarea"></textarea>
                      </div>
                    </div>
                  </div>
                </form>

              </div><!-- .tab-pane -->
            </div><!-- .tab-content -->

            <div class="row" id="terms-box">
              <div class="col-md-12">
                <div class="card border-primary">
                  <div class="card-header">
                    <p class="card-title">TERMS OF TRADE</p>
                  </div>
                  <div class="card-body" id="terms">

                  </div>
                </div>
              </div>
            </div>
          </div><!-- .card-innr -->
        </div><!-- .card -->

      </div><!-- .col -->
      <div class="aside sidebar-right col-lg-4">
        <div class="account-info card">
          <div class="card-innr">
            <div class="card-head">
              <span class="card-sub-title text-primary font-mid">To complete Order</span>
            </div>
            <div class="card-text" id="buy-detail">
              <p>Continue with payment through Flutterwave </p>
            </div>
            <div class="pay-buttons">
              <div class="pay-button" id="sell-submit"><a href="#" class="btn btn-primary btn-between w-100"
                  onclick="event.preventDefault();document.getElementById('sell-form').submit()">Place Order <em
                    class="ti ti-arrow-right"></em></a></div>
              <!-- <div class="pay-button-sap">or</div> -->
              <div class="pay-button" id="buy-submit" style="display: none;"><a href="#"
                  onclick="event.preventDefault();document.getElementById('buy-form').submit()"
                  class="btn btn-primary btn-between w-100">Make Online Payment <em class="ti ti-arrow-right"></em></a>
              </div>
            </div>
          </div>
        </div>

      </div><!-- .col -->
    </div><!-- .container -->
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Dropzone Image Upload
  // The recommended way from within the init configuration:
  let giftCardShot = new Dropzone("#gift-card-shot", {
    url: "{{ route('transaction.upload') }}",
    maxFilesize: 5,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  let imagesArray = [];

  giftCardShot.on("success", function(file, response) {
    imagesArray.push(response)
    $("#images").val(JSON.stringify(imagesArray))
    // console.log($("#images").val())
  });

  // giftCardShot.on("addedfile", function(file) {
  //   console.log(file.name)
  // });


  let categories = @json($categories);
  let cardBox = $('#gift-card');
  let cardCategoryBox = $('#gift-card-category');
  let amountBox = $('#gift-card-amount');
  let totalBox = $('#gift-card-equiv');

  let cardBuyBox = $('#buy-gift-card');
  let amountBuyBox = $('#buy-gift-card-amount');
  amountBuyBox.prop('disabled', true)
  let totalBuyBox = $('#buy-gift-card-equiv');

  // Box for displaying terms of trade for each card
  let termsBox = $('#terms-box');
  termsBox.hide();
  let termsDisplay = $('#terms');

  let currentCategory = null

  amountBox.attr('disabled', true)

  cardCategoryBox.change(function() {
    var id = this.value
    loadCard(categories, id)
  });

  // Get the current card
  cardBox.change(function() {
    var id = this.value
    if (id === undefined || id < 1 || id === '') {
      termsBox.hide();
      return
    }

    // Search for the card
    var curCard = currentCategory.cards.find((card) => {
      return card.id === Number(id)
    })

    // Set the validation for the amount input
    // data-multiples="10"
    if (!(curCard.denomination === '' || curCard.denomination === undefined)) {
      amountBox.data('multiples', curCard.denomination)
    }

    if (!(curCard.max === '' || curCard.max === undefined)) {
      amountBox.attr('max', curCard.max)
    }

    // Populate the calculation box with the current card name
    $('#card-name').text(curCard.name)

    // Make terms box visible
    termsBox.show();
    termsDisplay.html(curCard.terms)
  });

  amountBox.keyup(function() {
    var amt = Number(this.value)
    var id = cardBox.val()

    var curCard = currentCategory.cards.find((card) => {
      return card.id === Number(id)
    })
    // console.log(curCard);
    var rate = curCard.rate
    calculateRate(amt, rate)
  });

  let loadCard = function(categories, id) {
    if (id === undefined || id < 1 || id === '') {
      return
    }

    // Find the category in the categories array
    currentCategory = categories.find((cat) => {
      return cat.id === Number(id)
    })



    var cardOptions = `<option value="0">Select</option>`
    // Load the category cards into the card select input
    currentCategory.cards.forEach((card) => {
      cardOptions += `<option value="${card.id}">${card.name} - (${card.rate}/$)</option>`
    })

    cardBox.empty().append(cardOptions)
    amountBox.val("")
    amountBox.attr('disabled', false)
    // console.log(cardOptions)
  }

  // FOR THE BUY CARD
  // ********************************* //
  var buyRate = undefined
  var cardToBuy = undefined

  cardBuyBox.change(function() {
    var selected = $(this).find('option:selected')
    var id = this.value

    if (id === undefined || id < 1 || id === '') {
      termsBox.hide();
      return
    }


    // Get the current card
    cardToBuy = selected.data('card')

    // Set the validation for the amount input
    // data-multiples="10"
    if (!(cardToBuy.denomination === '' || cardToBuy.denomination === undefined)) {
      amountBox.data('multiples', cardToBuy.denomination)
    }

    if (!(cardToBuy.max === '' || cardToBuy.max === undefined)) {
      amountBox.attr('max', cardToBuy.max)
    }

    // Populate the calculation box with the current card name
    $('#buy-card-name').text(cardToBuy.name)

    // Make terms box visible
    termsBox.show();
    termsDisplay.html(cardToBuy.terms)


    buyRate = Number(cardToBuy.rate)
    amountBuyBox.prop('disabled', false)

  })

  amountBuyBox.keyup(function() {
    if (buyRate == undefined) return
    var amount = this.value

    calculateRate(amount, buyRate, totalBuyBox)
  })


  // ******************************** //

  // ****************************** //
  // BANK ACCOUNTS SECTION

  let buildSelect = function(banks, key = 'id') {
    var selectOptions = `<option value="0">Select</option>`
    // Load the category cards into the card select input
    banks.forEach((bank) => {
      selectOptions += `<option value="${bank[key]}">${bank.name}</option>`
    })

    return selectOptions;
  }

  let bankToggle = $('#bank_account_direct')
  let banksBox = $('#banks')
  let currencyInput = $('#currency')
  let bankInput = $('#bank')

  // Banks
  let wallets = @json($wallets);

  currencyInput.change(function() {
    let _currency = this.value;
    let _wallet = wallets.find(w => w.currency === _currency);
    let _banks = _wallet.bank_accounts;
    let _bankOptions = buildSelect(_banks);
    bankInput.empty().append(_bankOptions);
  })

  bankToggle.click(function() {
    var isChecked = this.checked

    if (isChecked) {
      banksBox.show()
      bankInput.prop('disabled', false)
    } else {
      banksBox.hide()
      bankInput.prop('disabled', true)
    }
  })

  let calculateRate = function(amount, rate, element = null) {
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
  }

  // JQUERY VALIDATION
  jQuery.validator.addMethod("multiples", function(value, element, jsonParams) {
    var isValid = false
    var params = $(element).data('multiples')

    if (params === undefined || params === null) {
      params = jsonParams
    }

    if ((typeof params) === "boolean") {
      return true
    }

    if ((typeof params) === "number") {
      params = params.toString()
    }

    params.split(',').forEach((a) => {
      if (Number(value) % Number(a) === 0) {
        isValid = true
      }
    })
    return isValid
  }, `Please enter a valid amount. Should be multiple of denomination.`)

  $("#sell-form").validate({
    rules: {
      amount: {
        required: true,
        multiples: true,
      }
    },
    // submitHandler: function(form) {
    //   // some other code
    //   // maybe disabling submit button
    //   // then:
    //   // $(form).submit();
    //   alert('ehe')
    // }
  });

  $("#buy-form").validate({
    rules: {
      amount: {
        required: true,
        multiples: true,
      }
    },
    // submitHandler: function(form) {
    //   // some other code
    //   // maybe disabling submit button
    //   // then:
    //   // $(form).submit();
    //   alert('ehe')
    // }
  });

});

//  ****************************** //
// Payment

let sellCard = $('#sell-toggle');
let buyCard = $('#buy-toggle');
let sellSubmit = $('#sell-submit');
let buySubmit = $('#buy-submit');
let buyDetails = $('#buy-detail');
buySubmit.hide()
buyDetails.hide()

sellCard.click(function() {
  sellSubmit.show()
  buySubmit.hide()
})

buyCard.click(function() {
  sellSubmit.hide()
  buySubmit.show()
})
</script>
@endpush
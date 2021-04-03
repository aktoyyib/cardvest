@extends('layouts.app')

@section('title', 'Cardvest - Rates')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="aside sidebar-right col-lg-4 d-flex flex-column justify-content-center">

        <h1 class="font-weight-bold">
          Our Rates:
          <br>The Best Every Time.
        </h1>
        <p class="text-subtext1 leading-33" data-v-d79bb0ec="">
          We give you good value for your cards every time you choose
          Cardvest.
        </p>

      </div><!-- .col -->
      <div class="main-content col-lg-8">
        <div class="content-area card">
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
                    <div class="row">
                      <div class="col-12">
                        <div class="input-item input-with-label">
                          <label class="input-item-label">Amount</label>
                          <input class="input-bordered" type="number" id="gift-card-amount" placeholder="Amount in USD">
                        </div>
                      </div>
                    </div>
                    <div class="token-overview-wrap mt-0">
                      <div class="token-overview">
                        <div class="row">
                          <div class="col">
                            <div class="token-bonus token-bonus-sale pt-1">
                              <span class="token-overview-title"> = </span>
                              <span class="token-overview-value bonus-on-sale" id="gift-card-equiv">0.00</span>
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
                        <option value="{{ $card->id }}" data-rate="{{ $card->rate }}">{{ $card->name." - ".$card->rate."/$" }}</option>
                        @endforeach
                      </select>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-12">
                        <div class="input-item input-with-label">
                          <label class="input-item-label">Amount</label>
                          <input class="input-bordered" type="number" id="buy-gift-card-amount" placeholder="Amount in USD">
                        </div>
                      </div>
                    </div>
                    <div class="token-overview-wrap mt-0">
                      <div class="token-overview">
                        <div class="row">
                          <div class="col">
                            <div class="token-bonus token-bonus-sale pt-1">
                              <span class="token-overview-title"> = </span>
                              <span class="token-overview-value bonus-on-sale" id="buy-gift-card-equiv">0.00</span>
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


      </div><!-- .col -->


    </div><!-- .container -->
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection

@push('scripts')
<script>
$(document).ready(function() {

  let categories = @json($categories);
  let cardBox = $('#gift-card');
  let cardCategoryBox = $('#gift-card-category');
  let amountBox = $('#gift-card-amount');
  let totalBox = $('#gift-card-equiv');

  let cardBuyBox = $('#buy-gift-card');
  let amountBuyBox = $('#buy-gift-card-amount');
  amountBuyBox.prop('disabled', true)
  let totalBuyBox = $('#buy-gift-card-equiv');

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



    var cardOptions = `<option>Select</option>`
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

    calculateRate(amount, buyRate, totalBuyBox)
  })


  // ******************************** //

  let calculateRate = function(amount, rate, element = null) {
    // console.log(rate)
    var total = Number(amount) * rate

    // create a formatter
    var formatter =  new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'NGN'
    })

    if (element === null)
      totalBox.text(formatter.format(total))
    else
      element.text(formatter.format(total))
  }
});
</script>
@endpush
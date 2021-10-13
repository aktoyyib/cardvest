@extends('layouts.app')

@section('title', 'Cardvest - User')
@section('content')
<div class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 aside sidebar-left">
        @include('partials.admin_sidebar')
      </div>
      <div class="col-lg-9 main-content">
        <div class="card content-area">
          <div class="card-innr card-innr-fix">
            <div class="card-head d-flex justify-content-between align-items-center">
              <h4 class="card-title mb-0">Transaction Details</h4>
              <div class="d-flex align-items-center guttar-20px">
                <div class="flex-col d-sm-block d-none">
                  <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-auto btn-primary"><em
                      class="fas fa-arrow-left mr-3"></em>Back</a>
                </div>
                <div class="flex-col d-sm-none">
                  <a href="user-list.html" class="btn btn-icon btn-sm btn-primary"><em
                      class="fas fa-arrow-left"></em></a>
                </div>

              </div>
            </div>
            <div class="gaps-1-5x"></div>
            <div class="data-details d-md-flex">
              <div class="fake-class">
                <span class="data-details-title">Reference</span>
                <span class="data-details-info">
                  <strong>{{ $transaction->reference }}</strong>
                </span>
              </div>

              <div class="fake-class">
                <span class="data-details-title">Transaction Date</span>
                <span class="data-details-info">
                  {{ $transaction->getDate() }} {{ $transaction->getTime() }}
                </span>
              </div>
              <div class="fake-class">
                <span class="data-details-title">Status</span>
                <span
                  class="dt-type-md badge badge-{{ $transaction->getDescription($transaction->status) }} badge-md text-capitalize">{{ $transaction->status }}</span>
              </div>
              @if($transaction->type === 'buy')
              <div class="fake-class">
                <span class="data-details-title">Payment Status</span>
                <span
                  class="dt-type-md badge badge-{{ $transaction->getDescription($transaction->payment_status) }} badge-md text-capitalize">{{ $transaction->payment_status }}</span>
              </div>
              @endif
            </div>
            <h6 class="card-sub-title">Transaction Info</h6>
            <ul class="data-details-list">
              <li>
                <div class="data-details-head">Transaction Type</div>
                <div class="data-details-des text-capitalize"><strong>{{ $transaction->type }}</strong></div>
              </li><!-- li -->
              @if($transaction->type !== 'payout')
              <li>
                <div class="data-details-head">Gift Card Category</div>
                <div class="data-details-des"><strong>{{ $transaction->card->category->name }}</strong></div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">Gift Card</div>
                <div class="data-details-des"><strong>{{ $transaction->card->name }}</strong></div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">Rate</div>
                <div class="data-details-des"><span>{{ $transaction->card->rate }}/$</span>
                  <span></span>
                </div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">Unit</div>
                <div class="data-details-des"><span>{{ $transaction->unit ?? '-' }}</span>
                  <span></span>
                </div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">Total Amount</div>
                <div class="data-details-des"><span>&#8358;{{ to_naira($transaction->amount) }}</span> <span></span>
                </div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">Users comment</div>
                <div class="data-details-des">{{ $transaction->comment ?? '-' }}</div>
              </li><!-- li -->
              @else
              <li>
                <div class="data-details-head">Amount</div>
                <div class="data-details-des"><span>&#8358;{{ to_naira($transaction->amount) }}</span> <span></span>
                </div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">New Balance</div>
                <div class="data-details-des"><span>&#8358;{{ to_naira($transaction->balance) }}</span> <span></span>
                </div>
              </li><!-- li -->
              @endif
            </ul>

            <div class="gaps-3x"></div>
            <h6 class="card-sub-title">Users Info</h6>
            <ul class="data-details-list">
              <li>
                <div class="data-details-head">Username</div>
                <div class="data-details-des">
                  <strong>{{ $transaction->user->username }}</strong>
                </div>
              </li>

              <li>
                <div class="data-details-head">Email</div>
                <div class="data-details-des">
                  <strong>{{ $transaction->user->email }}</strong>
                </div>
              </li>

              <li>
                <div class="data-details-head">Phone Number</div>
                <div class="data-details-des">
                  <strong>{{ $transaction->user->phonenumber }}</strong>
                </div>
              </li>
              @if($transaction->type === 'sell')
              <li>
                <div class="data-details-head">Pay To</div>
                <div class="data-details-des">
                  <strong>{{ !is_null($transaction->bank) ? $transaction->bank->bankname . " ". $transaction->bank->banknumber : 'User\'s Wallet' }}</strong>
                </div>
              </li>
              @else

              @endif
            </ul>

            @if($transaction->type !== 'payout')
            <div class="gaps-3x"></div>
            <h6 class="card-sub-title">Transaction Feedback</h6>
            <ul class="data-details-list">
              @if($transaction->type === 'sell')
              <li>
                <div class="data-details-head">Image Uploads</div>
                <div class="data-details-des d-flex justify-content-start">
                  @if(!is_null($transaction->images))
                  @forelse(json_decode($transaction->images) as $image)
                  <a class="img-thumbnail mr-2" target="_blank">
                    <img
                      src="{!! Storage::url($image) !!}" href="{!! Storage::url($image) !!}" class="image-popup" height="30px" alt="">
                    </a>
                  @empty
                  <span class="text-primary">No Image uploaded! <i class="fa fa-times text-danger"></i> </span>
                  @endforelse
                  @else
                  <!-- This handles old implementations where images is NULL rather than the recenet [] -->
                  <span class="text-primary">No Image uploaded! <i class="fa fa-times text-danger"></i> </span>
                  @endif
                </div>
              </li>
              @endif

              <li>
                <div class="data-details-head">Admin Feedback</div>
                <div class="data-details-des">
                  <div class="w-100 row">
                    <form method="post" action="{{ route('transactions.update', $transaction) }}"
                      id="transaction-update" class="w-100 row">
                      @csrf
                      @method('put')
                      <div class="col-sm-6">
                        <textarea name="admin_comment" id="admin_comment" class="input-textarea input-bordered"
                          placeholder="Your comment here">{{ $transaction->admin_comment }}</textarea>

                          <div class="input-item input-with-label mt-3">
                            <label class="input-item-label">Add Image to Comment <span class="text-danger">*</span> </label>
                            <div id="admin-comment-image">
                              <div class="dz-message" data-dz-message>
                                <span class="dz-message-text">Drag and drop file</span>
                                <span class="dz-message-or">or</span>
                                <button class="btn btn-sm btn-primary" type="button">Choose a File</button>
                              </div>
                            </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                        <div class="d-flex flex-column">
                          <!-- Once the transaction is approved or declined. Only the admin comment can be edited. -->
                          @if($transaction->status !== 'succeed')

                          @if($transaction->type == 'sell')
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Amount Payable <span class="text-warning">(Default:
                                &#8358;{{ to_naira($transaction->amount) }})</span></label>
                            <input type="number" placeholder="Enter amount" inputmode="numeric" name="amount"
                              class="input-bordered">
                          </div>
                          @endif

                          <div class="input-item input-with-label">
                            <label class="input-item-label">Gift Card</label>
                            <div class="select-wrapper">
                              <select class="select-bordered select select-block" name="card_id" id="gift-card">
                                <option>Select</option>
                                @foreach($transaction->card->category->all_cards()->type($transaction->type)->get() as
                                $card)
                                <option value="{{ $card->id }}"
                                  {{ $transaction->card_id == $card->id ? 'selected' : '' }}>
                                  {{ $card->name }}
                                </option>
                                @endforeach
                              </select>
                            </div>
                          </div>

                          <div class="input-item input-with-label">
                            <label class="input-item-label">Select Action </label>
                            <div class="select-wrapper">
                              <select class="select-bordered select select-block" name="status">
                                <option>Select</option>
                                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>
                                  Processing</option>
                                <option value="rejected" {{ $transaction->status == 'rejected' ? 'selected' : '' }}>
                                  Reject</option>
                                <option value="succeed" {{ $transaction->status == 'succeed' ? 'selected' : '' }}>
                                  Approve</option>
                              </select>
                            </div>
                          </div>

                          @if($transaction->type === 'buy')
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Update Payment Status</label>
                            <div class="select-wrapper">
                              <select class="select-bordered select select-block" name="payment_status">
                                <option>Select</option>
                                <option value="pending"
                                  {{ $transaction->payment_status == 'pending' ? 'selected' : '' }}>Processing</option>
                                <option value="failed" {{ $transaction->payment_status == 'failed' ? 'selected' : '' }}>
                                  Failed</option>
                                <option value="succeed"
                                  {{ $transaction->payment_status == 'succeed' ? 'selected' : '' }}>Approve</option>
                              </select>
                            </div>
                          </div>
                          @endif

                          @endif
                          <!-- Ends here:  Once the transaction is approved or declined. Only the admin comment can be edited. -->

                          <!-- A check before transaction update is only necessary when payment is still pending -->
                          @if($transaction->status === 'pending')
                          <button class="btn btn-primary mt-1" onclick="approveTransaction(event)">Update</button>
                          @else
                          <button class="btn btn-primary mt-1">Update</button>
                          @endif
                        </div>
                      </div>
                    </form>
                  </div>

                </div>
              </li>

            </ul>
            @endif
          </div><!-- .card-innr -->
        </div><!-- .card -->
      </div>
    </div>

  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script>
let approveTransaction = function(event) {
  event.preventDefault()

  swal({
      title: "Are you sure you want to continue?",
      text: "This transaction (if it's a card SALE) might initiate a payout to the users wallet, this is irreversible!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $('#transaction-update').submit();
      } else {

      }
    });
}

let cancelTransaction = function(id) {

}

let deleteTransaction = function(id) {

}

$(document).ready(function() {

  // Dropzone Image Upload
  // The recommended way from within the init configuration:
  let adminCommentImage = new Dropzone("#admin-comment-image", {
    url: "{{ route('transactions.upload', $transaction) }}",
    maxFilesize: 5,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // giftCardShot.on("success", function(file, response) {
  //   imagesArray.push(response)
  //   $("#images").val(JSON.stringify(imagesArray))
  //   // console.log($("#images").val())
  // });
});
</script>
@endpush
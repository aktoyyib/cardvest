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
                <div class="data-details-des">
                  @foreach(json_decode($transaction->images) as $image)
                  <a href="{!! Storage::url('gift-cards/'.$image) !!}" class="img-thumbnail" target="_blank"><img
                      src="{!! Storage::url('gift-cards/'.$image) !!}" height="30px" alt=""></a>
                  @endforeach
                </div>
              </li>
              @endif
              <li>
                <div class="data-details-head">Admin Feedback</div>
                <div class="data-details-des">
                  <div class="w-100 row">
                    <form method="post" action="{{ route('transactions.update', $transaction) }}" class="w-100 row">
                      @csrf
                      @method('put')
                      <div class="col-sm-6">
                        <textarea name="admin_comment" id="admin_comment" class="input-textarea input-bordered"
                          placeholder="Your comment here">{{ $transaction->admin_comment }}</textarea>

                      </div>
                      <div class="col-md-6">
                        <div class="d-flex flex-column">
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Select Action</label>
                            <div class="select-wrapper">
                              <select class="input-bordered select-block" name="status" required>
                                <option>Select</option>
                                <option value="pending">Processing</option>
                                <option value="rejected">Reject</option>
                                <option value="succeed">Approve</option>
                              </select>
                            </div>
                          </div>

                          @if($transaction->type === 'buy')
                          <div class="input-item input-with-label">
                            <label class="input-item-label">Update Payment Status</label>
                            <div class="select-wrapper">
                              <select class="input-bordered select-block" name="payment_status" required>
                                <option>Select</option>
                                <option value="pending">Processing</option>
                                <option value="failed">Failed</option>
                                <option value="succeed">Approve</option>
                              </select>
                            </div>
                          </div>
                          @endif

                          <button class="btn btn-primary">Update</button>
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
let approveTransaction = function(id) {

}

let cancelTransaction = function(id) {

}

let deleteTransaction = function(id) {

}
</script>
@endpush
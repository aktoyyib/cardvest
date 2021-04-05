@extends('layouts.app')

@section('title', 'Cardvest Withdrawal ')
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
              <h4 class="card-title mb-0">Withdrawal Details</h4>
              <div class="d-flex align-items-center guttar-20px">
                <div class="flex-col d-sm-block d-none">
                  <a href="{{ route('withdrawals.index') }}" class="btn btn-sm btn-auto btn-primary"><em
                      class="fas fa-arrow-left mr-3"></em>Back</a>
                </div>
                <div class="flex-col d-sm-none">
                  <a href="{{ route('withdrawals.index') }}" class="btn btn-icon btn-sm btn-primary"><em
                      class="fas fa-arrow-left"></em></a>
                </div>

              </div>
            </div>
            <div class="gaps-1-5x"></div>
            <div class="data-details d-md-flex">
              <div class="fake-class">
                <span class="data-details-title">Reference</span>
                <span class="data-details-info">
                  <strong>{{ $withdrawal->reference }}</strong>
                </span>
              </div>

              <div class="fake-class">
                <span class="data-details-title">Request Date</span>
                <span class="data-details-info">
                  {{ $withdrawal->getDate() }} {{ $withdrawal->getTime() }}
                </span>
              </div>
              <div class="fake-class">
                <span class="data-details-title">Status</span>
                <span
                  class="dt-type-md badge badge-{{ $withdrawal->getDescription($withdrawal->payment_status) }} badge-md text-capitalize">{{ $withdrawal->payment_status }}</span>
              </div>
            </div>
            <h6 class="card-sub-title">Withdrawal Info</h6>
            <ul class="data-details-list">
              <li>
                <div class="data-details-head">Amount</div>
                <div class="data-details-des"><span>&#8358;{{ to_naira($withdrawal->amount) }}</span> <span></span>
                </div>
              </li><!-- li -->
              <li>
                <div class="data-details-head">New Balance</div>
                <div class="data-details-des"><span>&#8358;{{ to_naira($withdrawal->balance) }}</span> <span></span>
                </div>
              </li><!-- li -->
            </ul>

            <div class="gaps-3x"></div>
            <h6 class="card-sub-title">Users Info</h6>
            <ul class="data-details-list">
              <li>
                <div class="data-details-head">Username</div>
                <div class="data-details-des">
                  <strong>{{ $withdrawal->user->username }}</strong>
                </div>
              </li>

              <li>
                <div class="data-details-head">Email</div>
                <div class="data-details-des">
                  <strong>{{ $withdrawal->user->email }}</strong>
                </div>
              </li>

              <li>
                <div class="data-details-head">Phone Number</div>
                <div class="data-details-des">
                  <strong>{{ $withdrawal->user->phonenumber }}</strong>
                </div>
              </li>

              <li>
                <div class="data-details-head">Pay To</div>
                <div class="data-details-des">
                  <strong>{{ $withdrawal->bank->bankname . " ". $withdrawal->bank->banknumber }}</strong>
                </div>
              </li>

            </ul>

            <div class="gaps-3x"></div>
            <h6 class="card-sub-title">Withdrawal Feedback</h6>
            <ul class="data-details-list">
              @if($withdrawal->payment_success === 'pending')
              <li>
                <div class="data-details-head">Admin Feedback</div>
                <div class="data-details-des">
                  <div class="w-100 row">
                    <form method="post" action="{{ route('withdrawals.update', $withdrawal) }}" class="w-100 row">
                      @csrf
                      @method('put')
                      <div class="col-sm-6">
                        <textarea name="admin_comment" id="admin_comment" class="input-textarea input-bordered"
                          placeholder="Your comment here">{{ $withdrawal->admin_comment }}</textarea>

                      </div>
                      <div class="col-md-6">
                        <div class="d-flex flex-column">

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

                          <button class="btn btn-primary">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>

                </div>
              </li>
              @endif
            </ul>
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
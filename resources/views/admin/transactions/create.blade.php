@extends('layouts.app')

@section('title', 'Cardvest - Admin Payout')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 aside sidebar-left ">
        @include('partials.admin_sidebar')

      </div>

      <div class="col-lg-3">
        <div class="token-calculator card h-100">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Send Funds</h4>
            </div>
            <form action="{{ route('transactions.store') }}" method="post">
              @csrf
              <input type="hidden" name="role" value="admin">
              <div class="input-item input-with-label">
                <label class="input-item-label">Enter amount to send</label>
                <input class="input-bordered" type="number" min="0" step="0.01" name="amount" placeholder="Amount"
                  required>
              </div>

              <div class="input-item">
                <input type="checkbox" name="debit" id="debit"
                  class="input-checkbox input-checkbox-md">
                <label for="debit">
                  Debit users account
                </label>
              </div>
              
              <div class="input-item input-with-label">
                <label class="input-item-label">Enter users email address</label>
                <input class="input-bordered" type="email" name="email" placeholder="Email Address" required>
              </div>

              <div class="token-buy text-center">
                <button class="btn btn-primary">Proceed</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-6 main-content">
        <div class="content-area card h-100">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Admin Payouts</h4>
            </div>
            <table class="data-table  admin-tnx">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-tnxno">Tranx NO</th>
                  <th class="data-col dt-token">Amount</th>
                  <th class="data-col dt-amount">To</th>
                </tr>
              </thead>
              <tbody>
                @forelse($transactions as $transaction)
                <tr class="data-item">
                  <td class="data-col dt-tnxno">
                    <div class="d-flex align-items-center">
                      <div class="data-state data-state-approved">
                        <span class="d-none">Success</span>
                      </div>
                      <div class="fake-class">
                        <span class="lead tnx-id">{{ $transaction->reference }}</span>
                        <span class="sub sub-date">{{ $transaction->getDate() }} {{ $transaction->getTime() }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="data-col dt-token">
                    <span class="lead token-amount">{{ to_naira($transaction->amount) }}</span>
                    <span class="sub sub-symbol">NGN</span>
                  </td>
                  <td class="data-col dt-token">
                    <span class="sub sub-symbol">{{ $transaction->payout_to->email }}</span>
                    <span class="lead token-amount">{{ $transaction->payout_to->username }}</span>
                  </td>
                </tr><!-- .data-item -->
                @empty
                <tr class="data-item">
                  <td colspan="7" class="data-col dt-tnxno">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="fake-class text-center">
                        <span class="lead tnx-id text-danger">Transactions Empty!</span>
                      </div>
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @endforelse
              </tbody>
            </table>
          </div><!-- .card-innr -->
          <div class="card-footer bg-white">
            {{ $transactions->links() }}
          </div>
        </div><!-- .card -->
      </div>
    </div>
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script>

</script>
@endpush
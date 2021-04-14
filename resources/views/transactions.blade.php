@extends('layouts.app')

@section('title', 'Cardvest - Transactions')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="card content-area">
      <div class="card-innr">
        <div class="card-head">
          <h4 class="card-title">User Transactions</h4>
        </div>
        <table class="data-table user-tnx">
          <thead>
            <tr class="data-item data-head">
              <th class="data-col dt-tnxno">Tranx NO</th>
              <th class="data-col dt-amount">Amount</th>
              <th class="data-col dt-type">
                <div class="dt-type-text">Card</div>
              </th>
              <th class="data-col dt-type">
                <div class="dt-type-text">Type</div>
              </th>
              <th class="data-col">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transactions as $transaction)
            <tr class="data-item">
              <td class="data-col dt-tnxno">
                <div class="d-flex align-items-center">
                  <div class="data-state data-state-{{ $transaction->getStatus() }}">
                    <span class="d-none">{{ $transaction->status }}</span>
                  </div>
                  <div class="fake-class">
                    <a href="{{ route('transaction.show', $transaction) }}">
                      <span class="lead tnx-id d-none d-md-block">{{ $transaction->reference }}</span>
                      <span class="lead tnx-id d-md-none">{{ substr($transaction->reference, 0, 10) }}...</span>
                    </a>
                    <span class="sub sub-date">{{ $transaction->getDate() }} {{ $transaction->getTime() }}</span>
                  </div>
                </div>
              </td>
              <td class="data-col dt-token">
                <span class="lead token-amount">{{ to_naira($transaction->amount) }}</span>
                <span class="sub sub-symbol">NGN</span>
              </td>
              <td class="data-col dt-token dt-type-md">
                <span class="lead token-amount">{{ $transaction->card->category->name }}</span>
                <span class="sub sub-symbol">{{ $transaction->card->name }}</span>
              </td>
              <td class="dt-type">
                <span
                  class="dt-type-md badge badge-outline badge-{{ $transaction->getDescription() }} badge-md text-capitalize">{{ $transaction->type }}</span>
              </td>
              <td class="">
                <span
                  class="dt-type-md badge badge-{{ $transaction->getDescription($transaction->status) }} badge-md text-capitalize">{{ $transaction->status }}</span>

                <a href="{{ route('transaction.show', $transaction) }}"><span
                    class=" badge badge-primary badge-md text-capitalize">View</span></a>
              </td>
            </tr><!-- .data-item -->
            @empty
            <tr class="data-item">
              <td colspan="6" class="data-col dt-tnxno">
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
      <div class="card-footer bg-white justify-content-center">
        {{ $transactions->links() }}
      </div>
    </div><!-- .card -->
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()
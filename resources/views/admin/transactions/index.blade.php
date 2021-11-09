@extends('layouts.app')

@section('title', 'Cardvest - Admin User\'s List')
@section('content')
<div class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 aside sidebar-left">
        @include('partials.admin_sidebar')
      </div>

      <div class="col-lg-9 main-content">
        <div class="content-area card">
            <div class="card content-area">
                <div class="card-innr">
                    <div class="card-head">
                        <h4 class="card-title">Transaction Search</h4>
                        <form action="{{ route('transactions.search') }}">
                        <!-- @csrf -->
                            <div class="row mt-3">
                                <div class="col-md-9">
                                    <div class="input-item input-with-label">
                                        <input class="input-bordered" type="search" id="search" name="search"
                                               placeholder="Search by Reference ID">
                                    </div><!-- .input-item -->
                                </div>
                                <div class="col-md-3">
                                    <div class="input-item input-with-label">
                                        <button class="btn btn-primary">Search</button>
                                    </div><!-- .input-item -->
                                </div>
                            </div>
                        </form>
                    </div>
                    @if (!is_null($searched_transactions))
                        @if ($searched_transactions->isNotEmpty())
                            <h4>Search result for: <strong>{{ $param }}</strong></h4>
                            <hr>
                            <table class="data-table user-list">
                                <thead>
                                    <tr class="data-item data-head">
                                        <th class="data-col dt-tnxno">Tranx NO</th>
                                        <th class="data-col dt-token">Amount</th>
                                        <th class="data-col dt-amount">Details</th>
                                        <th class="data-col dt-usd-amount">Type</th>
                                        <th class="data-col dt-account">By/From</th>
                                        <th class="data-col dt-type">
                                            <div class="dt-type-text">Type</div>
                                        </th>
                                        <th class="data-col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($searched_transactions as $transaction)
                                    <tr class="data-item">
                                        <td class="data-col dt-tnxno">
                                            <div class="d-flex align-items-center">
                                                <div class="data-state data-state-{{ get_state($transaction->status) }}">
                                                    <span class="d-none text-uppercase">{{ $transaction->status }}</span>
                                                </div>
                                                <div class="fake-class">
                                                    <span class="lead tnx-id">{{ $transaction->reference }}</span>
                                                    <span class="sub sub-date">{{ $transaction->getDate() }} {{ $transaction->getTime() }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="data-col dt-token">
                                            <span class="lead token-amount">{{ to_naira($transaction->amount) }}</span>
                                            <span class="sub sub-symbol">{{ $transaction->currency }}</span>
                                        </td>
                                        <td class="data-col dt-token">
                                            @if($transaction->type !== 'payout')
                                                <span class="lead token-amount">{{ $transaction->card->category->name }}</span>
                                                <span class="sub sub-symbol">{{ $transaction->card->name }}</span>
                                            @else
                                                <span class="sub sub-symbol">Payout to:</span>
                                                <span class="lead token-amount">{{ $transaction->payout_to->username ?? 'Me' }}</span>
                                            @endif
                                        </td>
                                        <td class="data-col dt-type">
                    <span
                        class="dt-type-md badge badge-outline badge-{{ $transaction->getDescription() }} badge-md text-capitalize">{{ $transaction->type }}</span>
                                        </td>
                                        <td class="data-col dt-account">
                                            <span class="lead user-info">{{ $transaction->user->username }}</span>
                                            <span class="sub sub-date">08 Jul, 18 10:20PM</span>
                                        </td>
                                        <td class="data-col text-right">
                                            <div class="relative d-inline-block">
                                                <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em
                                                        class="ti ti-more-alt"></em></a>
                                                <div class="toggle-class dropdown-content dropdown-content-top-left">
                                                    <ul class="dropdown-list">
                                                        <li><a href="{{ route('transactions.show', $transaction) }}"><em class="ti ti-eye"></em> View
                                                                Details</a></li>
                                                        <!-- <li><a href="#"><em class="ti ti-check-box"></em> Approve</a></li>
                                                    <li><a href="#"><em class="ti ti-na"></em> Cancel</a></li>
                                                    <li><a href="#"><em class="ti ti-trash"></em> Delete</a></li> -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr><!-- .data-item -->
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-light alert-center">
                                <div class="">
                                    <em class="fa fa-times fa-3x"></em>
                                </div>
                                <p>Search not found!</p>
                            </div>
                        @endif
                    @endif
                </div><!-- .card-innr -->
            </div><!-- .card -->


            <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Admin Transactions</h4>
            </div>
            <table class="data-table dt-filter-init admin-tnx">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-tnxno">Tranx NO</th>
                  <th class="data-col dt-token">Amount</th>
                  <th class="data-col dt-amount">Details</th>
                  <th class="data-col dt-usd-amount">Type</th>
                  <th class="data-col dt-account">By/From</th>
                  <th class="data-col dt-type">
                    <div class="dt-type-text">Type</div>
                  </th>
                  <th class="data-col"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($transactions as $transaction)
                <tr class="data-item">
                  <td class="data-col dt-tnxno">
                    <div class="d-flex align-items-center">
                      <div class="data-state data-state-{{ get_state($transaction->status) }}">
                        <span class="d-none text-uppercase">{{ $transaction->status }}</span>
                      </div>
                      <div class="fake-class">
                        <span class="lead tnx-id">{{ $transaction->reference }}</span>
                        <span class="sub sub-date">{{ $transaction->getDate() }} {{ $transaction->getTime() }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="data-col dt-token">
                    <span class="lead token-amount">{{ to_naira($transaction->amount) }}</span>
                    <span class="sub sub-symbol">{{ $transaction->currency }}</span>
                  </td>
                  <td class="data-col dt-token">
                    @if($transaction->type !== 'payout')
                    <span class="lead token-amount">{{ $transaction->card->category->name }}</span>
                    <span class="sub sub-symbol">{{ $transaction->card->name }}</span>
                    @else
                    <span class="sub sub-symbol">Payout to:</span>
                    <span class="lead token-amount">{{ $transaction->payout_to->username ?? 'Me' }}</span>
                    @endif
                  </td>
                  <td class="data-col dt-type">
                    <span
                      class="dt-type-md badge badge-outline badge-{{ $transaction->getDescription() }} badge-md text-capitalize">{{ $transaction->type }}</span>
                  </td>
                  <td class="data-col dt-account">
                    <span class="lead user-info">{{ $transaction->user->username }}</span>
                    <span class="sub sub-date">08 Jul, 18 10:20PM</span>
                  </td>
                  <td class="data-col text-right">
                    <div class="relative d-inline-block">
                      <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em
                          class="ti ti-more-alt"></em></a>
                      <div class="toggle-class dropdown-content dropdown-content-top-left">
                        <ul class="dropdown-list">
                          <li><a href="{{ route('transactions.show', $transaction) }}"><em class="ti ti-eye"></em> View
                              Details</a></li>
                          <!-- <li><a href="#"><em class="ti ti-check-box"></em> Approve</a></li>
                      <li><a href="#"><em class="ti ti-na"></em> Cancel</a></li>
                      <li><a href="#"><em class="ti ti-trash"></em> Delete</a></li> -->
                        </ul>
                      </div>
                    </div>
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

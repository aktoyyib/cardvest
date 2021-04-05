@extends('layouts.app')

@section('title', 'Cardvest - Admin Withdrawal List')
@section('content')
<div class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 aside sidebar-left">
        @include('partials.admin_sidebar')
      </div>

      <div class="col-lg-9 main-content">
        <div class="content-area card">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Withdrawals</h4>
            </div>
            <table class="data-table dt-filter-init admin-tnx">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-tnxno">Tranx NO</th>
                  <th class="data-col dt-token">Amount</th>
                  <th class="data-col dt-amount">Details</th>
                  <th class="data-col dt-usd-amount">Bank</th>
                  <th class="data-col"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($withdrawals as $withdrawal)
                <tr class="data-item">
                  <td class="data-col dt-tnxno">
                    <div class="d-flex align-items-center">
                      <div class="data-state data-state-{{ get_state($withdrawal->payment_status) }}">
                        <span class="d-none text-uppercase">{{ $withdrawal->payment_status }}</span>
                      </div>
                      <div class="fake-class">
                        <span class="lead tnx-id">{{ $withdrawal->reference }}</span>
                        <span class="sub sub-date">{{ $withdrawal->getDate() }} {{ $withdrawal->getTime() }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="data-col dt-token">
                    <span class="lead token-amount">{{ to_naira($withdrawal->amount) }}</span>
                    <span class="sub sub-symbol">NGN</span>
                  </td>
                  <td class="data-col dt-token">
                    <span class="lead token-amount">{{ $withdrawal->user->username }}</span>
                    <span class="sub sub-symbol">B:{{ $withdrawal->user->balance() }}</span>
                  </td>
                  <td class="data-col dt-token">
                    <span class="lead token-amount">{{ $withdrawal->bank->bankname }}</span>
                    <span class="sub sub-symbol">{{ $withdrawal->bank->banknumber }}</span>
                  </td>
                  <td class="data-col text-right">
                    <div class="relative d-inline-block">
                      <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em
                          class="ti ti-more-alt"></em></a>
                      <div class="toggle-class dropdown-content dropdown-content-top-left">
                        <ul class="dropdown-list">
                          <li><a href="{{ route('withdrawals.show', $withdrawal) }}"><em class="ti ti-eye"></em> View
                              Details</a></li>
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
                        <span class="lead tnx-id text-danger">Withdrawals Empty!</span>
                      </div>
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @endforelse
              </tbody>
            </table>
          </div><!-- .card-innr -->
          <div class="card-footer bg-white">
            {{ $withdrawals->links() }}
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
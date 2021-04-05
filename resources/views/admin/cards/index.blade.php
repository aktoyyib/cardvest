@extends('layouts.app')

@section('title', 'Cardvest - Gift Card Categories')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 aside sidebar-left">
        @include('partials.admin_sidebar')


      </div>
      <div class="col-lg-9 main-content">
        <div class="card content-area">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Gift Card & Categories</h4>
              <hr>
            </div>
            <table class="data-table user-list">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-user">Name</th>
                  <th class="data-col dt-email">Cards</th>
                  <th class="data-col dt-email">Sell</th>
                  <th class="data-col dt-email">Buy</th>
                  <th class="data-col"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($categories as $category)
                <tr class="data-item">
                  <td class="data-col dt-user">
                    <div class="user-block">
                      <div class="user-info">
                        <span class="lead user-name">{{ $category->name }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="data-col dt-email">
                    <span class="sub sub-s2 sub-email">{{ $category->cards->count() }} gift cards</span>
                  </td>
                  <td class="data-col dt-email">
                    <span class="sub sub-s2 sub-email">{{ $category->cards()->sale()->count() }}</span>
                  </td>
                  <td class="data-col dt-email">
                    <span class="sub sub-s2 sub-email">{{ $category->cards()->buy()->count() }}</span>
                  </td>
                  <td class="data-col text-right">
                    <div class="relative d-inline-block">
                      <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em
                          class="ti ti-more-alt"></em></a>
                      <div class="toggle-class dropdown-content dropdown-content-top-left">
                        <ul class="dropdown-list">
                          <li><a href="{{ route('categories.show', $category) }}"><em class="ti ti-eye"></em> Open</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @empty
                <tr class="data-item">
                  <td colspan="5" class="data-col dt-tnxno">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="fake-class text-center">
                        <span class="lead tnx-id text-danger">No category!</span>
                      </div>
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @endforelse
              </tbody>
            </table>
          </div><!-- .card-innr -->
          <div class="card-footer bg-white">
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
@extends('layouts.app')

@section('title', 'Cardvest - Admin User\'s List')
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
              <h4 class="card-title">Users Search</h4>
              <form action="{{ route('roles.search') }}">
                <!-- @csrf -->
                <div class="row mt-3">
                  <div class="col-md-9">
                    <div class="input-item input-with-label">
                      <input class="input-bordered" type="search" id="search" name="user"
                        placeholder="Search Users by Email or Username">
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
            @if (!is_null($searched_users))
            @if ($searched_users->isNotEmpty())
            <h4>Search result for: <strong>{{ $param }}</strong></h4>
            <hr>
            <table class="data-table user-list">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-user">User</th>
                  <th class="data-col dt-email">Email</th>
                  <th class="data-col dt-status">Balance (&#8358;)</th>
                  <th class="data-col"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($searched_users as $user)
                <tr class="data-item">
                  <td class="data-col dt-user">
                    <div class="user-block">
                      <div class="user-photo d-flex justify-content-center {{ random_color() }} align-items-center">
                        <!-- <img src="images/user-a.jpg" alt=""> -->
                        <span class="sub user-id font-weight-bold"
                          style="font-size: 0.9rem; color: #fff;">{{ $user->getInitials() }}</span>
                      </div>
                      <div class="user-info">
                        <span class="lead user-name">{{ $user->username }}</span>
                        <span class="sub user-id">{{ $user->getId() }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="data-col dt-email">
                    <span class="sub sub-s2 sub-email">{{ $user->email }}</span>
                  </td>
                  <!-- <td class="data-col dt-status">
                                          <span class="dt-status-md badge badge-outline badge-success badge-md">Active</span>
                                          <span class="dt-status-sm badge badge-sq badge-outline badge-success badge-md">A</span>
                                      </td> -->
                  <td class="data-col dt-token">
                    <span class="lead lead-btoken">{{ to_naira($user->balance()) }}</span>
                  </td>
                  <td class="data-col text-right">
                    <div class="relative d-inline-block">
                      <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em
                          class="ti ti-more-alt"></em></a>
                      <div class="toggle-class dropdown-content dropdown-content-top-left">
                        <ul class="dropdown-list">
                          <li><a href="{{ route('roles.store', ['user' => $user]) }}" onclick="event.preventDefault();
                                                document.getElementById('make-admin-form-{{$user->id}}').submit();"><em
                                class="fa fa-users"></em> Add as Admin</a>
                          </li>
                          <form id="make-admin-form-{{$user->id}}" action="{{ route('roles.store', [$user]) }}"
                            method="POST" class="d-none">
                            @csrf
                          </form>
                        </ul>
                      </div>
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @empty

                @endforelse
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

        <div class="card content-area">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Cardvest Administrators</h4>
            </div>
            <table class="data-table user-list">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-user">User</th>
                  <th class="data-col dt-email">Email</th>
                  <th class="data-col dt-status">Balance (&#8358;)</th>
                  <th class="data-col"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($users as $user)
                <tr class="data-item">
                  <td class="data-col dt-user">
                    <div class="user-block">
                      <div class="user-photo d-flex justify-content-center {{ random_color() }} align-items-center">
                        <!-- <img src="images/user-a.jpg" alt=""> -->
                        <span class="sub user-id font-weight-bold"
                          style="font-size: 0.9rem; color: #fff;">{{ $user->getInitials() }}</span>
                      </div>
                      <div class="user-info">
                        <span class="lead user-name">{{ $user->username }}</span>
                        <span class="sub user-id">{{ $user->getId() }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="data-col dt-email">
                    <span class="sub sub-s2 sub-email">{{ $user->email }}</span>
                  </td>
                  <!-- <td class="data-col dt-status">
                                          <span class="dt-status-md badge badge-outline badge-success badge-md">Active</span>
                                          <span class="dt-status-sm badge badge-sq badge-outline badge-success badge-md">A</span>
                                      </td> -->
                  <td class="data-col dt-token">
                    <span class="lead lead-btoken">{{ to_naira($user->balance()) }}</span>
                  </td>
                  <td class="data-col text-right">
                    <div class="relative d-inline-block">
                      <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em
                          class="ti ti-more-alt"></em></a>
                      <div class="toggle-class dropdown-content dropdown-content-top-left">
                        <ul class="dropdown-list">
                          <li><a href="{{ route('roles.destroy', [$user]) }}"
                              onclick="event.preventDefault();
                                                document.getElementById('remove-admin-form-{{$user->id}}').submit();"><em class="fa fa-ban"></em> Ban as Admin</a>
                          </li>
                          <form id="remove-admin-form-{{$user->id}}" action="{{ route('roles.destroy', [$user]) }}"
                            method="POST" class="d-none">
                            @csrf
                            @method('delete')
                          </form>
                        </ul>
                      </div>
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @empty

                @endforelse
              </tbody>
            </table>
          </div><!-- .card-innr -->
          <div class="card-footer bg-white">
            {{ $users->links() }}
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
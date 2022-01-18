@extends('layouts.app')

@section('title', 'Cardvest - Admin Dashboard')
@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="token-information card card-full-height">
                    <div class="token-info">
                        <i class="fa fa-calculator fa-2x"></i>
                        <div class="gaps-2x"></div>
                        <h5 class="token-info-sub">Total Balance by Users</h5>
                        <h1 class="token-info-head text-light">&#8358;{{ to_naira($totalHeld) }}</h1>
                    </div>
                </div><!-- .card -->
            </div><!-- .col -->
        </div>
        <div class="row">
            <div class="col-lg-12 main-content">
                <div class="card content-area"> 
                    <div class="card content-area mb-0">
                        <div class="card-innr">
                            <div class="card-head">
                                <h4 class="card-title">Search Users</h4>
                                <form action="{{ route('users.search') }}" method="get">
                                <!-- @csrf -->
                                    <div class="row mt-3">
                                        <div class="col-md-9">
                                            <div class="input-item input-with-label">
                                                <input class="input-bordered" type="search" id="search" name="search"
                                                       placeholder="Search by Email or Username">
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
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                    
                    <div class="card-innr">
                        <div class="card-head">
                          <h4 class="card-title">User List</h4>
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
                                <span class="sub sub-s2 sub-email">
                                  {{ $user->email }} 
                                  @if ($user->isBanned())
                                    <span class="text-danger fa fa-ban"></span>
                                  @endif
                                </span>
                              </td>
                              <!-- <td class="data-col dt-status">
                                                      <span class="dt-status-md badge badge-outline badge-success badge-md">Active</span>
                                                      <span class="dt-status-sm badge badge-sq badge-outline badge-success badge-md">A</span>
                                                  </td> -->
                              <td class="data-col dt-token">
                                <span class="lead lead-btoken">{{ to_naira($user->balance()) }}</span>
                              </td>
                              <td class="data-col text-right">
                                <div class="relative d-inline-block" x-cloak>
                                  <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em
                                      class="ti ti-more-alt"></em></a>
                                  <div class="toggle-class dropdown-content dropdown-content-top-left">
                                    <ul class="dropdown-list">
                                      <li><a href="{{ route('users.show', $user) }}"><em class="ti ti-eye"></em> View Details</a>
                                      </li>
                                      @if ($user->isBanned())
                                      <li><a href onclick="event.preventDefault();
                                        document.getElementById('ban-form-{{ $user->id }}').submit();"><em class="fa fa-toggle-on"></em> Lift Ban</a></li>
                                      <form id="ban-form-{{ $user->id }}" action="{{ route('users.liftban', $user) }}" method="POST" class="d-none">
                                        @csrf
                                      </form>
                                      @else
                                      <li><a href x-on:click.prevent="banUser({{ $user->id }}, '{{ $user->username }}')"><em class="ti ti-na"></em> Suspend</a></li>
                                      @endif
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
        </div><!-- .row -->
    </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script>

</script>
@endpush

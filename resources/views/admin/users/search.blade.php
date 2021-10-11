@extends('layouts.app')

@section('title', 'Cardvest - Admin User\'s List')
@section('content')
<div class="page-content" x-data="{
  open: false,
  userId: null,
  userName: '',
  terms: null,
  duration: 0,
  actionRoute: '',

  banUser(id, userName) {
    this.userId = id;
    this.userName = userName;
    this.actionRoute = `/admin/users/${this.userName}/suspend`;

    $('#modal-ban').modal();
  }
}">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 aside sidebar-left">
        @include('partials.admin_sidebar')
      </div>
      <div class="col-lg-9 main-content">
        <div class="card content-area">
          <div class="card-innr">
            <div class="card-head">
              <form action="{{ route('users.search') }}" method="get">
              <!-- @csrf -->
                  <div class="row mt-3">
                      <div class="col-md-9">
                          <div class="input-item input-with-label">
                              <input class="input-bordered" type="search" id="search" name="search"
                                      placeholder="Search by Email or Username" value="{{ request()->search }}">
                          </div><!-- .input-item -->
                      </div>
                      <div class="col-md-3">
                          <div class="input-item input-with-label">
                              <button class="btn btn-primary">Search</button>
                          </div><!-- .input-item -->
                      </div>
                  </div>
              </form>
              <h4 class="card-title">Search result for: <i>{{ request()->search }}</i></h4>
              <hr>
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
                  <tr>
                    <td colspan="4">
                      <div class="alert alert-warning alert-center my-3">
                        <p>User with username <strong>{{ request()->search }}</strong> not found!</p>
                      </div>
                    </td>
                  </tr>
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

  <!-- Modal User Banning -->
<div class="modal fade" id="modal-ban" tabindex="-1">
  <div class="modal-dialog modal-dialog-sm modal-dialog-centered">
      <div class="modal-content">
          <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
          <div class="popup-body">
              <h3 class="popup-title">Ban User (<small x-text="userName"></small>)</h3>
              <form x-bind:action="actionRoute" method="post">
                @csrf
                <div class="row justify-content-center">
                  <div class="col-md-12">
                      <div class="input-item input-with-label">
                          <label class="input-item-label">Ban Terms</label>
                          <div class="select-wrapper">
                              <select class="input-bordered" x-model="terms" name="terms">
                                  <option value="permanent">Indefinitely</option>
                                  <option value="temporary">Temporarily</option>
                              </select>
                          </div>
                      </div>
                      <template x-if="terms==='temporary'">
                        <div class="input-item input-with-label">
                          <label class="input-item-label">Duration (In days)</label>
                          <input class="input-bordered" x-model="duration" type="number" min="0" placeholder="Ban Duration" name="duration">
                        </div>
                      </template>
  
                      <button class="btn btn-primary btn-block">Proceed</button>
                  </div>
                  
                </div>
              </form>
          </div>
      </div><!-- .modal-content -->
  </div><!-- .modal-dialog -->
</div>
<!-- Modal User Banning End -->

</div><!-- .page-content -->


@endsection()

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
<script>
  

</script>
@endpush

@push('css')
  <style>
    [x-cloak] { display: none !important; }
  </style>
@endpush
@extends('layouts.app')

@section('title', 'Cardvest - Admin User\'s List')
@section('content')
<div class="page-content" x-data="{
  open: false,
  userId: null,
  userName: '',
  terms: null,
  duration: 0,

  banUser(id, userName) {
    this.id = id;
    this.userName = userName;

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
                          <li><a href="{{ route('users.show', $user) }}"><em class="ti ti-eye"></em> View Details</a>
                          </li>
                          <li><a x-on:click="banUser({{ $user->id }}, '{{ $user->username }}')"><em class="ti ti-na"></em> Suspend</a></li>
                          {{-- <li><a href="#"><em class="ti ti-trash"></em> Delete</a></li> --}}
                        </ul>
                      </div>
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @empty

                @endforelse
              </tbody>
            </table>

            <a href="#" data-toggle="modal" data-target="#modal-ban" class="btn btn-primary">Modal Centered</a>
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
              <form action="">
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
                      <div class="input-item input-with-label" x-show="terms==='temporary'">
                          <label class="input-item-label">Duration (In days)</label>
                          <input class="input-bordered" x-model="duration" type="number" min="0" placeholder="Ban Duration" name="duration">
                      </div>
  
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
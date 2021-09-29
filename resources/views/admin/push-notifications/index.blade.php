@extends('layouts.app')

@section('title', 'Cardvest Admin - Push Campaign')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 aside sidebar-left ">
        @include('partials.admin_sidebar')

      </div>

      <div class="col-lg-9">
        <div class="token-calculator card h-100">
          <div class="card-innr">
            <div class="card-head">
              <h4 class="card-title">Create Campaign</h4>
            </div>
            <form action="{{ route('push-notification-now') }}" method="post">
              @csrf
              <input type="hidden" name="role" value="admin">
              <div class="input-item input-with-label">
                <label class="input-item-label">Campaign Title</label>
                <input class="input-bordered" type="text" name="title" placeholder="Title "
                  required>
              </div>
              
              <div class="input-item input-with-label">
                <label class="input-item-label">Campaign Content</label>
                <textarea class="input-bordered" name="body" placeholder="Campaign Content"></textarea>
              </div>

              <input type="hidden" name="channel" value="notifications">

              <div class="token-buy text-center">
                <button class="btn btn-primary">Create Campaign</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script>

</script>
@endpush
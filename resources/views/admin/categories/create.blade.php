@extends('layouts.app')

@section('title', 'Cardvest - Create Category ')
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
            <div class="card-head d-flex justify-content-between align-items-center">
              <h4 class="card-title">Add new category</h4>
              <div class="d-flex align-items-center guttar-20px">
                <div class="flex-col d-sm-block d-none">
                  <a href="{{ route('categories.index') }}" class="btn btn-sm btn-auto btn-primary"><em
                      class="fas fa-arrow-left mr-3"></em>Back</a>
                </div>
                <div class="flex-col d-sm-none">
                  <a href="{{ route('categories.index') }}" class="btn btn-icon btn-sm btn-primary"><em
                      class="fas fa-arrow-left"></em></a>
                </div>

              </div>
            </div>

            <form action="{{ route('categories.store') }}" method="post">
              @csrf
              <div class="row">
                <div class="col-md-12">
                  <div class="input-item input-with-label">
                    <label for="name" class="input-item-label">Category Name</label>
                    <input class="input-bordered" type="text" id="name" name="name" placeholder="Category name/title"
                      value="{{ old('name') }}">
                  </div><!-- .input-item -->
                </div>
              </div><!-- .row -->

              <div class="gaps-1x"></div><!-- 10px gap -->
              <div class="d-sm-flex justify-content-between align-items-center">
                <button class="btn btn-primary">Add Category</button>
                <div class="gaps-2x d-sm-none"></div>
              </div>
            </form><!-- form -->
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
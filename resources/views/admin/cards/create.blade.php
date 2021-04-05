@extends('layouts.app')

@section('title', 'Cardvest - Edit {{ $card->name }} Gift Card ')
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
              <h4 class="card-title">Add Gift Card</h4>
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

            <form action="{{ route('cards.store') }}" method="post">
              @csrf
              <div class="row">
                <div class="col-md-12">
                  <div class="input-item input-with-label">
                    <label for="name" class="input-item-label">Card Name</label>
                    <input class="input-bordered" type="text" id="name" name="name" placeholder="Gift Card name/title"
                      value="{{ old('name') }}">
                  </div><!-- .input-item -->
                </div>

                <div class="col-md-6">
                  <div class="input-item input-with-label">
                    <label for="category" class="input-item-label">Category <small class="text-danger">*</small></label>
                    <select class="select-bordered select-block" name="category" id="category">
                      <option>Select</option>
                      @foreach($categories as $category)
                      @if(is_null(request()->query('category')))
                      <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                      @else
                      <option value="{{ $category->id }}"
                        {{ request()->query('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                      @endif
                      @endforeach
                    </select>
                  </div><!-- .input-item -->
                </div><!-- .col -->

                <div class="col-md-6">
                  <div class="input-item input-with-label">
                    <label for="rate" class="input-item-label">Rate</label>
                    <input class="input-bordered" type="number" min="0" steps="0.01" id="rate" name="rate"
                      placeholder="Rate per USD" value="{{ old('rate') }}">
                  </div><!-- .input-item -->
                </div>
                <div class="col-md-3">
                  <div class="input-item input-with-label">
                    <label for="min" class="input-item-label">Min</label>
                    <input class="input-bordered" type="number" id="min" name="min" palceholder="Mininum unit"
                      value="{{ old('min') }}">
                  </div><!-- .input-item -->
                </div>
                <div class="col-md-3">
                  <div class="input-item input-with-label">
                    <label for="max" class="input-item-label">Max</label>
                    <input class="input-bordered" type="number" id="min" name="max" palceholder="Maximum unit"
                      value="{{ old('max') }}">
                  </div><!-- .input-item -->
                </div><!-- .col -->

              </div><!-- .row -->

              <h4 class="my-1">Card Type <small class="text-danger">*</small></h4>
              <div class="row">
                <div class="col-md-3">
                  <div class="input-item">
                    <input type="radio" class="input-radio" name="type" id="sell" value="sell"
                      {{ old('type') === 'sell' ? 'checked' : '' }}>
                    <label for="sell">Sell</label>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="input-item">
                    <input type="radio" class="input-radio" name="type" id="buy" value="buy"
                      {{ old('type') === 'buy' ? 'checked' : '' }}>
                    <label for="buy">Buy</label>
                  </div>
                </div>
              </div>
              <div class="gaps-1x"></div><!-- 10px gap -->
              <div class="d-sm-flex justify-content-between align-items-center">
                <button class="btn btn-primary">Add Card</button>
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
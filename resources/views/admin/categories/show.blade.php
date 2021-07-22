@extends('layouts.app')

@section('title', 'Cardvest - {{ $category->name }} Gift Card ')
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
              <h4 class="card-title">{{ $category->name }} Gift Cards</h4>
              <div class="d-flex align-items-center guttar-20px">
                <div class="flex-col d-sm-block d-none">
                  <a href="{{ route('cards.create', ['category' => $category->id]) }}"
                    class="btn btn-sm btn-auto btn-primary"><em class="fas fa-plus mr-3"></em>Add Card</a>
                </div>
                <div class="flex-col d-sm-none">
                  <a href="{{ route('cards.create', ['category' => $category->id]) }}"
                    class="btn btn-icon btn-sm btn-primary"><em class="fas fa-plus"></em></a>
                </div>

              </div>
              <div class="d-flex align-items-center guttar-20px">
                <div class="flex-col d-sm-block d-none">
                  <a href="{{ route('categories.index') }}" class="btn btn-sm btn-auto btn-primary"><em
                      class="fas fa-arrow-left mr-3"></em>Back</a>
                </div>
                <div class="flex-col d-sm-none">
                  <a href="user-list.html" class="btn btn-icon btn-sm btn-primary"><em
                      class="fas fa-arrow-left"></em></a>
                </div>

              </div>
            </div>
            <table class="data-table user-list">
              <thead>
                <tr class="data-item data-head">
                  <th class="data-col dt-user">Name</th>
                  <th class="data-col dt-email">Rate</th>
                  <th class="data-col dt-email">Threshold</th>
                  <th class="data-col dt-email">Type</th>
                  <th class="data-col"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($cards as $card)
                <tr class="data-item {{ $card->active ? '' : 'table-danger' }}">
                  <td class="data-col dt-tnxno">
                    <div class="d-flex align-items-center">
                      <div class="fake-class">
                        <span class="lead tnx-id">{{ $card->name }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="data-col dt-token">
                    <span class="lead token-amount">&#8358;{{ to_money($card->rate) }}</span>
                    <span class="sub sub-symbol">per USD</span>
                  </td>
                  <td class="data-col dt-account">
                    <span class="lead user-info">Min: {{ $card->min ?? '-' }}</span>
                    <span class="sub sub-date">Max: {{ $card->max ?? '-' }}</span>
                  </td>
                  <td class="data-col dt-type">
                    <span
                      class="dt-type-md badge badge-outline badge-{{ get_description($card->type) }} badge-md text-capitalize">{{ $card->type }}</span>
                  </td>
                  <td class="data-col text-right">
                    <div class="relative d-inline-block">
                      <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em
                          class="ti ti-more-alt"></em></a>
                      <div class="toggle-class dropdown-content dropdown-content-top-left">
                        <ul class="dropdown-list">
                          <li><a href="{{ route('cards.edit', $card) }}"><em class="ti ti-eye"></em> Edit Card</a></li>
                          <li><a href="#" onclick="disableCard(event, {{ $card->id }})"><em class="fa fa-{{ $card->active ? 'ban' : 'check' }}"></em>
                          {{ $card->active ? 'Disable' : 'Enable' }}</a>
                          </li>
                          <li><a href="#" onclick="deleteCard(event, {{ $card->id }})"><em class="ti ti-trash"></em>
                              Delete</a>
                          </li>
                          <form action="{{ route('cards.destroy', $card) }}" method="post"
                            id="card-delete-{{ $card->id }}">
                            @csrf
                            @method('delete')
                          </form>
                          <form action="{{ route('cards.disable', $card) }}" method="post"
                            id="card-disable-{{ $card->id }}">
                            @csrf
                            @method('delete')
                          </form>
                          <!-- <li><a href="#"><em class="ti ti-na"></em> Cancel</a></li>
                      <li><a href="#"><em class="ti ti-trash"></em> Delete</a></li> -->
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
                        <span class="lead tnx-id text-danger">No gift card in category!</span>
                      </div>
                    </div>
                  </td>
                </tr><!-- .data-item -->
                @endforelse
              </tbody>
            </table>
          </div><!-- .card-innr -->
          <div class="card-footer bg-white">
            {{ $cards->links() }}
          </div>
        </div><!-- .card -->
      </div>
    </div>
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()

@push('scripts')
<script>
let deleteCard = function(event, id) {
  event.preventDefault()
  var form = $('#card-delete-' + id);
  swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this card!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        swal("Card removed!", {
          icon: "success",
        });
        form.submit();
      } else {

      }
    });
}

let disableCard = function(event, id) {
  event.preventDefault()
  var form = $('#card-disable-' + id);
  swal({
      title: "Are you sure?",
      text: "This action toggles the cards availability.",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDisable) => {
      if (willDisable) {
        form.submit();
      } else {

      }
    });
}
</script>
@endpush
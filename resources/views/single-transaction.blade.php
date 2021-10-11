@extends('layouts.app')

@section('title', 'Cardvest - Transactions')
@section('content')
<div class="page-content">
  <div class="container">
    <div class="card content-area">
      <div class="card-innr">
        <div class="card-head d-flex justify-content-between align-items-center">
          <h4 class="card-title mb-0">Transaction Details</h4>
          <a href="{{ route('transaction.index') }}" class="btn btn-sm btn-auto btn-primary d-sm-block"><em
              class="fas fa-arrow-left mr-3"></em>Back</a>
        </div>
        <div class="gaps-1-5x"></div>
        <div class="data-details d-md-flex">
          <div class="fake-class">
            <span class="data-details-title">Reference</span>
            <span class="data-details-info">
              <strong>{{ $transaction->reference }}</strong>
            </span>
          </div>

          <div class="fake-class">
            <span class="data-details-title">Transaction Date</span>
            <span class="data-details-info">
              {{ $transaction->getDate() }} {{ $transaction->getTime() }}
            </span>
          </div>
          <div class="fake-class">
            <span class="data-details-title">Status</span>
            <span
              class="dt-type-md badge badge-{{ $transaction->getDescription($transaction->status) }} badge-md text-capitalize">{{ $transaction->status }}</span>
          </div>
          <div class="fake-class">
            <span class="data-details-title">Last Updated</span>
            <span class="data-details-info">
              {{ $transaction->getDate() }} {{ $transaction->getTime() }}
            </span>
          </div>
        </div>
        <div class="gaps-3x"></div>
        <h6 class="card-sub-title">Transaction Info</h6>
        <ul class="data-details-list">
          <li>
            <div class="data-details-head">Transaction Type</div>
            <div class="data-details-des text-capitalize"><strong>{{ $transaction->type }}</strong></div>
          </li><!-- li -->
          @if($transaction->type !== 'payout')
          <li>
            <div class="data-details-head">Gift Card Category</div>
            <div class="data-details-des"><strong>{{ $transaction->card->category->name }}</strong></div>
          </li><!-- li -->
          <li>
            <div class="data-details-head">Gift Card</div>
            <div class="data-details-des"><strong>{{ $transaction->card->name }}</strong></div>
          </li><!-- li -->
          <li>
            <div class="data-details-head">Rate</div>
            <div class="data-details-des"><span>{{ $transaction->card->rate }}/$</span>
              <span></span>
            </div>
          </li><!-- li -->
          <li>
            <div class="data-details-head">Unit</div>
            <div class="data-details-des"><span>{{ $transaction->unit ?? '-' }}</span>
              <span></span>
            </div>
          </li><!-- li -->
          <li>
            <div class="data-details-head">Total Amount</div>
            <div class="data-details-des"><span>&#8358;{{ to_naira($transaction->amount) }}</span> <span></span></div>
          </li><!-- li -->
          <li>
            <div class="data-details-head">Users comment</div>
            <div class="data-details-des">{{ $transaction->comment ?? '-' }}</div>
          </li><!-- li -->
          @else
          <li>
            <div class="data-details-head">Amount</div>
            <div class="data-details-des"><span>&#8358;{{ to_naira($transaction->amount) }}</span> <span></span></div>
          </li><!-- li -->
          <li>
            <div class="data-details-head">New Balance</div>
            <div class="data-details-des"><span>&#8358;{{ to_naira($transaction->balance) }}</span> <span></span></div>
          </li><!-- li -->
          @endif
        </ul><!-- .data-details -->
        @if($transaction->type !== 'payout')
        <div class="gaps-3x"></div>
        <h6 class="card-sub-title">Transaction Feedback</h6>
        <ul class="data-details-list">
          <li>
            <div class="data-details-head">Admin Feedback</div>
            <div class="data-details-des d-flex flex-column">
              <strong>{{ $transaction->admin_comment ?? '-' }}</strong>
              @if($transaction->admin_images)
              <p class="text-primary">View Image Uploaded by Amdin</p>
              <img src="{!! Storage::url(''.$transaction->admin_images) !!}" href="{!! Storage::url(''.$transaction->admin_images) !!}" class="image-popup img-thumbnail" width="100" alt="">
              @endif
            </div>
          </li>
        </ul>
        @endif
      </div>
    </div><!-- .card -->
  </div><!-- .container -->
</div><!-- .page-content -->
@endsection()
@extends('layouts.app')

@section('title', 'Admin Activity Tracking')
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
                                <h4 class="card-title">Admin Activity</h4>
                            </div>
                            <div class="card-text">
                                <p>Here are the recent activities by the admins.</p>
                            </div>
                            <div class="gaps-1-5x"></div>
                            <table class="data-table activity-table" data-items="10">
                                <thead>
                                    <tr class="data-item data-head">
                                        <th class="data-col activity-time"><span>Date</span></th>
                                        <th class="data-col activity-device"><span>Admin</span></th>
                                        <th class="data-col activity-device"><span>Transaction</span></th>
                                        <th class="data-col activity-browser"><span>Changes</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($activities as $activity)
                                    @if ($activity->description == 'created') @continue @endif
                                    @php
                                        $transaction = $activity->subject;
                                    @endphp
                                    <tr class="data-item">
                                        <td class="data-col activity-time">
                                            <span class="sub sub-date">{{ $transaction->getDate() }}</span>  <br>
                                            <span class="sub sub-date">{{ $transaction->getTime() }}</span>
                                        </td>
                                        <td class="data-col activity-device">
                                            {{ $activity->causer->username }} <br>
                                            <span class="badge badge-secondary text-uppercase badge-{{ $activity->description == 'viewed' ? 'info' : '' }}">{{ config('activities')[$activity->description] }}</span>
                                        </td>
                                        <td class="data-col activity-device">
                                            {{ $transaction->reference }} <br>
                                        </td>
                                        <td class="data-col activity-browser">
                                            @if($activity->changes && $activity->changes->count())
                                            @foreach($activity->changes['old'] as $key => $value)
                                                    @php
                                                        $newValue = $activity->changes['attributes'][$key];
                                                    @endphp
                                                    <span class="badge badge-primary">{{ $key }}</span>
                                                    @if($key == 'amount')
                                                        <span class="badge badge-warning">{{ to_naira($value) }} ---->
                                                        {{ to_naira($newValue) }}</span> <br>
                                                    @else
                                                        <span class="badge badge-warning text-uppercase">{{ $value ?? "[Nil]  " }} ---->
                                                        {{ $newValue }}</span> <br>
                                                    @endif
                                            @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div><!-- .card-innr -->
                        <div class="card-footer bg-white">
                            {{ $activities->links() }}
                        </div>
                    </div><!-- .card -->
                </div>
            </div>
        </div><!-- .container -->
    </div><!-- .page-content -->
@endsection

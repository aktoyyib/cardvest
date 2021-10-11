<div class="card card-navs">
  <div class="card-innr">
    <div class="card-head d-none d-lg-block">
      <h6 class="card-title card-title-md">Administration</h6>
    </div>
    <ul class="sidebar-nav">
        <li><a href="{{ route('users.index') }}"><em class="ikon ikon-user"></em> Users</a></li>
        <li><a href="{{ route('roles.index') }}"><em class="ikon fa fa-2x fa-users"></em> Roles</a></li>
        @if(auth()->user()->hasRole('Super Admin'))
        <li><a href="{{ route('activities.index') }}"><em class="ikon fa fa-2x fa-users"></em> Admin Activities</a></li>
        @endif
        <li><a href="{{ route('transactions.index') }}"><em class="ikon ikon-user"></em> Transactions</a></li>
        <li><a href="{{ route('withdrawals.index') }}"><em class="ikon ikon-user"></em> Withdrawals</a></li>
        <li><a href="{{ route('categories.index') }}"><em class="ikon ikon-exchange"></em> Gift Cards</a></li>
        <li><a href="{{ route('transactions.create') }}"><em class="ikon ikon-exchange"></em> Send Funds</a></li>
        <li><a href="{{ route('push-notification-campaign') }}"><em class="ikon fa fa-bell"></em> Create Campaign</a></li>
    </ul>
  </div>
</div>

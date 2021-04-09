<div class="topbar-wrap">
  <div class="topbar is-sticky">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <ul class="topbar-nav d-lg-none">
          <li class="topbar-nav-item relative">
            <a class="toggle-nav" href="#">
              <div class="toggle-icon">
                <span class="toggle-line"></span>
                <span class="toggle-line"></span>
                <span class="toggle-line"></span>
                <span class="toggle-line"></span>
              </div>
            </a>
          </li><!-- .topbar-nav-item -->
        </ul><!-- .topbar-nav -->
        <a class="topbar-logo" href="./">
          <img src="{{ asset('images/logo.png') }}" alt="logo">
        </a>
        @auth()
        <ul class="topbar-nav">
          <li class="topbar-nav-item relative">
            <span class="user-welcome d-none d-lg-inline-block">Welcome! {{ auth()->user()->username }}</span>
            <a class="toggle-tigger user-thumb" href="#"><em class="ti ti-user"></em></a>
            <div class="toggle-class dropdown-content dropdown-content-right dropdown-arrow-right user-dropdown">
              <div class="user-status">
                <h6 class="user-status-title">balance</h6>
                <div class="user-status-balance">{{ to_naira(auth()->user()->balance()) }} <small>NGN</small></div>
              </div>
              <ul class="user-links">
                <li><a href="{{ route('profile') }}"><i class="ti ti-id-badge"></i>My Profile</a></li>
              </ul>
              <ul class="user-links bg-light">
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();"><i
                      class="ti ti-power-off"></i>Logout</a></li>
              </ul>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
          </li><!-- .topbar-nav-item -->
        </ul><!-- .topbar-nav -->
        @endauth
      </div>
    </div><!-- .container -->
  </div><!-- .topbar -->
  <div class="navbar">
    <div class="container">
      <div class="navbar-innr">
        <ul class="navbar-menu">
          <li><a href="{{ route('home') }}"><em class="ikon ikon-dashboard"></em> Dashboard</a></li>
          <li><a href="{{ route('transaction.create') }}"><em class="ikon ikon-coins"></em> Trade Card</a></li>
          <!-- <li><a href="ico-distribution.html"><em class="ikon ikon-distribution"></em> ICO Distribution</a></li> -->
          <li><a href="{{ route('transaction.index') }}"><em class="ikon ikon-transactions"></em> Transactions</a></li>
          <li><a href="{{ route('profile') }}"><em class="ikon ikon-user"></em> Profile</a></li>
          <li><a href="{{ route('referrals') }}"><em class="ikon ikon-user"></em> Referrals</a></li>
          @hasanyrole('admin')
          <li><a href="{{ route('admin.dashboard') }}"><em class="ikon ikon-user"></em> Admin Dashboard</a></li>
          @endhasanyrole
        </ul>


      </div><!-- .navbar-innr -->
    </div><!-- .container -->
  </div><!-- .navbar -->
</div><!-- .topbar-wrap -->
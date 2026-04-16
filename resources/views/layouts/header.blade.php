<header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini">P</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>POS</b></span>
</a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
    <li class="dropdown user user-menu">
    
    <ul class="dropdown-menu">
       
        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-left">
                <a href="{{ route('user.profil') }}" class="btn btn-primary btn-flat">My Profile</a>
            </div>
            <div class="pull-right">
                <a href="#" class="btn btn-danger btn-flat"
                    onclick="$('#logout-form').submit()"><i class="fa fa-power-off"></i> Logout</a>
            </div>
        </li>
    </ul>
</li>

            </ul>
        </div>
    </nav>
</header>

<form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
    @csrf
</form>
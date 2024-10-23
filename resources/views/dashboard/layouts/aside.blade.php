<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('admin/dashboard') }}" class="brand-link">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" width="65px" style="opacity: .8; border-radius: 50%">
        <span class="brand-text font-weight-light">&nbsp;&nbsp;Betycor Trans</span>
    </a>
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
         <span class="brand-text font-weight-light" style="color: white"><i class="nav-icon far fa-user"></i>&nbsp;&nbsp;&nbsp;{{ Auth::user()->name}}</span>

        </div>
      </div>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="{{ url('admin/dashboard') }}" class="nav-link @if(Request::segment('2') == "dashboard") active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/employes/lister') }}" class="nav-link @if(Request::segment('2') == "employes")  active @endif">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Employé</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/transports/lister') }}" class="nav-link @if(Request::segment('2') == "transports") active @endif">
                        <i class="nav-icon fas fa-bus"></i>
                        <p>Transport</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p style="color: red">Se déconnecter</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

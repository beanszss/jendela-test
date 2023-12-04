<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Jendela<sup>360</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ is_null(Request::segment(2))  ? "active" : ""  }}">
        <a class="nav-link" href="{{ url("/dashboard") }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data
    </div>

    <li class="nav-item {{ Request::segment(2) == "cars" ? "active" : ""  }}">
        <a class="nav-link" href="{{ url("/dashboard/cars") }}">
            <i class="fa fa-car"></i>
            <span>List Cars</span></a>
    </li>

    <li class="nav-item {{ Request::segment(2) == "order" ? "active" : ""  }}">
        <a class="nav-link" href="{{ url("/dashboard/order") }}">
            <i class="fa fa-car"></i>
            <span>List Order</span></a>
    </li>

    @role('super-admin')
    <li class="nav-item {{ Request::segment(2) == "user" ? "active" : ""  }}">
        <a class="nav-link" href="{{ url("/dashboard/user") }}">
            <i class="fa fa-shopping-bag"></i>
            <span>List Users</span></a>
    </li>
    @endrole

    <hr class="sidebar-divider">
</ul>
<!-- End of Sidebar -->

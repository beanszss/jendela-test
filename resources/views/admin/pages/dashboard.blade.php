@extends("admin.layouts")
@section("title", "Dashboard")
@section("content-title", "Dashboard")
@section("content")
<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Books</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $books ?? 0 }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Authors</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $authors ?? 0 }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-shopping-bag  fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Admin/User</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users ?? 0 }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-shopping-basket fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

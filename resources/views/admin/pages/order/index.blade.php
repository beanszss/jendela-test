@extends("admin.layouts")
@section("title", "Orders")
@section("content-title", "Order List")
@section('css')
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
@endsection
@section("content")

<!-- DataTales Example -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
        @role('super-admin')
            <h6 class="m-0 font-weight-bold text-primary">List Orders <span class="float-right"> <a href="{{ url("dashboard/order/create") }}" class="btn btn-success btn-sm" ><i class="fa fa-plus"> Add Order</i></a></span></h6>
        @endrole
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-books">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Customer Phone</th>
                            <th>Cars Order</th>
                            @role('super-admin')
                                <th>Action</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->email }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>{{ $order->cars()->pluck("cars_name")->implode(", ") }}</td>
                                @role('super-admin')
                                    <td>
                                        <a href="{{ url('dashboard/order/' . $order['id'] . '/edit') }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a>
                                        <form action="{{ url('dashboard/order/' . $order['id']) }}" method="POST">
                                            @csrf
                                            @method("delete")
                                            <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button></button>
                                        </form>
                                    </td>
                                @endrole
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection
@section('js')
<script>
    $(document).ready(function () {
        $('#table-books').DataTable();
    });
</script>
@endsection

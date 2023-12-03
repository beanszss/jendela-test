@extends("admin.layouts")
@section("title", "Stock Cars")
@section("content-title", "Car List")
@section('css')
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
@endsection
@section("content")

    <!-- DataTales Example -->
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                @role('super-admin')
                <h6 class="m-0 font-weight-bold text-primary">List Cars <span class="float-right"> <a
                            href="{{ url("dashboard/cars/create") }}" class="btn btn-success btn-sm"><i
                                class="fa fa-plus"> Add Cars</i></a></span></h6>
                @endrole
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table-cars">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            @role('super-admin')
                            <th>Action</th>
                            @endrole
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($cars as $car)
                            <tr>
                                <td>{{ $car->id }}</td>
                                <td>{{ $car->cars_name }}</td>
                                <td>Rp. {{ number_format($car->price) }}</td>
                                <td>{{ $car->stock }}</td>
                                @role('super-admin')
                                <td>
                                    <a href="{{ url('dashboard/cars/' . $car['id'] . '/edit') }}"
                                       class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a>
                                    <form action="{{ url('dashboard/cars/' . $car['id']) }}" method="POST">
                                        @csrf
                                        @method("delete")
                                        <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete
                                        </button>
                                        </button>
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
            $('#table-cars').DataTable();
        });
    </script>
@endsection

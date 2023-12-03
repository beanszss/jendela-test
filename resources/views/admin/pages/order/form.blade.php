@extends("admin.layouts")
@section('title', 'Order Form')
@section('content-title', 'Order Car')
@section('css')
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            /* padding: 10px 16px; */
            padding: 5px;
            /* font-size: 18px;  */
            line-height: 1.33;
            border-radius: 6px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            top: 75% !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px !important;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #CCC !important;
            box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset;
            transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
        }

        .delete-price-type-range-field {
            color: red;
        }
        .delete-price-type-range-field:hover {
            color: red;
            cursor: pointer;
        }

    </style>
@endsection
@section('content')

    <!-- DataTales Example -->
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Car</h6>
            </div>
            <div class="card-body">
                <form action="{{ Request::segment(3) == "create" ? url("dashboard/order") : url("dashboard/order/" . $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(Request::segment(3) != "create")
                        <input type="hidden" name="_method" value="PUT">
                    @endif
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="customer_name">Customer Name</label>
                            <input type="text" name="customer_name" value="{{ $order->customer_name ?? old("customer_name") }}" class="form-control" id="title" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="text" name="email" value="{{ $order->email ?? old("email") }}" class="form-control" id="publisher" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" value="{{ $order->phone ?? old("phone") }}" class="form-control" id="publisher" required>
                        </div>
                    </div>

                    <div class="form-row mb-2">
                        <label for="cars">Cars<a href="{{ url("dashboard/cars/create") }}" target="_blank"  class="btn btn-success btn-sm ml-2" ><i class="fa fa-plus"></i></a></label>
                        <select name="cars[]" id="cars" class="form-control" multiple="multiple" required>
                            @foreach ($cars as $car)
                                @if(isset($order))
                                    <option value="{{ $car->id }}"
                                        @foreach ($order->cars as $a)
                                            {{ $a->cars_name == $car->cars_name ? "selected" : "" }}
                                        @endforeach>
                                        {{ $car->cars_name }}
                                    </option>
                                @else
                                    <option value="{{ $car->id }}">{{ $car->cars_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#cars').select2();
    });
</script>
@endsection

@extends("admin.layouts")
@section('title', 'Cars Form')
@section('content-title', 'Form Cars')
@section('css')
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
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
                <h6 class="m-0 font-weight-bold text-primary">Form Cars</h6>
            </div>
            <div class="card-body">
                <form
                    action="{{ Request::segment(3) == "create" ? url("dashboard/cars") : url("dashboard/cars/" . $car->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(Request::segment(3) != "create")
                        <input type="hidden" name="_method" value="PUT">
                    @endif
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Cars Name</label>
                            <input type="text" name="cars_name" value="{{ $car->cars_name ?? old("cars_name") }}"
                                   class="form-control" id="title" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="name">Price</label>
                            <input type="text" name="price" value="{{ $car->price ?? old("price") }}"
                                   class="form-control" id="title" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="name">Stock</label>
                            <input type="text" name="stock" value="{{ $car->stock ?? old("stock") }}"
                                   class="form-control" id="title" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection

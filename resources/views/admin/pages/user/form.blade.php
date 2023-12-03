@extends("admin.layouts")
@section('title', 'Book Form')
@section('content-title', 'Book Author')
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
                <h6 class="m-0 font-weight-bold text-primary">Book Author</h6>
            </div>
            <div class="card-body">
                <form action="{{ url("dashboard/user") }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">email</label>
                            <input type="text" name="email" value="{{ $book->email ?? old("email") }}" class="form-control" id="email" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Name</label>
                            <input type="text" name="name" value="{{ $user->name ?? old("name") }}" class="form-control" id="name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="text" name="password" value="{{ old("password") }}" class="form-control" id="password" required>
                        </div>
                    </div>

                    <div class="form-row mb-2">
                        <label for="roles">Roles</label>
                        <select name="roles" id="roles" class="form-control" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
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
        $('#roles').select2();
    });
</script>
@endsection

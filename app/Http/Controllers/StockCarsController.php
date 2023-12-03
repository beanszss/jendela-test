<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Repository\StockCarsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockCarsController extends Controller
{
    public function __construct()
    {
        $this->middleware('super-admin')->except('index');
    }

    public function index()
    {
        $cars = StockCarsRepository::listStockCars();

        return view('admin.pages.stock-cars.index', compact('cars'));

    }

    public function create()
    {

        return view('admin.pages.stock-cars.form');
    }

    public function edit(Cars $car)
    {
        return view('admin.pages.stock-cars.form', compact('car'));
    }

    public function store()
    {
        $response = StockCarsRepository::createOrUpdateCars();

        if (!$response["status"]) {
            alertNotify(false, $response["data"]);
            return back();
        }

        alertNotify(true, $response["data"]);
        return redirect(url("dashboard/cars"));
    }

    public function update($id)
    {
        $response = StockCarsRepository::createOrUpdateCars($id);

        if (!$response["status"]) {
            alertNotify(false, $response["data"]);
            return back();
        }

        alertNotify(true, $response["data"]);
        return redirect(url("dashboard/cars"));
    }

    public function destroy($id)
    {
        $response = StockCarsRepository::destroyStockCars($id);
        if (!$response['status']) {
            alertNotify(false, $response['data']);
            return back();
        }
        alertNotify(true, $response["data"]);
        return back();
    }

    public function listStockCars(Request $request)
    {
        $cars = Cars::paginate(10);
        $superAdmin = Auth::user()->hasRole('super-admin');
        $arrData = collect([]);

        $cars->each(function ($q) use ($arrData, $superAdmin) {
            $arrData->push([
                $q->id ?? "",
                $q->cars_name ?? "",
                number_format($q->price) ?? "",
                $q->stock ?? "",
                $superAdmin ? '<a href="' . url('dashboard/cars/' . $q['id'] . '/edit') . '" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edits</a>' .
                    '<form action="' . url('dashboard/cars/' . $q['id']) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button></button>
                </form>'
                    : "-"
            ]);
        });

        return response()->json([
            "draw" => $request->get("draw"),
            "recordsTotal" => $cars->total(),
            "recordsFiltered" => $cars->total(),
            "data" => $arrData->toArray()
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Repository\StockCarsRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class StockCarsController extends Controller
{
    public function __construct()
    {
        $this->middleware('super-admin')->except('index');
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $cars = StockCarsRepository::listStockCars();

        return view('admin.pages.stock-cars.index', compact('cars'));

    }

    /**
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {

        return view('admin.pages.stock-cars.form');
    }

    /**
     * @param Cars $car
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function edit(Cars $car): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('admin.pages.stock-cars.form', compact('car'));
    }

    /**
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function store(): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $response = StockCarsRepository::createOrUpdateCars();

        if (!$response["status"]) {
            alertNotify(false, $response["message"]);
            return back();
        }

        alertNotify(true, $response["message"]);
        return redirect(url("dashboard/cars"));
    }

    /**
     * @param $id
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     */
    public function update($id): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $response = StockCarsRepository::createOrUpdateCars($id);

        if (!$response["status"]) {
            alertNotify(false, $response["message"]);
            return back();
        }

        alertNotify(true, $response["message"]);
        return redirect(url("dashboard/cars"));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $response = StockCarsRepository::destroyStockCars($id);
        if (!$response['status']) {
            alertNotify(false, $response['message']);
            return back();
        }
        alertNotify(true, $response["message"]);
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listStockCars(Request $request): \Illuminate\Http\JsonResponse
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

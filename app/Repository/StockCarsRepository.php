<?php

namespace App\Repository;

use App\Models\Cars;
use App\Models\OrderCars;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StockCarsRepository
{
    public static function listStockCars()
    {
        try {
            return Cars::orderByDesc('id')->get();
        } catch (\Throwable $th) {
            return collect([]);
        }
    }

    public static function findStockCarsById($id)
    {
        return Cars::find($id);
    }

    public static function createOrUpdateCars($id = null)
    {
        try {
            $validate = self::validate($id);

            if (!$validate['status']) {
                return $validate;
            }

            Cars::updateOrCreate(
                ['id' => $id],
                [
                    'cars_name' => request()->get('cars_name'),
                    'price' => request()->get('price'),
                    'stock' => request()->get('stock')
                ]
            );

            return responseCustom("Success " . (!$id ? "Add" : "Update") . " Cars", true);

        } catch (\Throwable $th) {
            return responseCustom($th->getMessage(), false);
        }
    }

    public static function destroyStockCars($id)
    {
        try {
            $cars = self::findStockCarsById($id);

            if (!$cars) {
                return responseCustom("cars not found", false);
            }

            $orderCars = OrderCars::where('cars_id', $id)->count();

            if ($orderCars > 0) {
                return responseCustom("Cannot delete cars, becaue cars has order", false);
            }

            $cars->delete();

            return responseCustom("Success Delete Cars", true);
        } catch (\Throwable $th) {
            return responseCustom($th->getMessage());
        }
    }

    public static function validate($id = null)
    {
        try {
            $validation = Validator::make(request()->all(), [
               'cars_name' => 'required|string|unique:cars,cars_name,' . $id,
               'price' => 'required|integer',
               'stock' => 'required|integer'
            ]);

            if ($validation->fails()) {
                return responseCustom(collect($validation->errors()->all())->implode(' , '), false);
            }

            return responseCustom("Validation Success", true);
        } catch (\Throwable $th) {
            return responseCustom($th->getMessage(), false);
        }
    }

//    public static function ajaxStockCars()
//    {
//        $cars = Cars::paginate(10);
//        $superAdmin = Auth::user()->hasRole('super-admin');
//        $arrData = collect([]);
//
//        $cars->each(function ($q) use ($arrData, $superAdmin) {
//            $arrData->push([
//                $q->id ?? "",
//                $q->cars_name ?? "",
//                number_format($q->price) ?? "",
//                $q->stock ?? "",
//                $superAdmin ? '<a href="' . url('dashboard/stock-cars/' . $q['id'] . '/edit') . '" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a>' .
//                    '<form action="' . url('dashboard/stock-cars/' . $q['id']) . '" method="POST">
//                    <input type="hidden" name="_method" value="DELETE">
//                    <input type="hidden" name="_token" value="' . csrf_token() . '">
//                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button></button>
//                </form>'
//                    : "-"
//            ]);
//        });
//
//        return response()->json([
//            'draw' => request()->get('draw'),
//            "recordsTotal" => $cars->total(),
//            "recordsFiltered" => $cars->total(),
//            "data" => $arrData->toArray()
//        ]);
//    }

}

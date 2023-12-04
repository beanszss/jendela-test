<?php

namespace App\Repository;

use App\Models\Cars;
use App\Models\OrderCars;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StockCarsRepository
{
    /**
     * @return Collection
     */
    public static function listStockCars()
    {
        try {
            return Cars::orderByDesc('id')->get();
        } catch (\Throwable $th) {
            return collect([]);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function findStockCarsById($id)
    {
        return Cars::find($id);
    }

    /**
     * @param $id
     * @return array
     */
    public static function createOrUpdateCars($id = null): array
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

            return responseCustom([], "Success " . (!$id ? "Add" : "Update") . " Cars", true);

        } catch (\Throwable $th) {
            return responseCustom($th->getMessage(), null,false);
        }
    }

    /**
     * @param $id
     * @return array
     */
    public static function destroyStockCars($id): array
    {
        try {
            $cars = self::findStockCarsById($id);

            if (!$cars) {
                return responseCustom([], "cars not found", false);
            }

            $orderCars = OrderCars::where('cars_id', $id)->count();

            if ($orderCars > 0) {
                return responseCustom([], "Cannot delete cars, becaue cars has order", false);
            }

            $cars->delete();

            return responseCustom("Success Delete Cars", null,true);
        } catch (\Throwable $th) {
            return responseCustom($th->getMessage());
        }
    }

    /**
     * @param $id
     * @return array
     */
    public static function validate($id = null): array
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

            return responseCustom([], "Validation Success", true);
        } catch (\Throwable $th) {
            return responseCustom($th->getMessage(), null,false);
        }
    }
}

<?php

namespace App\Repository;

use App\Mail\Invoice;
use App\Models\Book;
use App\Models\BookAuthor;
use App\Models\OrderCars;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderRepository
{

    public static function listOreders()
    {
        try {
            return Orders::with(['cars'])
                ->orderByDesc('id')
                ->get();
        } catch (\Throwable $th) {
            return collect([]);
        }
    }

    public static function createOrUpdateOrder($id = null)
    {
        try {
            $validate = self::validate($id);
            $bodyParams = request()->all();
            if(!$validate["status"]) {
                return $validate;
            }


            DB::beginTransaction();

            $order = Orders::updateOrCreate(
                ["id" => $id],
                [
                    "customer_name" => $bodyParams["customer_name"],
                    "email" => $bodyParams["email"],
                    "phone" => $bodyParams["phone"]
                ]
            );

            if($id && $order->cars->isNotEmpty()) {
                OrderCars::where("orders_id", $id)->delete();
            }

            $order->cars()->attach($bodyParams["cars"]);
            DB::commit();
//            $order->with('cars');
            $car = [];
            collect($order->cars)->each(function ($item, $key) use (&$car) {
                $car['name'] = $item->cars_name;
                $car['price'] = number_format($item->price);
            });

            $arrData = [
                'order' => $order,
                'car' => $car
            ];

            return responseCustom($arrData, "Success ".(!$id ? "Add" . $id : "Update")." Order", true);
        } catch (\Throwable $th) {
            DB::rollback();
            return responseCustom($th->getMessage(), null, false);
        }
    }

    public static function validate($id = null) {
        try {
            $validation = Validator::make(request()->all(), [
                "customer_name" => "required|string|unique:order,customer_name," . $id,
                "email" => "required|string",
                "phone" => "required|string"
            ]);


            if($validation->fails()) {
                return responseCustom(collect($validation->errors()->all())->implode(' , '), null, false);
            }

            return responseCustom(null, "Validation Success", true);
        } catch (\Throwable $th) {
            return responseCustom($th->getMessage(), null, false);
        }
    }

    public static function destroyOrder($id)
    {
        try {
            $order = self::findBookById($id);
            if(!$order) {
                return responseCustom(null, "Order not found", false);
            }

            OrderCars::where("orders_id", $id)->delete();
            $order->delete();

            return responseCustom(null,"Success Delete Order!", true);
        } catch (\Throwable $th) {
            return responseCustom($th->getMessage());
        }
    }

    private static function findBookById($id)
    {
        return Orders::find($id);
    }
}

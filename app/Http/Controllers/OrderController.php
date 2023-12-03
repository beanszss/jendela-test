<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Models\Orders;
use App\Repository\BookRepository;
use App\Repository\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('super-admin')->except('index');
    }

    public function index()
    {
        $orders = OrderRepository::listOreders();
        return view("admin.pages.order.index", compact("orders"));
    }

    public function create()
    {
        $cars = Cars::get();
        return view("admin.pages.order.form", compact("cars"));
    }

    public function edit(Orders $order)
    {
        $cars = Cars::get();
        return view("admin.pages.order.form", compact("order","cars"));
    }

    public function store()
    {
        $response = OrderRepository::createOrUpdateOrder();
        if(!$response["status"]) {
            alertNotify(false, $response["data"]);
            return back()->withInput();
        }

        alertNotify(true, $response["data"]);
        return redirect(url("dashboard/order"));

    }

    public function update($id)
    {
        $response = OrderRepository::createOrUpdateOrder($id);
        if(!$response["status"]) {
            alertNotify(false, $response["data"]);
            return back()->withInput();
        }

        alertNotify(true, $response["data"]);
        return redirect(url("dashboard/order"));

    }

    public function destroy($id)
    {
        $response = OrderRepository::destroyOrder($id);
        if(!$response["status"]) {
            alertNotify(false, $response["data"]);
            return back();
        }

        alertNotify(true, $response["data"]);
        return back();
    }

    public function listOrders(Request $request)
    {
        $orders = OrderRepository::listOreders();
        $superAdmin = Auth::user()->hasRole("super-admin");
        $arrayData = collect([]);

        $orders->each(function($q) use($arrayData, $superAdmin) {
            $arrayData->push([
                $q->id ?? "",
                $q->customer_name ?? "",
                $q->email,
                $q->phone,
                $q->cars()->pluck("cars_name")->implode(", "),
                $superAdmin ? '<a href="'.url('dashboard/order/' . $q['id'] . '/edit').'" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a>' .
                '<form action="'.url('dashboard/order/' . $q['id']).'" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button></button>
                </form>'
                : "-"
            ]);
        });


        return response()->json([
            "draw"              => $request->get("draw"),
            "recordsTotal"      => $orders->total(),
            "recordsFiltered"   => $orders->total(),
            "data"              => $arrayData->toArray()
        ]);

    }
}

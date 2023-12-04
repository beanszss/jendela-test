<?php

namespace App\Http\Controllers;

use App\Mail\Invoice;
use App\Models\Cars;
use App\Models\Orders;
use App\Repository\OrderRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
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
        $orders = OrderRepository::listOreders();
        return view("admin.pages.order.index", compact("orders"));
    }

    /**
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $cars = Cars::get();
        return view("admin.pages.order.form", compact("cars"));
    }

    /**
     * @param Orders $order
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function edit(Orders $order): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $cars = Cars::get();
        return view("admin.pages.order.form", compact("order","cars"));
    }

    /**
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function store(): \Illuminate\Foundation\Application|Redirector|Application|RedirectResponse
    {
        $response = OrderRepository::createOrUpdateOrder();
        $order = $response['data']['order'];
        $car = $response['data']['car'];

        if(!$response["status"]) {
            alertNotify(false, $response["message"]);
            return back()->withInput();
        }

        Mail::to($order->email)->send(new Invoice($order,$car));

        alertNotify(true, $response["message"]);
        return redirect(url("dashboard/order"));
    }

    /**
     * @param $id
     * @return \Illuminate\Foundation\Application|Redirector|Application|RedirectResponse
     */
    public function update($id): \Illuminate\Foundation\Application|Redirector|Application|RedirectResponse
    {
        $response = OrderRepository::createOrUpdateOrder($id);

        $order = $response['data']['order'];
        $car = $response['data']['car'];

        if(!$response["status"]) {
            alertNotify(false, $response["message"]);
            return back()->withInput();
        }

        Mail::to($order->email)->send(new Invoice($order,$car));

        alertNotify(true, $response["message"]);
        return redirect(url("dashboard/order"));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $response = OrderRepository::destroyOrder($id);
        if(!$response["status"]) {
            alertNotify(false, $response["data"]);
            return back();
        }

        alertNotify(true, $response["data"]);
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listOrders(Request $request): \Illuminate\Http\JsonResponse
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

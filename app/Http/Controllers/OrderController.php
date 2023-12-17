<?php

namespace App\Http\Controllers;

use App\Enums\Transition;
use App\Models\Driver;
use App\Models\DriverReview;
use App\Models\Order;
use App\Models\Customer;
use App\Services\Workflow;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;

class OrderController extends Controller
{
    public function store(Request $request): Response
    {
        // TODO: need to validate time eligibility
        $scheduled_at = ($request->meta['is_odd_even'])
            ? Date::createFromTimeString($request->scheduled_at) : null;

        $attributes = $request->merge([
            'driver_review_id' => DriverReview::createWithDriver(
                $request->meta['is_odd_even'],
                $scheduled_at
            )->id,
            'approver_id' => Customer::getApprover(auth()->user()->meta['personnel_no'])->id,
            'state_id' => app()->make(Workflow::class)->getInitialState()->value,
        ])->all();
        $order = Order::create($attributes);

        return response($order, 201);
    }

    public function driver(Order $order, Driver $driver): Response
    {
        // TODO: need to validate time eligibility
        $order->driver_review->driver()->associate($driver);

        return response($order, 201);
    }

    public function start(Request $request, Order $order): Response
    {
        $order->tracking_numbers()->create($request->all());
        $order->applyTransition(Transition::DRIVE_TO_DEST);

        return response($order, 201);
    }

    public function approve(Order $order): Response
    {
        $order->applyTransition(Transition::APPROVE);

        return response($order, 201);
    }

    public function reject(Order $order): Response
    {
        $order->applyTransition(Transition::REJECT);

        return response($order, 201);
    }

    public function costs(Request $request, Order $order): Response
    {
        $order->costs()->createMany($request->costs);

        return response($order, 201);
    }

    public function end(Request $request, Order $order): Response
    {
        $order->tracking_numbers()->create($request->all());
        $order->applyTransition(Transition::END_TRIP);

        return response($order, 201);
    }
}

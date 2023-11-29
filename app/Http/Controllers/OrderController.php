<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\DriverReview;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function store(Request $request): Response
    {
        // TODO: need to implement the driver selection
        $attributes = $request->merge([
            'driver_review_id' => DriverReview::createWithAvailable()->id,
            'approver_id' => User::getApprover(auth()->user()->meta['personnel_no'])->id,
        ])->all();

        $order = Order::create($attributes);

        return response($order, 201);
    }
}

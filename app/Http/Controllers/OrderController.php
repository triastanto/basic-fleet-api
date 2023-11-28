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
        $personnel_no = auth()->user()->meta['personnel_no'];
        $driver_review = DriverReview::create([
            'driver_id' => Driver::available()->first()?->id,
        ]);

        // TODO: need to implement initial state
        $attributes = $request->merge([
            'driver_review_id' => $driver_review->id,
            'approver_id' => User::getApprover($personnel_no)->id,
            'state_id' => 1,
        ])->all();

        $order = Order::create($attributes);

        return response($order, 201);
    }
}

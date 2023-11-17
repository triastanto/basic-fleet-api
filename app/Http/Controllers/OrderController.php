<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $attributes = $request->merge([
            'driver_id' => Driver::available()->first()->id,
            'approver_id' => User::approver()->id,
            'status' => 'waiting_approval',
        ])->all();

        $order = Order::create($attributes);

        return response($order, 201);
    }
}

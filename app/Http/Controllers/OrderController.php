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
        $personnel_no = auth()->user()->meta['personnel_no'];

        $attributes = $request->merge([
            'driver_id' => Driver::available()->first()->id,
            'approver_id' => User::getApprover($personnel_no)->id,
            'status' => 'waiting_approval',
        ])->all();

        $order = Order::create($attributes);

        return response($order, 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Response;

class ApprovalController extends Controller
{
    public function approve(Order $order): Response
    {
        $order->status = 'approved';
        $order->save();

        return response($order, 201);
    }
}

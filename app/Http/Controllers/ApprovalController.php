<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Workflow\State;
use Illuminate\Http\Response;

class ApprovalController extends Controller
{
    public function approve(Order $order): Response
    {
        $order->performTransition(State::approved());

        return response($order, 201);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Workflow\State;
use Illuminate\Http\Response;

class ApprovalController extends Controller
{
    public function approve(Order $order): Response
    {
        // TODO: need to implement state enum
        $order->performTransition(State::find(2));

        return response($order, 201);
    }
}

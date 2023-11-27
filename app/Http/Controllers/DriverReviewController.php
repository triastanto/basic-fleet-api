<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\DriverReview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DriverReviewController extends Controller
{
    public function store(Request $request, Driver $driver): Response
    {
        $review = DriverReview::create(
            $request->merge(['driver_id' => $driver->id])->all()
        );

        return response($review, 201);
    }
}

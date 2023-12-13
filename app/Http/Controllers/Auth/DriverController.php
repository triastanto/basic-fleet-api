<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DriverController extends Controller
{
    public function token(Request $request): Response
    {
        $driver = Driver::where('email', $request->email)->first();

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect'],
            ])->status(401);
        }

        return response(
            $driver->createToken($request->device_name)->plainTextToken,
            200
        );
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function token(Request $request): Response
    {
        /** @var Customer|null $customer */
        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect'],
            ])->status(Response::HTTP_UNAUTHORIZED);
        }

        return response(
            ['token' => $customer->createToken($request->device_name, ['customer'])->plainTextToken],
            200
        );
    }
}

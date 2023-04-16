<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\LoginVerificationNotification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // validate phone number
        $request->validate([
            'phone' => 'required|numeric|min:10'
        ]);

        $user = User::firstOrCreate([
            'phone' => $request->phone,
        ]);

        if (!$user) {
            return response()->json(['message' => 'Cannot process a user with that phone number'], Response::HTTP_UNAUTHORIZED);
        }

        // Send the user a OTP
        $user->notify(new LoginVerificationNotification());

        return response()->json(['message' => 'Text message notification sent.']);
    }
}

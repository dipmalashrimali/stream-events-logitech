<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class FaceBookController extends Controller
{

    /**
     * Get user login token for API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToken()
    {
        try {
            // Get authenticated user
            $user = Auth::user();

            // Generate a token for the user
            $token = $user->createToken('my-app-token')->plainTextToken;

            // Prepare response with token
            $response = [
                'token' => $token
            ];

            return response()->json($response);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Oops unexpected error, Please try again later or contact support.'], $e->status ?? 500);
        }
    }

    /**
     * Logout the user and redirect to login page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logoutUsingFacebook()
    {
        // Log out the user
        Auth::logout();

        // Redirect to the login page
        return redirect('/login');
    }

    /**
     * Redirect the user to the Facebook authentication page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginUsingFacebook()
    {
        // Redirect the user to Facebook authentication
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle the callback from Facebook authentication
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callbackFromFacebook()
    {
        try {
            // Get user details from Facebook
            $user = Socialite::driver('facebook')->user();

            // Create or update user record
            $saveUser = User::updateOrCreate([
                'facebook_id' => $user->getId(),
            ], [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => Hash::make($user->getName() . '@' . $user->getId()),
            ]);

            // Log in the user and redirect to events page
            Auth::loginUsingId($saveUser->id);
            return redirect('/events');

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Oops unexpected error, Please try again later or contact support.'], $e->status ?? 500);
        }
    }
}

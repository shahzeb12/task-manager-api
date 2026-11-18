<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerate();

        return response()->noContent();
    }
}

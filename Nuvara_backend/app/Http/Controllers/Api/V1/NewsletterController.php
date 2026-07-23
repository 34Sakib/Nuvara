<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to the newsletter!'
        ]);
    }
}

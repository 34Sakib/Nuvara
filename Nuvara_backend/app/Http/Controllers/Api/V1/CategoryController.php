<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')->orderBy('sort_order')->get();
        return response()->json($categories);
    }
}

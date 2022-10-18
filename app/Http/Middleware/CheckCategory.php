<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\SubCategory;
use Closure;
use Illuminate\Http\Request;

class CheckCategory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $slug = Category::where('slug', $request->slug)->first();
        $sub_slug = SubCategory::where('slug', $request->sub_slug)->first();
        if (!$slug) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }
        if ($request->sub_slug && !$sub_slug) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sub Category not found'
            ], 404);
        }


        return $next($request);
    }
}

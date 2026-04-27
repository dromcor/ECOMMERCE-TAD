<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate(['producto_id' => 'required|integer|exists:products,id']);

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $productId = $request->input('producto_id');
        $attached = $user->favorites()->toggle($productId);

        return back();
    }
}

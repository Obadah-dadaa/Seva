<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Store a newly created color, used for the inline "add color" field
     * on the product form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:colors,name'],
        ]);

        $color = Color::create($data);

        return response()->json(['id' => $color->id, 'name' => $color->name]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScreenController extends Controller
{
    public function screen1()
    {
        return response()->json([
            'message' => 'This is Screen 1'
        ]);
    }

    public function screen2()
    {
        return response()->json([
            'message' => 'This is Screen 2'
        ]);
    }

    public function screen3()
    {
        return response()->json([
            'message' => 'This is Screen 3'
        ]);
    }
} 
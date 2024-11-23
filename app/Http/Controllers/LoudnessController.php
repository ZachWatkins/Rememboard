<?php

namespace App\Http\Controllers;

use App\Loudness;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoudnessController extends Controller
{
    public function show(Request $request, Loudness $loudness): Response
    {
        return view('loudness.show');
    }
}

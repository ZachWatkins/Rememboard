<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LoudnessController extends Controller
{
    public function show(Request $request): View
    {
        return view('loudness.show');
    }
}

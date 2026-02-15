<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function index()
    {
        return view('plans.index');
    }

    public function show($plan)
    {
        // Validate plan exists
        if (!in_array($plan, ['starter', 'professional', 'enterprise'])) {
            return redirect()->route('plans.index');
        }

        return view('plans.show', compact('plan'));
    }
}
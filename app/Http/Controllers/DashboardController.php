<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // return with id user 
        return Inertia::render('Dashboard/Index', [
            'id' => auth()->user()->id
        ]);
    }
}

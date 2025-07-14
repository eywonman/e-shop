<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuitarController extends Controller
{
    public function index()
    {
        // This view just loads the page — Livewire handles the data
        return view('guitars.index');
    }
}

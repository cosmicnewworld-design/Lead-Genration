<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class SegmentController extends Controller
{
    public function show(string $segment)
    {
        $leads = auth()->user()->leads()->where('segment', $segment)->get();

        return view('leads.index', compact('leads'));
    }
}

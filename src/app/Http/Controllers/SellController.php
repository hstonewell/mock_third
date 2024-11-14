<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Item;

class SellController extends Controller
{
    public function show()
    {
        return view('sell');
    }
}

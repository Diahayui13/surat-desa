<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function adminDashboard(){
        return view('admin.dashboard');
    }
    public function wargaDashboard(){
        return view('warga.dashboard');
    }
    public function ttd(){
        return view('admin.ttd');
    }
}

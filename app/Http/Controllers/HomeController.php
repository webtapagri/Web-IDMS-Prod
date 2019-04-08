<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(empty(Session::get('authenticated')))
            return redirect('/login');

        if(Session::get('role_id')) {
            return view('dashboard');
        } else {
            return view('home');
        }    
    }
}
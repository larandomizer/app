<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.home');
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {  
       
        return view('home');
    }

    public function getUser()
    {
        return User::orderBy('created_at', 'asc')->paginate(10);
    }

    public function delUser()
    {
        $user = User::find('163');
        $user->delete();
        
        return User::orderBy('created_at', 'desc')->paginate(3);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Test;
use App\Models\Game_User;
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
    public function profile(){
        $user_id = Auth::user()->id;
        $rooms = Room::where('room_owner', $user_id)
                      ->where("status_game", 3)
                      ->get();
        $tests = Test::where('uid', $user_id)
                      ->get();
        $testov_proideno = count($rooms);
        $testov_sozdano = count($tests); 
        return view("profile", compact('testov_proideno', 'testov_sozdano', 'rooms'));
    }
    public function roomhistory(String $room_pin){
        $users = Game_User::where("room_pin", $room_pin)->orderByDesc('score')->get();
        return view('roomhistory', compact('users'));
    }
}

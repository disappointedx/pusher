<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game_User;
use Illuminate\Support\Facades\Session;
use App\Events\OpenUserEvent;
class SessionController extends Controller
{
    public function openSessionForGuest(Request $request){
        Session::start();
        $session = Session::getId();
        Session::put('room_pin', $request->get('room_pin'));
        Session::put('name', $request->get('name'));
        Session::put('score', $request->get('score'));
        $if = Game_User::where('session_id', $session)->first();
        $game_user = Game_User::create([
                'name' => $request->get('name'),
                'room_pin' => $request->get('room_pin'),
                'score' => $request->get('score'),
                'session_id' => $session
        ]);
        broadcast(new OpenUserEvent($game_user))->toOthers();
        return response()->json(['message' => 'Успешно'], 200);
    }
}

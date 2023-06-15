<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game_User;
use Illuminate\Support\Facades\Event;
use App\Events\TimerUpdated;
use App\Events\UpdateScoreEvent;
class GameController extends Controller
{
    public function updatescore(String $session_id, Request $request)
    {

        $game_user = Game_User::where('session_id', $session_id)->first();
            if ($game_user) {
                $game_user->score+=1;
                $game_user->save();
            }
         broadcast(new UpdateScoreEvent($game_user))->toOthers();
    
    }

}

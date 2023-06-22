<?php

namespace App\Http\Controllers;

use App\Events\StoreRoomEvent;
use App\Events\UpdateRoomEvent;
use App\Http\Resources\RoomResource;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use Pusher\Pusher;
use App\Models\Game_User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->middleware('auth'); 
        $user_id = Auth::id();
        $rooms = Room::all();
        $rooms = RoomResource::collection($rooms)->resolve();
        $tests = Test::where('uid', $user_id)->get();
        return view('room', compact('rooms', 'tests'));
    }

    // public function all()
    // {
    //     $rooms = Room::all();
    //     return response()->json($rooms);
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->middleware('auth'); 
        $room = Room::create($request->all());
        broadcast(new StoreRoomEvent($room))->toOthers();
        return RoomResource::make($room)->resolve();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) 
    {
        $room = Room::where('pin', $id)->get();
        $rooms = Room::where('pin', $id)->first();
        $game_user = Game_User::where('room_pin', $id)->get();
        $session = Session::getId();
        $test = Test::where('id', $rooms->test_id)->first();
        $questions = Question::where('test_id', $test->id)->get();
        $answers = Answer::where('test_id', $test->id)->get();
        return view('game', compact('room', 'game_user', 'session', 'questions', 'answers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $room->update($request->all());
        broadcast(new UpdateRoomEvent($room))->toOthers();
        return response()->json($room, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

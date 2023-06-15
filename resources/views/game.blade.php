@extends('layouts.app')
@section('content')
<game-component :room='@json($room)' :game_user='@json($game_user)' :user_id = '{{ Auth::id() }}' :session = '@json($session)' :questions = '@json($questions)' :answers = '@json($answers)'/>
@endsection

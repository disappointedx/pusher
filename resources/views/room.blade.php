@extends('layouts.app')

@section('content')
<room-component :user_id="{{ Auth::id() }}" :rooms='@json($rooms)' :tests = '@json($tests)' />
@endsection

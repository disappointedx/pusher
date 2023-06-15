@extends('layouts.app')

@section('content')
<test-component :user_id="{{ Auth::id() }}" :tests='@json($tests)'>
@endsection

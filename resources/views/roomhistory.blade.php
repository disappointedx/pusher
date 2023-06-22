@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center fs-2">Итоговые результаты</div>
    @foreach($users as $user)
    <div class="row my-1 mx-2 p-2">
        <div class="col text-center green fs-3">{{$user->name}}</div>
        <div class="col text-center green fs-3">{{$user->score}}</div>    
    </div>
        
    
    @endforeach
</div>
@endsection
@section('style')
<style>
    .green {
        background-color: #9afbac;
    }
</style>
@endsection
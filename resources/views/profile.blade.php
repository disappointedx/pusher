@extends('layouts.app')

@section('content')
{{ $testov_proideno }}
<div class="container">
    <div class="content">
        <div class="profile my-2" style = "background-color: white;">
            <div class="row mx-4 my-3">
                <div class="col-4">
                    <div class="avatar">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQlvapOOKTYbJNRayZFsm-7FFJG5_MOVnrfWQ&usqp=CAU" alt="">
                    </div>
                    <div class="name">
                        <label>{{ Auth::user()->name }}</label>
                    </div>
                </div>
                <div class="col my-4">
                   <div class="test_completed my">
                        <label>Тестов пройдено</label>
                   </div> 
                   <div class="count_completed">
                        <label>{{ $testov_proideno }}</label>
                    </div>
                </div>
                <div class="col my-4">
                    <div class="test_created">
                        <label>Тестов создано</label>
                    </div>
                    <div class="count_created">
                        <label>{{ $testov_sozdano }}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="button my-5">
            <a href = "/test" class = "btn btn-primary button">Создать тест</a>
        </div>
        <div class="results my-5">
            <div class="row">
                <div class="col">
                    <label>
                        Результаты прошлых тестов
                    </label>
                    @foreach($rooms as $room)
                    <div class="row m-2 p-2 orange">
                        <div class="col fs-3">
                            Ваша комната
                        </div>
                        <div class="col fs-3">
                            {{ $room->pin }}
                        </div>
                        <div class="col">
                            <a href = "history/{{ $room->pin }}" class = "btn btn-primary">Перейти</a>
                        </div>
                    </div>
                    @endforeach
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('style')
<style>
    img {
        width: 75px;
    }
    .name {
        width: 75px;
        text-align: center;
    }
    label {
        font-size: 20px;
    }
    .col{
        text-align: center;
    }
    .button{
        text-align: center;
    }
    .orange {
        background-color: #f1d9a1;
        border: 1px solid black;
    }
</style>
@endsection
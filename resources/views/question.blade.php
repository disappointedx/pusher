@extends('layouts.app')

@section('content')
<question-component :test='@json($test)' :questions='@json($questions)' :answers = '@json($answers)'>
@endsection

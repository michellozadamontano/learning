@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Administrar cursos', 'icon' => 'unlock-alt'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
    <p>{{$pepe}}</p>
    </div>
@endsection
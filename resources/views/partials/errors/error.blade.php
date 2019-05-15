@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Error en la Suscriccion', 'icon' => 'bug'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-around">
            <div class="alert alert-danger" role="alert">
                <strong>{{$title}}</strong>
            </div>
        </div>
    </div>
@endsection
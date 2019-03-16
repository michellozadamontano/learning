@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Administrar codigos subcripciones paypal', 'icon' => 'unlock-alt'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <paypal></paypal>
    </div>
@endsection
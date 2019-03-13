@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Administrar Maestros', 'icon' => 'unlock-alt'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <teacher-list
            :labels="{{ json_encode([
                'name' => __("Nombre"),
                'email' => __("Email"),
               
                
            ]) }}"
            route="{{ route('admin.teachers_json') }}"
        >
        </teacher-list>
    </div>
@endsection
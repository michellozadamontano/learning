@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Agregar Contenido al curso', 'icon' => 'edit'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <form
            method="POST"
            action="{{ route('course.add_course_class')}}"
            novalidate
           
        >    

            @csrf

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            {{ __("Información del curso") }}
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">
                                    {{ __("Nombre nombre Clase") }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        name="titulo"
                                        id="titulo"
                                        class="form-control{{ $errors->has('titulo') ? ' is-invalid' : '' }}"
                                        value=""
                                        required
                                        autofocus
                                    />

                                    @if ($errors->has('titulo'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('titulo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-danger">
                                    {{ __($btnText) }}
                                </button>
                            </div>                                                  
                        </div>    
                    </div>
                </div>  
                
            </div>
        <input type="hidden" name="course_id" value="{{$course->id}}">
        </form>
        
        <form action="{{route('courses.addFile')}}"
            method="POST"
            enctype="multipart/form-data"
            >   
            @csrf
            <div class="row justify-content-center">
                <div class="col-md-8">                   
                        <div class="card">
                            <div class="card-header">
                                    {{ __("Contenido para cada clase") }}
                            </div>
                            <div class="card-body">                                
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">
                                            {{ __("Seleccione Clase") }}
                                    </label>
                                    <div class="col-md-6">
                                        <select name="titulo_id" id="titulo_id" class="form-control">
                                            @foreach($clases as $title)
                                                <option value="{{ $title->id }}">
                                                    {{ $title->titulo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="form-group row"> 
                                        <label for="url_video" class="col-md-4 col-form-label text-md-right">
                                            {{ __("Titulo Video") }}
                                        </label>
                                        <div class="col-md-6">                                            
                                            <input
                                                name="titulo_video"
                                                id="titulo_video"
                                                class="form-control{{ $errors->has('titulo_video') ? ' is-invalid' : '' }}"
                                                value=""
                                                required
                                                autofocus
                                            />
        
                                            @if ($errors->has('titulo_video'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('titulo_video') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                </div>
                                <div class="form-group row"> 
                                        <label for="url_video" class="col-md-4 col-form-label text-md-right">
                                            {{ __("Url video youtube") }}
                                        </label>
                                        <div class="col-md-6">                                            
                                            <input
                                                name="url_video"
                                                id="url_video"
                                                class="form-control{{ $errors->has('url_video') ? ' is-invalid' : '' }}"
                                                value=""
                                                required
                                                autofocus
                                            />
        
                                            @if ($errors->has('url_video'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('url_video') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                </div>                    
                                <button type="submit" class="btn btn-success offset-4">
                                    Aceptar
                                </button>                                                  
                            </div>
                           
                        </div>                                     
                </div>  
            </div>              
        </form>
        
        
    </div>
@endsection
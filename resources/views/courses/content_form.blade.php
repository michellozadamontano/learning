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
            enctype="multipart/form-data"           
        >    

            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row justify-content-center">                    
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            {{ __("Información del curso") }}
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">
                                    {{ __("Nombre Clase") }}
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
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right" for="archivo">Imagen (Opcional,jpg,png,jpeg)</label>
                                <div class="col-md-6">
                                    <input type="file" class="form-control-file" id="picture" name="picture">
                                </div>
                                @if ($errors->has('picture'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('picture') }}</strong>
                                        </span>
                                @endif
                                    
                            </div> 
                            <button type="submit" class="btn btn-danger offset-4">
                                {{ __($btnText) }}
                            </button>                                                  
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
                                        <div class="form-check offset-4">
                                            <input class="form-check-input" type="radio" name="video_radio" id="youtube_radio" value="youtube" checked>
                                            <label class="form-check-label" for="youtube">
                                                Videos de Youtube
                                            </label>
                                            </div>
                                            <div class="form-check">
                                            <input class="form-check-input" type="radio" name="video_radio" id="vimeo_radio" value="vimeo">
                                            <label class="form-check-label" for="vimeo">
                                                Videos de Vimeo
                                            </label>
                                        </div>
                                </div> 
                                <div class="form-group row"> 
                                        <label for="titulo_video" class="col-md-4 col-form-label text-md-right">
                                            {{ __("Titulo Contenido") }}
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
                                        <label for="url_youtube" class="col-md-4 col-form-label text-md-right">
                                            {{ __("Url video youtube") }}
                                        </label>
                                        <div class="col-md-6">                                            
                                            <input
                                                name="url_youtube"
                                                id="url_youtube"
                                                class="form-control{{ $errors->has('url_youtube') ? ' is-invalid' : '' }}"
                                                value=""                                                
                                                autofocus
                                            />
        
                                            @if ($errors->has('url_youtube'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('url_youtube') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                </div>  
                                <div class="form-group row"> 
                                        <label for="url_vimeo" class="col-md-4 col-form-label text-md-right">
                                            {{ __("Url video Vimeo") }}
                                        </label>
                                        <div class="col-md-6">                                            
                                            <input
                                                name="url_vimeo"
                                                id="url_vimeo"
                                                class="form-control{{ $errors->has('url_vimeo') ? ' is-invalid' : '' }}"
                                                value=""                                                
                                                autofocus
                                            />
        
                                            @if ($errors->has('url_vimeo'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('url_vimeo') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                </div> 
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right" for="archivo">Archivo (Opcional,pdf,txt,docx,xlsx,mp4s,m4a,mp4a)</label>
                                    <div class="col-md-6">
                                        <input type="file" class="form-control-file" id="archivo" name="archivo">
                                    </div>
                                    @if ($errors->has('archivo'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('archivo') }}</strong>
                                            </span>
                                    @endif
                                    
                                </div> 
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right" for="archivo">Breve Descripción</label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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

@push('scripts')    
    <script>
       
        jQuery(document).ready(function() {
            $( "#url_vimeo" ).hide();
            $( "#youtube_radio" ).on( "click", function() {
                $( "#url_youtube" ).show();
                $( "#url_vimeo" ).hide();
            });
            $( "#vimeo_radio" ).on( "click", function() {
                $( "#url_youtube" ).hide();
                $( "#url_vimeo" ).show();
            });
        })
    </script>
@endpush
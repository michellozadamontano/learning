@extends('layouts.app')

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
            <div class="col-sm">  
                @forelse($course->courseContent as $content)
                <div class="card">                        
                    <div class="card-body">
                    <h4 class="card-title">{{$content->titulo}}</h4> 
                    <hr>
                    
                    @foreach ($content->files as $file)                        
                    <a href="{{route('courses.show_video', ['id' => $file->id])}}">
                        <i class="fa fa-youtube-play" aria-hidden="true"></i>
                        <span>{{$file->file}}</span>
                    </a>
                        
                    @endforeach 
                                       
                    </div>
                </div>      
                @empty
                    <div class="alert alert-dark">
                        {{ __("No hay ningún dato disponible") }}
                    </div>
                @endforelse              
                                  
                
            </div>
        </div>        
    </div>
@endsection
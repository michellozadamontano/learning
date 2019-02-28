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
                        <p>{{$file->file}}</p>
                        @if($file->path != "")
                        
                            <video width="400" controls>
                                <source src="/images/courses/{{$file->path}}" type="video/mp4">
                                    Your browser does not support the video tag.
                            </video>
                        @endif
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
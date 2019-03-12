@extends('layouts.app')
@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
            <div class="row">
                <div class="col-md-3">
                        <a href="{{route('courses.content',['slug'=>$course->slug])}}" class="btn btn-success">
                            <i class="fa fa-backward" aria-hidden="true"></i>
                            Regresar
                        </a>
                </div>
            </div>
            
            <br>
            <div class="row">
                <div class="col-md-12">                   
                        @if($file->path != "")                        
                            @if(strlen($file->path) > 9)
                                <iframe width="620" height="415" frameborder="0"
                                    src="https://www.youtube.com/embed/{{$file->path}}">
                                </iframe>
                                @else
                                <iframe width="620" height="415" frameborder="0"
                                    src="https://player.vimeo.com/video/{{$file->path}}">
                                </iframe> 
                            @endif         
                                            
                        @endif
                </div>
            </div>
           
        </div>
    </div>
@endsection    
@extends('layouts.app')

@section('content')
    <div class="pl-5 pr-5">
            <a class="btn btn-course" href="{{ route('courses.detail', ["slug" => $course->slug]) }}">
                    <i class="fa fa-eye"></i> {{ __("Detalle") }}
            </a>
        <div class="row justify-content-center">       
            @forelse ($course->courseContent as $content)
            <div class="col-md-10  listing-block">
                <div class="media" style="height: 250px;" >
                    <img
                        style="height: 200px; width: 300px;"
                        class="img-rounded"
                        src="{{ $course->pathAttachment() }}"
                        alt="{{ $course->name }}"
                    />

                    <div class="media-body pl-3" style="height: 250px;overflow: scroll">
                        <div class="price">
                            <small class="badge-danger text-white text-center">
                                {{ $content->titulo }}
                            </small>
                            @foreach ($content->files as $file)
                            <div class="alert alert-primary" role="alert">
                                @if($file->arhivo != "")
                                    <small>
                                            <a href="{{route('courses.download', ['file' => $file->arhivo])}}">
                                                <i class="fa fa-file" aria-hidden="true"></i>
                                                <span>{{$file->file}}</span><br>
                                            </a> 
                                    </small>                      
                                @endif 
                                @if($file->path != "")
                                    <small data-toggle="tooltip" data-placement="top" title="{{$file->description}}">
                                        <a href="{{route('courses.show_video', ['id' => $file->id])}}">
                                            <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                            <span>{{$file->file}}</span><br>
                                        </a>
                                    </small>                               
                                    
                                @endif  
                                
                                
                                   <small>{{__('Descripción')}}: {{$file->description}}</small>
                                </div>
                                
                            @endforeach                             
                        </div>

                        
                    </div>
                </div>
            </div>
            {{-- $course->courseContent->links() --}}
            @empty
            <div class="alert alert-dark">
                {{ __("No hay ningún dato disponible") }}
            </div>
            @endforelse
            
        </div>        
    </div>
@endsection
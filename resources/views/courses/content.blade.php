@extends('layouts.app')

@section('content')
    <div class="pl-5 pr-5">
        <div class="row mb-2">
            <div class="col-sm-3">
                <a class="btn btn-course" href="{{ route('courses.detail', ["slug" => $course->slug]) }}">
                        <i class="fa fa-eye"></i> {{ __("Detalle") }}
                </a>
            </div>
            @if ($course->free == 1)
            <div class="col-sm-3">
                <form action="{{ route('courses.free',["slug" => $course->slug])}}" method="GET" id="form_paginate">
                    <select name="paginate" id="paginate" class="form-control" onchange="this.form.submit()">
                        <option value="">[Seleccionar]</option>          
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>      
                        <option value="20">20</option>                  
                    </select>
                </form>
            </div>
            @else 
            <div class="col-sm-3">
                <form action="{{ route('courses.content',["slug" => $course->slug])}}" method="GET" id="form_paginate">
                    <select name="paginate" id="paginate" class="form-control" onchange="this.form.submit()">
                        <option value="">[Seleccionar]</option>          
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>      
                        <option value="20">20</option>                  
                    </select>
                </form>
            </div>
            @endif
            
        </div>        
            
        <div class="row justify-content-center">      
                      
            @forelse ($contents as $content)
            <div class="col-md-10  listing-block">
                <div class="media" style="height: 250px;" >
                    <img
                        style="height: 200px; width: 300px;"
                        class="img-rounded"
                        src="/images/courses/{{ $content->picture }}"
                        alt="{{ $content->course->name }}"
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
                                            <a href="{{route('courses.download', ['file' => $file->arhivo])}}" style="color:blue">
                                                <i class="fa fa-file yellow" aria-hidden="true"></i>
                                                <span>{{$file->file}}</span><br>
                                            </a> 
                                    </small>                      
                                @endif 
                                @if($file->path != "")
                                    <small data-toggle="tooltip" data-placement="top" title="{{$file->description}}">
                                        <a href="{{route('courses.show_video', ['id' => $file->id])}}" style="color:blue">
                                            <i class="fa fa-youtube-play red" aria-hidden="true"></i>
                                            <span>{{$file->file}}</span><br>
                                        </a>
                                    </small>                               
                                    
                                @endif      
                                
                                   <small style="background: cadetblue">{{__('Descripción')}}: {!! eval('?>'.Blade::compileString($file->description)) !!} </small>                                  
                                </div>
                                
                            @endforeach                             
                        </div>

                        
                    </div>
                </div>
            </div>   
            
            @empty
            <div class="alert alert-dark">
                {{ __("No hay ningún dato disponible") }}
            </div>
            @endforelse    
        </div> 
        {{ $contents->links()}}       
    </div>
@endsection
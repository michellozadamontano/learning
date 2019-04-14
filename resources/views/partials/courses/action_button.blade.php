<div class="col-2">
    @auth
        @can('opt_for_course', $course)
             @can('subscribe', \App\Course::class)
                 @if($course->free == 1)
                    <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.free', ['slug' => $course->slug])}}">
                        <i class="fa fa-bolt"></i> {{ __("Gratis") }}
                    </a>
                @else
                    <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('subscriptions.plans') }}">
                        <i class="fa fa-bolt"></i> {{ __("Subscribirme") }}
                    </a>    
                @endif            
             @else
                
                @can('inscribe', $course)
                    @if($course->free == 1)
                        <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.free', ['slug' => $course->slug])}}">
                            <i class="fa fa-bolt"></i> {{ __("Gratis") }}
                        </a>
                    @else
                        <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('courses.inscribe', ['slug' => $course->slug]) }}">
                            <i class="fa fa-bolt"></i> {{ __("Inscribirme") }}
                        </a>
                    @endif
                @else
                    @if($course->free == 1)
                        <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.free', ['slug' => $course->slug])}}">
                            <i class="fa fa-bolt"></i> {{ __("Gratis") }}
                        </a>
                    @else
                        <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.content', ['slug' => $course->slug])}}">
                            <i class="fa fa-bolt"></i> {{ __("Inscrito") }}
                        </a>
                    @endif
                @endcan
            @endcan
             
        @else
            <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.content', ['slug' => $course->slug])}}">
                <i class="fa fa-user"></i> {{ __("Soy autor") }}
            </a>
        @endcan
    @else
        @if($course->free == 1)
            <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.free', ['slug' => $course->slug])}}">
                <i class="fa fa-bolt"></i> {{ __("Gratis") }}
            </a>
        @else
            <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('login') }}">
                <i class="fa fa-user"></i> {{ __("Acceder") }}
            </a>
        @endif
    @endauth
</div>
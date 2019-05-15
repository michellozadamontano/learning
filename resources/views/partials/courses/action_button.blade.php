<div class="col-2">       
    @auth
        @can('opt_for_course', $course)
             @can('subscribe', \App\Course::class)
                 @if($course->free == 1)
                    <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.free', ['slug' => $course->slug])}}">
                        <i class="fa fa-bolt"></i> {{ __("Gratis") }}
                    </a>
                @else
                    @if($course->pay == 1 && $course->userPayment->contains('user_id',auth()->user()->id))
                        <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.content', ['slug' => $course->slug])}}">
                            <i class="fa fa-user"></i> {{ __("Ir al curso") }}
                        </a>
                    @else
                        @if($course->pay == 1 && !$course->userPayment->contains('user_id',auth()->user()->id))                        
                            <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.payment_details', ['id' => $course->id])}}">
                                <i class="fa fa-paypal"></i> {{ __("Pagar") }}
                            </a>   
                                                   
                        @else
                            <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('subscriptions.plan_epay') }}">
                                <i class="fa fa-bolt"></i> {{ __("Subscribirme") }}
                            </a> 
                        @endif   
                    @endif
                      
                @endif            
             @else
                
                @can('inscribe', $course)
                    @if($course->free == 1)
                        <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.free', ['slug' => $course->slug])}}">
                            <i class="fa fa-bolt"></i> {{ __("Gratis") }}
                        </a>
                    @else
                        @if($course->pay == 1 && $course->userPayment->contains('user_id',auth()->user()->id))
                            <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.content', ['slug' => $course->slug])}}">
                                <i class="fa fa-user"></i> {{ __("Ir al curso") }}
                            </a>
                        @else
                            @if($course->pay == 1 && !$course->userPayment->contains('user_id',auth()->user()->id))                        
                                <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.payment_details', ['id' => $course->id])}}">
                                    <i class="fa fa-paypal"></i> {{ __("Pagar") }}
                                </a>                            
                                                
                            @else
                                <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('courses.inscribe', ['slug' => $course->slug]) }}">
                                    <i class="fa fa-bolt"></i> {{ __("Inscribirme") }}
                                </a> 
                            @endif 
                        @endif  
                    @endif
                        
                    
                @else
                    @if($course->free == 1)
                        <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.free', ['slug' => $course->slug])}}">
                            <i class="fa fa-bolt"></i> {{ __("Gratis") }}
                        </a>
                        
                    @else
                        @if($course->pay == 1 && $course->userPayment->contains('user_id',auth()->user()->id))
                            <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.content', ['slug' => $course->slug])}}">
                                <i class="fa fa-user"></i> {{ __("Ir al curso") }}
                            </a>
                        @else
                            @if($course->pay == 1 && !$course->userPayment->contains('user_id',auth()->user()->id))                        
                                <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.payment_details', ['id' => $course->id])}}">
                                    <i class="fa fa-paypal"></i> {{ __("Pagar") }}
                                </a>                            
                                                
                            @else
                                <a class="btn btn-subscribe btn-bottom btn-block" href="{{route('courses.content', ['slug' => $course->slug])}}">
                                    <i class="fa fa-bolt"></i> {{ __("Inscrito") }}
                                </a>
                            @endif 
                        @endif  
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

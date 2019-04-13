<div class="card card-01">
    <img
        class="card-img-top"
        src="{{$course->pathAttachment()}}"
        alt="{{ $course->name }}"
        style="height: 200px; width: 300px;"
    />
    <div class="card-body">
        <span class="badge-box"><i class="fa fa-check"></i></span>
        <h4 class="card-title">{{ $course->name }}</h4>
        <hr />
        <div class="row justify-content-center">
            @include('partials.courses.rating', ['rating' => $course->custom_rating])
        </div>
        <hr />
        <span class="badge badge-danger badge-cat">{{ $course->category->name }}</span>
        <p class="card-text">
            {{ str_limit($course->description, 100) }}
        </p>
        @guest
        @if($course->free == 1)
            <a
                href="{{route('courses.free', ['slug' => $course->slug])}}"
                class="btn btn-course btn-block"
            >
                    {{ __("Ir Al curso") }}
            </a>
        @else 
            <a
                href="{{ route('courses.detail', $course->slug) }}"
                class="btn btn-course btn-block"
            >
                {{ __("Más información") }}
            </a>
        @endif
        @endguest
        @auth
        @if(($course->students->contains(auth()->user()->student->id) && auth()->user()->paypal)||$course->free == 1)
            <a
                href="{{route('courses.content', ['slug' => $course->slug])}}"
                class="btn btn-course btn-block"
            >
                    {{ __("Ir Al curso") }}
            </a>        
        @else
            <a
                href="{{ route('courses.detail', $course->slug) }}"
                class="btn btn-course btn-block"
            >
                {{ __("Más información") }}
            </a>
        @endif
       
        @endauth
    </div>
</div>
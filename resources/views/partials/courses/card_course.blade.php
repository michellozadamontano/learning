@push('styles')
<style>
    .oversize {
        width: 100%;
        height: 50px;
    }
    .card_image {
        width: 100%;
        height: 200px;
    }
</style>
@endpush
<div class="card card-01">
    <img
        class="card_image card-img-top"
        src="{{$course->pathAttachment()}}"
        alt="{{ $course->name }}"
       
    />
    <div class="card-body">
            @if($course->free == 1)
                <img src="/images/free.jpg" class="oversize img-rounded" alt="free" >
            @endif
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
                    href="{{route('courses.detail', ['slug' => $course->slug])}}"
                    class="btn btn-course btn-block"
                >
                        {{ __("Ver detalle") }}
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
        @if($course->free == 1)
            <a
                href="{{route('courses.detail', ['slug' => $course->slug])}}"
                class="btn btn-course btn-block"
            >
                    {{ __("Ver detalle") }}
            </a>
        @else 
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
        @endif
       
        @endauth
    </div>
</div>
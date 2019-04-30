@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Detalles del curso', 'icon' => 'table'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
                <div class="col-6">
                    <div class="card text-white bg-success">
                      <img class="card-img-top" src="holder.js/100px180/" alt="">
                      <div class="card-body">
                            <h3>{{ __("Curso") }}: {{ $course->name }}</h3>
                            <h4>{{ __("Profesor") }}: {{ $course->teacher->user->name }}</h4>
                            <h5>{{ __("Categoría") }}: {{ $course->category->name }}</h5>
                            <h5>{{ __("Valor en USD") }}: {{ $course->value }}</h5>
                            
                            <form  action="{{route('courses.paypal')}}" method="POST">
                                    @csrf
                                <div class="form-group">
                                    <label for="">CUPON</label>
                                    <input type="text" name="coupon" id="" class="form-control" placeholder="" aria-describedby="helpId">
                                    <small id="helpId" class="text-muted">Si tienes un cupon por favor intoducelo aquí</small>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                </div>
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </form>
                            
                      </div>
                    </div>        
                </div>
                
                
        </div>
    </div>
@endsection
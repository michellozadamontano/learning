@extends('layouts.app')
@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
                <div class="col-6">
                    <div class="card text-white bg-primary">
                      <img class="card-img-top" src="holder.js/100px180/" alt="">
                      <div class="card-body">
                            <h3>{{ __("Curso") }}: {{ $course->name }}</h3>
                            <h4>{{ __("Profesor") }}: {{ $course->teacher->user->name }}</h4>
                            <h5>{{ __("Categoría") }}: {{ $course->category->name }}</h5>
                            <h5>{{ __("Valor en USD") }}: {{ $course->value }}</h5>
                            <br>
                            <div class="alert alert-success" role="alert">
                              <h4 class="alert-heading">Datos del pago</h4>
                              <h5>{{ __("Valor") }}: {{ $course->value }}</h5>
                              <h5>{{ __("Total a Pagar") }}: {{ $amount }}</h5>
                              <h5>{{ __("Descuento") }}: {{ $descuento }}</h5>
                              <p class="mb-0"></p>
                              <paypal-payment
                                    amount= '{{$amount}}'
                                    sandbox = '{{$paypal_id}}'
                                    production = '{{$paypal_id}}'
                                    course_id ='{{$course->id}}'
                                ></paypal-payment>
                            </div>
                      </div>
                    </div>        
                </div>
                
                
        </div>
    </div>
@endsection
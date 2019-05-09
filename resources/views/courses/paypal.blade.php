
@extends('layouts.app')
@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
          @if ($type == "paypal")
            <div class="col-6">
                <div class="card text-white bg-primary">
                  <img class="card-img-top" src="holder.js/100px180/" alt="">
                  <div class="card-body">
                        <h3>Pago en dolares</h3>                            
                        <br>
                        <div class="alert alert-success" role="alert">
                          <h4 class="alert-heading">Datos del pago</h4>
                          <h5>{{ __("Valor") }}: {{ $course->value }}</h5>
                          <h5>{{ __("Descuento") }}: {{ $descuento }}</h5>
                          <h5>{{ __("Total a Pagar") }}: {{ $amount }}</h5>                              
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
          @else
            <div class="col-6">
                <div class="card text-white bg-primary">
                    <img class="card-img-top" src="holder.js/100px180/" alt="">
                    <div class="card-body">
                            <h3>Pago en pesos colombianos</h3> 
                          <br>
                          <div class="alert alert-primary" role="alert">
                            <h4 class="alert-heading">Datos del pago</h4>
                            <h5>{{ __("Valor") }}: {{ $course->cop }}</h5>
                            <h5>{{ __("Descuento") }}: {{ $desc_cop }}</h5>
                            <h5>{{ __("Total a Pagar") }}: {{ $cop }}</h5>                                
                            <p class="mb-0"></p>
                            <div>
                              <payu-check-out 
                                p_key         = "{{$epay_key}}"
                                test          = "{{$epay_test}}"
                                name          = "{{$course->name}}"
                                amount        = "{{$cop}}"
                                url_acepted   = "{{$url_acepted}}"
                                url_rejected  = "{{$url_rejected}}"
                                url_pending   = "{{$url_pending}}"
                              ></payu-check-out>
                            </div>                             
                          </div>
                    </div>
                  </div>  
            </div>    
          @endif        
        </div>
    </div>
@endsection
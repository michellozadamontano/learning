@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Detalles de pagos', 'icon' => 'table'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">
                <div class="col-6">
                    <div class="card text-white bg-success">
                      <img class="card-img-top" src="holder.js/100px180/" alt="">
                      <div class="card-body">
                            <h3>Pago en D&oacute;lares</h3> 
                            <br>
                            <div class="alert alert-primary" role="alert">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="alert-heading">Datos del pago</h4>
                                        <h5>{{ __("Valor en USD") }}: {{ $course->value }}</h5>                                                                
                                        <p class="mb-0"></p>
                                    </div>
                                    <div class="col-6">                                        
                                        <form action="{{ route('courses.paypal') }}" method="POST">
                                            @csrf
                                            <input
                                                class="form-control"
                                                name="coupon"
                                                placeholder="{{ __("¿Tienes un cupón?") }}"
                                            />
                                            <input type="hidden" name="type" value="paypal" />
                                            <input type="hidden" name="course_id" value="{{$course->id}}">
                                            <hr />
                                            <button type="submit" class="btn btn-primary">Pagar</button>
                                        </form>                                        
                                    </div>
                                </div>   
                                                        
                            </div>
                            
                      </div>
                    </div>        
                </div>
                <div class="col-6">
                    <div class="card text-white bg-primary">
                        <img class="card-img-top" src="holder.js/100px180/" alt="">
                        <div class="card-body">                            
                            <h3>Pago en pesos colombianos</h3> 
                            <br>
                            <div class="alert alert-primary" role="alert">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="alert-heading">Datos del pago</h4>
                                        <h5>{{ __("Valor en COP") }}: {{ $course->cop }}</h5>                                                                
                                        <p class="mb-0"></p>
                                    </div>
                                    <div class="col-6">                                        
                                            <form action="{{ route('courses.paypal') }}" method="POST">
                                                    @csrf
                                                    <input
                                                        class="form-control"
                                                        name="coupon"
                                                        placeholder="{{ __("¿Tienes un cupón?") }}"
                                                    />
                                                    <input type="hidden" name="type" value="epay" />
                                                    <input type="hidden" name="course_id" value="{{$course->id}}">                                                    
                                                    <hr />
                                                    <button type="submit" class="btn btn-primary">Pagar</button>
                                            </form>                                        
                                    </div>
                                </div>
                                
                                                        
                            </div>
                                
                                
                        </div>
                      </div>  
                </div>  
                
                
        </div>
    </div>
@endsection
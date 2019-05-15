
@extends('layouts.app')
@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-center">         
            <div class="col-6">
                <div class="card text-white bg-primary">
                  <img class="card-img-top" src="holder.js/100px180/" alt="">
                  <div class="card-body">
                        <h3>Pago en pesos colombianos</h3>                            
                        <br>
                        <div class="alert alert-success" role="alert">
                          <h4 class="alert-heading">Datos del pago</h4>
                          <h5>{{ __("Valor") }}: {{ $price }}</h5>
                          <h5>{{ __("Descuento") }}: {{ $descuento }}</h5>
                          <h5>{{ __("Total a Pagar") }}: {{ $amount }}</h5>                               
                          <p class="mb-0"></p>
                          <form action="{{ route('epay.epay_data') }}" method="POST">
                            @csrf
                                <input type="hidden"
                                    class="form-control"
                                    name="amount"
                                    value="{{$amount}}"
                                />
                                <input type="hidden" name="type" value="{{ $type }}" />
                                <hr />
                                <button class="btn btn-default"><i class="fas fa-money-check-alt fa-4x"></i></i></button>
                          </form>                              
                        </div>
                  </div>
                </div>        
            </div>       
        </div>
    </div>
@endsection
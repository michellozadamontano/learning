
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
                        @php
                            $pri = number_format($price,2);
                            $des = number_format($descuento,2);
                            $amo = number_format($amount,2);
                        @endphp
                        <div class="alert alert-success" role="alert">
                          <h4 class="alert-heading">Datos del pago</h4>
                          <h5>{{ __("Valor") }}: {{ $pri }}</h5>
                          <h5>{{ __("Descuento") }}: {{ $des }}</h5>
                          <h5>{{ __("Total a Pagar") }}: {{ $amo }}</h5>                               
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
                                <button class="btn btn-default"><i class="fas fa-money-check-alt fa-4x"></i></button>
                          </form>                              
                        </div>
                  </div>
                </div>        
            </div>       
        </div>
    </div>
@endsection
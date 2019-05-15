@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pricing.css') }}">
@endpush

@section('jumbotron')
    @include('partials.jumbotron', [
        'title' => __("Elige la moneda de la suscricción"),
        'icon' => 'globe'
    ])
@endsection

@section('content')
    <div class="container">
        <div class="pricing-table pricing-three-column row">            
            <div class="plan col-sm-4 col-lg-4">
                <div class="plan-name-bronze">
                    <h5>{{ __("SELECCIONA LA MONEDA") }}</h5>
                    <h5>{{ __(" USD") }}</h5>                   
                </div> 
                <br>
                <hr>              
                <form action="{{route('subscriptions.plans')}}" method="GET">
                    @csrf
                    <input type="hidden" name="money" value="usd">
                    <button type="submit" class="btn btn-primary">Aceptar</button>
                </form>
                
            </div>
            <div class="plan col-sm-4 col-lg-4">
                <div class="plan-name-silver">
                    <h5>{{ __("SELECCIONA LA MONEDA") }}</h5>
                    <h5>{{ __(" COP") }}</h5>                  
                </div>
                <br>
                <hr>                
                <form action="{{route('subscriptions.plans')}}" method="GET">
                    @csrf
                    <input type="hidden" name="money" value="cop">
                    <button type="submit" class="btn btn-primary">Aceptar</button>
                </form>
            </div>           
        </div>
    </div>
@endsection
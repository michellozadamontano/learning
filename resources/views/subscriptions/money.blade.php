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
                    <h2>{{ __("Dólar") }}</h2>                   
                </div> 
                <br>
                <hr>              
                <form action="{{route('subscriptions.plans')}}" method="post">
                    @csrf
                    <input type="hidden" name="money" value="usd">
                    <button type="submit" class="btn btn-primary">Aceptar</button>
                </form>
                
            </div>
            <div class="plan col-sm-4 col-lg-4">
                <div class="plan-name-silver">
                    <h2>{{ __("Peso colombiano") }}</h2>                   
                </div>
                <br>
                <hr>
                <h5 style="color: brown">Se está trabajando en esta area en estos momentos</h5>
                <!--<form action="{{route('subscriptions.plans')}}" method="post">
                    @csrf
                    <input type="hidden" name="money" value="cop">
                    <button type="submit" class="btn btn-primary">Aceptar</button>
                </form>-->
            </div>           
        </div>
    </div>
@endsection
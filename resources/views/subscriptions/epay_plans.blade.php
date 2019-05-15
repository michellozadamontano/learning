@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pricing.css') }}">
@endpush

@section('jumbotron')
    @include('partials.jumbotron', [
        'title' => __("Subscríbete ahora a uno de nuestros planes"),
        'icon' => 'globe'
    ])
@endsection

@section('content')
    <div class="container">
        <div class="pricing-table pricing-three-column row">
            
            <div class="plan col-sm-4 col-lg-4">
                <div class="plan-name-bronze">
                    <h2>{{ __("MENSUAL") }}</h2>
                    @php
                        $number = number_format($epay_mensual,2);
                    @endphp
                    <span>{{ __(":price / Mes", ['price' => $number]) }}</span>
                </div>
                <ul>
                    <li class="plan-feature">{{ __("Acceso a todos los cursos") }}</li>
                    <li class="plan-feature">{{ __("Acceso a todos los archivos") }}</li>
                    <li class="plan-feature">
                        @include('partials.paypal.epayform', [
                            "product" => [
                                "name" => __("Suscripción"),
                                "description" => __("Mensual"),
                                "type" => "monthly",
                                "amount" => $epay_mensual
                            ]
                        ])
                    </li>
                </ul>
            </div>

            <div class="plan col-sm-4 col-lg-4">
                <div class="plan-name-silver">
                    <h2>{{ __("Trimestral") }}</h2>
                    @php
                        $number = number_format($epay_trimestral,2);
                    @endphp
                    <span>{{ __(":price / 3 meses", ['price' => $number]) }}</span>
                </div>
                <ul>
                    <li class="plan-feature">{{ __("Acceso a todos los cursos") }}</li>
                    <li class="plan-feature">{{ __("Acceso a todos los archivos") }}</li>
                    <li class="plan-feature">
                        @include('partials.paypal.epayform',
                            ["product" => [
                                'name' => 'Suscripción',
                                'description' => 'Trimestral',
                                'type' => 'quarterly',
                                'amount' => $epay_trimestral
                            ]]
                        )
                    </li>
                </ul>
            </div>

            <div class="plan col-sm-4 col-lg-4">
                <div class="plan-name-gold">
                    <h2>{{ __("ANUAL") }}</h2>
                    @php
                        $number = number_format($epay_anual,2);
                    @endphp
                    <span>{{ __(":price / 12 meses", ['price' => $number]) }}</span>
                </div>
                <ul>
                    <li class="plan-feature">{{ __("Acceso a todos los cursos de membresia") }}</li>
                    <li class="plan-feature">{{ __("Acceso a todos los archivos de membresia") }}</li>
                    <li class="plan-feature">
                        @include('partials.paypal.epayform',
                            ["product" => [
                                'name' => 'Suscripción',
                                'description' => 'Anual',
                                'type' => 'yearly',
                                'amount' => $epay_anual
                            ]]
                        )
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
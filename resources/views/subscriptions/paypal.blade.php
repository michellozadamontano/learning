@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Manejar mis suscripciones', 'icon' => 'list-ol'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <div class="row justify-content-around">
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th scope="col">#</th>                        
                        <th scope="col">Plan</th>
                        <th scope="col">ID Suscripción</th>                        
                        <th scope="col">Alta</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Finaliza en</th>
                        <th scope="col">Cancelar / Reanudar / Suspender</th>
                    </tr>
                </thead>
                <tbody>
                    @if($subscription != null)
                        <td>{{ $subscription->id }}</td>                        
                        <td>{{ $subscription->plan }}</td>
                        <td>{{ $subscription->paypal_id }}</td>                       
                        <td>{{ $subscription->created_at->format('d/m/Y') }}</td>
                        <td>{{ $subscription->state }}</td>  
                        <td>{{ $subscription->end_date ? $subscription->end_date->format('d/m/Y') : __("Suscripción activa") }}</td>
                        <td>
                            @if($subscription->state == "Suspended")
                                <form action="{{ route('subscriptions.reactivate') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan" value="{{ $subscription->paypal_id }}" />
                                    <button class="btn btn-success">
                                        {{ __("Reanudar") }}
                                    </button>
                                </form>
                            @else
                            <table>
                                <tr>
                                    <td>
                                        <form action="{{ route('subscriptions.suspend') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="plan" value="{{ $subscription->paypal_id }}" />
                                            <button class="btn btn-warning">
                                                {{ __("Suspender") }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('subscriptions.cancelar') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="plan" value="{{ $subscription->paypal_id }}" />
                                            <button class="btn btn-danger">
                                                {{ __("Cancelar") }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </table>   
                                
                            @endif
                        </td>
                    @else
                        <tr>
                            <td colspan="8">{{ __("No hay ninguna suscripción disponible") }}</td>
                        </tr>
                    @endif
                   
                </tbody>
            </table>
        </div>
    </div>
@endsection
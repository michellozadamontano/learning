@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Datos para el pago con epayco', 'icon' => 'edit'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
            <form
            method="POST"
            action="{{  route('epay.epay_suscription') }}"                     
        >         

            @csrf
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="">Nombre y Apellidos</label>
                                <input type="text"
                                    class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Nombre y Apellidos" required
                                    value="{{ old('nombre') ?: '' }}">                            
                            </div> 
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" 
                                class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" 
                                aria-describedby="helpId" placeholder="Email" required 
                                value="{{ old('email') ?: '' }}">                            
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Telefono</label>
                                <input type="text"
                                    class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" id="phone" aria-describedby="helpId" placeholder="Telefono" required
                                    value="{{ old('phone') ?: '' }}">                            
                            </div>
                            <div class="form-group">
                                <label for="">CC</label>
                                <input type="text"
                                    class="form-control {{ $errors->has('doc_number') ? ' is-invalid' : '' }}" name="doc_number" id="doc_number" aria-describedby="helpId" placeholder="Cedula de Ciudadanía" required
                                    value="{{ old('doc_number') ?: '' }}">                            
                            </div>
                            <div class="form-group">
                            <label for="">Tarjeta de Credito</label>
                            <input type="text"
                                class="form-control {{ $errors->has('card') ? ' is-invalid' : '' }}" name="card" id="card" aria-describedby="helpId" placeholder="Tarjeta de Credito" required
                                value="{{ old('card') ?: '' }}">                            
                            </div> 
                            <div class="row">
                                <div class="col-6">
                                    @php
                                        $year = Date('Y');
                                    @endphp
                                    <div class="form-group">
                                        <label for="">Año de Expiracion</label>                           
                                        <input type="number" min="{{$year}}"
                                            class="form-control {{ $errors->has('card_exp') ? ' is-invalid' : '' }}" name="card_exp" id="card_exp" aria-describedby="helpId" placeholder="Año de Expiracion" required
                                            value="{{ old('card_exp') ?: '' }}">                                        
                                        @if ($errors->has('card_exp'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('card_exp') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>                                
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Mes de Expiracion</label>
                                        <input type="number" min="1" max="12"
                                            class="form-control {{ $errors->has('card_exp_mes') ? ' is-invalid' : '' }}" name="card_exp_mes" id="card_exp_mes" aria-describedby="helpId" placeholder="Mes de Expiracion" required
                                            value="{{ old('card_exp_mes') ?: '' }}">                                        
                                        @if ($errors->has('card_exp_mes'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('card_exp_mes') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>        
                            <div class="form-group">
                                <label for="">Codigo de Seguridad</label>
                                <input type="text"
                                    class="form-control {{ $errors->has('card_cvc') ? ' is-invalid' : '' }}" name="card_cvc" id="card_cvc" aria-describedby="helpId" placeholder="Codigo de Seguridad" required
                                    value="{{ old('card_cvc') ?: '' }}">
                                @if ($errors->has('card_cvc'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('card_cvc') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <input type="hidden" name="type" value="{{$type}}">
                            <input type="hidden" name="amount" value="{{$amount}}">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
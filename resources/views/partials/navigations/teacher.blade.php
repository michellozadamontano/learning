<li class="nav-item dropdown">
    <a id="navbarDropdown"
       class="nav-link dropdown-toggle"
       href="#" role="button"
       data-toggle="dropdown"
       aria-haspopup="true"
       aria-expanded="false"
    >
        Cursos<span class="caret"></span>
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('free') }}">{{ __("Contenido Gratuito") }}</a>
        <a class="dropdown-item" href="{{ route('pay') }}">{{ __("Contenido Pago") }}</a>
    </div>
</li>

<li><a class="nav-link" href="{{ route('profile.index') }}">{{ __("Mi perfil") }}</a></li>
<li><a class="nav-link" href="{{ route('subscriptions.paypal') }}">{{ __("Mis suscripciones") }}</a></li>
<!--<li><a class="nav-link" href="{{ route('invoices.admin') }}">{{ __("Mis facturas") }}</a></li></li>-->
<li class="nav-item dropdown">
    <a id="navbarDropdown"
       class="nav-link dropdown-toggle"
       href="#" role="button"
       data-toggle="dropdown"
       aria-haspopup="true"
       aria-expanded="false"
    >
        Mis Cursos<span class="caret"></span>
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('courses.subscribed') }}">{{ __("Inscritos") }}</a>
        <a class="dropdown-item" href="{{ route('courses.payed') }}">{{ __("Pagados") }}</a>
    </div>
</li>

<li><a class="nav-link" href="{{ route('teacher.courses') }}">{{ __("Cursos desarrollados por mi") }}</a></li>
<li><a class="nav-link" href="{{ route('courses.create') }}">{{ __("Crear curso") }}</a></li>

@include('partials.navigations.logged')
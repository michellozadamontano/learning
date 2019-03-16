<li><a class="nav-link" href="{{ route('admin.courses') }}">{{ __("Administrar cursos") }}</a></li>
<li><a class="nav-link" href="{{ route('admin.students') }}">{{ __("Administrar estudiantes") }}</a></li>
<li><a class="nav-link" href="{{ route('admin.teachers') }}">{{ __("Administrar profesores") }}</a></li>
<li><a class="nav-link" href="{{ route('admin.paypal') }}">{{ __("Paypal Code") }}</a></li>
@include('partials.navigations.logged')
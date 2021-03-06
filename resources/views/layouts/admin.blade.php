<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>GAIAGS</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>

    </ul>

    <!-- SEARCH FORM -->
      <!--<div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" @keyup="searchit" v-model="search" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" @click="searchit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>-->

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src="./images/logo.png" alt="Curos Edwin" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Curos Edwin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="./images/profile.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
              {{Auth::user()->name}}
              <!--<p>{{Auth::user()->type}}</p>-->
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <li class="nav-item">
            <router-link to="/dashboard" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt blue"></i>
                <p>
                Dashboard

                </p>
            </router-link>
            </li>

            {{--@can('isAdmin')--}}
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-cog green"></i>
              <p>
                Administracion
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/courses" class="nav-link">
                    <i class="fas fa-book-open red"></i>
                  <p>Cursos</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/teachers" class="nav-link">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <p>Profesores</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/students" class="nav-link">
                  <i class="fas fa-users indigo" aria-hidden="true"></i>                    
                  <p>Estudiantes</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/coupon" class="nav-link">
                    <i class="fas fa-comment-dollar green"></i>                    
                    <p>Cupones</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/category" class="nav-link">
                  <i class="fas fa-certificate  indigo  "></i>                                       
                    <p>Categorias</p>
                </router-link>
              </li>

            </ul>
          </li>

          <li class="nav-item">
              <router-link to="/paypal" class="nav-link">
                  <i class="fab fa-paypal blue"></i>
                  <p>
                      Paypal
                  </p>
              </router-link>
         </li>
         <li class="nav-item">
          <router-link to="/epay" class="nav-link">
              <i class="fab fa-paypal yellow"></i>
              <p>
                  Planes Epay
              </p>
          </router-link>
     </li>
         <li class="nav-item">
            <router-link to="/payu" class="nav-link">
                <i class="fab fa-paypal green"></i>
                <p>
                    Pagos Membresia
                </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/user_payment" class="nav-link">
                <i class="fab fa-paypal purple"></i>
                <p>
                    Usuarios Cursos Pagos
                </p>
            </router-link>
          </li>
         <li class="nav-item">
            <router-link to="/profile" class="nav-link">
              <i class="fas fa-key  indigo  "></i>                
                <p>
                    Contraseñas
                </p>
            </router-link>
        </li>
         <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fa fa-cog green"></i>
            <p>
              Area de Traiding
              <i class="right fa fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <router-link to="/company" class="nav-link">
                  <i class="fas fa-building indigo"></i>                 
                  <p>Company</p>
              </router-link>
            </li> 
            <li class="nav-item">
              <router-link to="/quote" class="nav-link">
                <i class="fas fa-quote-right red"></i>                  
                <p>Quote</p>
              </router-link>
            </li>  
            <li class="nav-item">
              <router-link to="/previous" class="nav-link">
                <i class="fas fa-angle-double-left yellow"></i>                  
                <p>Previous</p>
              </router-link>
            </li>    
            <li class="nav-item">
              <router-link to="/keystat" class="nav-link">
                <i class="fas fa-key purple"></i>                  
                <p>KeyStat</p>
              </router-link>
            </li>                

          </ul>
        </li>
        {{-- @endcan--}}          

          <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                    <i class="nav-icon fa fa-power-off red"></i>
                    <p>
                        {{ __('Logout') }}
                    </p>
                 </a>

             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
             </form>
        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper mt-5">

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>

        <vue-progress-bar></vue-progress-bar>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Cursos Online
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2019 <a href="https://escueladeinversionistas.online">Curos Edwin</a>.</strong> Todos los derechos reservados
  </footer>
</div>
<!-- ./wrapper -->

@auth
<script>
    window.user = @json(auth()->user())
</script>
@endauth

<script src="/js/app.js"></script>
</body>
</html>


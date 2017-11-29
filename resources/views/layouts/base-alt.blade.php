<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>CTO :: {{ $page['title'] }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}">
    @stack('styles_head')
    @yield('content_head')
</head>
<body>
@section('navbar')
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand text-center">
                    <a href="http://portal.mrcroquet.mx/mrpanel_0923rj390fj23/dashboard">
                        <img src="{{ asset('assets/images/brand/logo_cto.jpeg') }}" alt="" height="65">
                    </a>
                </li>
                <li>
                    <a href="http://portal.mrcroquet.mx/mrpanel_0923rj390fj23/dashboard">
                        <i class="fa fa-dashboard margin-right--10"></i> Escritorio
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle" type="button" id="meDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="fa fa-user-circle"></span>
                        {{ Auth::user()['person']->PersonaNombres }} <span class="caret"></span>
                    </a>
                </li>
                <li>
                    <a role="button" data-toggle="collapse" href="#collapseWorks" aria-expanded="false" aria-controls="collapseWorks" class="collapsed">
                        <span class="fa fa-cubes"></span>
                        Obras <span class="caret"></span>
                    </a>
                    <div class="collapse" id="collapseWorks" aria-expanded="false" style="height: 0px;">
                        <ul class="collapse-nav">
                            <?php $worksAll = App\Models\ConstructionWork::all()->take(10) ?>
                            <li class="dropdown-header">
                                Activas ({{ $worksAll->count() }})
                            </li>
                            @foreach($worksAll as $work)
                                <li>
                                    <a href="{{ url('/panel/constructionwork') }}/info/{{ $work->tbObraID  }}">{{ $work->ObraClave  }}</a>
                                </li>
                            @endforeach
                            @if($worksAll->count() == 10)
                                <li>
                                    <a href="">Ver todas las activas</a>
                                </li>
                            @endif
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="">Terminadas</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <!--<nav class="navbar navbar-default navbar-static-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a href="{{ url('panel') }}" class="navbar-brand">
                            <img src="{{ asset('assets/images/brand/logo_cto.jpeg') }}" alt="">
                        </a>
                        <p class="navbar-text margin-left--clear small">
                            CTO <span class="fa fa-caret-right fa-fw"></span>
                            {{ $page['title'] }}
                        </p>
                    </div>
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="fa fa-cubes fa-fw"></span>
                                    Obras <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" style="width: 300px">
                                    <?php $worksAll = App\Models\ConstructionWork::all()->take(10) ?>
                                    <li class="dropdown-header">
                                        Activas ({{ $worksAll->count() }})
                                    </li>
                                    @foreach($worksAll as $work)
                                        <li>
                                            <a href="{{ url('/panel/constructionwork') }}/info/{{ $work->tbObraID  }}">{{ $work->ObraClave  }}</a>
                                        </li>
                                    @endforeach
                                    @if($worksAll->count() == 10)
                                        <li>
                                            <a href="">Ver todas las activas</a>
                                        </li>
                                    @endif
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <a href="">Terminadas</a>
                                    </li>
                                </ul>
                            </li>
                            @if ( auth_permissions(Auth::user()['role'])->grant == 1)
                                <li class="dropdown dropdown-large">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <span class="fa fa-cog fa-fw"></span>
                                        Sistema <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-large row">
                                        <li class="col-sm-4">
                                            <ul>
                                                <li class="dropdown-header">
                                                    Directorios
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/business') }}">
                                                        Empresas
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        Libre
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/myBusiness') }}">
                                                        Mis empresas
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/persons') }}">
                                                        Personas
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/unities') }}">
                                                        Unidades
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/users') }}">
                                                        Usuarios
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/customers') }}">
                                                        Clientes
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="col-sm-4">
                                            <ul>
                                                <li class="dropdown-header">
                                                    Finanzas
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/aplications') }}">
                                                        Aplicaciones
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/items') }}">
                                                        Rubros
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/invoices') }}">
                                                        Facturas
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/currencies') }}">
                                                        Monedas
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="col-sm-4">
                                            <ul>
                                                <li class="dropdown-header">
                                                    Información
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/arancel') }}">
                                                        Arancel
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        Fechas
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/imss') }}">
                                                        IMSS
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/groups') }}">
                                                        Grupos
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/emails') }}">
                                                        Correos
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('panel/system/phones') }}">
                                                        Teléfonos
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if (auth_permissions(Auth::user()['role'])->grant == 1)
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" type="button" id="businessDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="fa fa-building fa-fw"></span>
                                        Empresa <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="businessDropdownMenu">
                                        <li>
                                            <a href="#">Ejemplo</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" type="button" id="meDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fa fa-user-circle fa-fw"></span>
                                    {{ Auth::user()['person']->PersonaNombres }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="meDropdownMenu">
                                    <li>
                                        <a href="#">Perfil</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/action/logout')  }}">Cerrar sesión</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>-->
        </div>
        <div id="container-wrapper">
            @yield('content')
        </div>
        <footer class="container-fluid margin-bottom--30">
            <div class="row margin-top--50">
                <div class="col-sm-12 text-center small">
                    <p>&copy; {{ Carbon\Carbon::now()->year }} BFC Arquitectos S.C.</p>
                    <p>
                        <span class="fa fa-globe fa-fw"></span>
                        <a href="{{ url('language/es') }}">Español</a> /
                        <a href="{{ url('language/en') }}">English</a>

                    </p>
                </div>
            </div>
        </footer>
    </div>
@show
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
@stack('scripts_footer')
</body>
</html>
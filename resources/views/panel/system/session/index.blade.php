@extends('layouts.base')

@section('content')
    {{--
        
        Contenido de la sección
        
        Se mostra toda la lista de registro y la información de un registro
        seleccionado.

        <data-content>
    --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                @include('layouts.alerts', ['errors' => $errors])
            </div>
            <div class="col-sm-12">
                <div class="panel panel-default panel-section">
                    @if (count($sessions['all']) == 0)
                        <div class="panel-body text-center">
                            <h3>No hay empresas registradas</h3>
                            <a href="{{ $navigation['base'] }}/save" class="btn btn-link btn-lg">Agregar empresa</a>
                        </div>
                    @else
                        <div class="panel-body">
                            <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
                                <div class="list-group-item padding-clear padding-bottom--10">
                                    <form action="{{ $navigation['base'] }}/search" method="get">
                                        <div class="input-group input-group-sm">
                                            <input name="q" type="text" class="form-control form-control-plain" placeholder="Busqueda">
                                            <span class="input-group-btn">
										    	<button class="btn btn-default" type="submit">
										    		<span class="fa fa-search fa-fw"></span>
										    	</button>
										    </span>
                                        </div>
                                    </form>
                                </div>
                                @foreach ($sessions['all'] as $index => $session)
                                    @if ($index == 0)
                                        <a href="{{ $navigation['base'] }}/info/{{ $session->TbCTOSesionID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item active">
                                            <h4 class="list-group-item-heading">
                                                {{ $session->user->CTOUsuarioNombre  }}-{{ $session->user->person->PersonaAlias  }}
                                            </h4>
                                            <p class="small">
                                                {{ Carbon\Carbon::parse($session->SesionIniciaTimestamp)->formatLocalized('%d %B %Y') }}
                                            </p>
                                        </a>
                                        @continue
                                    @endif
                                    <a href="{{ $navigation['base'] }}/info/{{ $session->TbCTOSesionID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">
                                            {{ $session->user->CTOUsuarioNombre  }}-{{ $session->user->person->PersonaAlias  }}
                                        </h4>
                                        <p class="small">
                                            {{ Carbon\Carbon::parse($session->SesionIniciaTimestamp)->formatLocalized('%d %B %Y') }}
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                            <div>
                                <div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
                                    {{--

										Formulario para eliminar un registro
										Si es index hay que sobre escribre la variable $model['one']
									--}}
                                    @include('panel.system.currency.shared.delete-form', [
                                        'title' => 'Sesión',
                                        'name' => $sessions['all'][0]->CTOUsuarioNombre,
                                        'id' => $sessions['all'][0]->user->TbCTOSesionID,
                                    ])
                                    <div class="col-sm-12">
                                        <ul class="nav nav-actions navbar-nav navbar-right">
                                            <li>
                                                <a href="#" id="confirmDeleteButton" class="is-tooltip" data-placement="bottom" title="Eliminar">
                                                    <span class="fa-stack text-danger fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x"></i>
                                                        <span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group col-sm-12">
                                            <label>Usuario</label>
                                            <p>{{ $sessions['all'][0]->user->CTOUsuarioNombre  }} - {{ $sessions['all'][0]->user->person->PersonaAlias  }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Fecha Inicia</label>
                                            <p>{{ $sessions['all'][0]->SesionIniciaTimestamp }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Fecha Termina</label>
                                            <p>{{ $sessions['all'][0]->SesionTerminaTimestamp }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Duración</label>
                                            <p>{{ $sessions['all'][0]->SesionDuracion  }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- Se mostrara el menú de las página disponibles --}}
                {{ $navigation['pagination'] }}
            </div>
        </div>
    </div>
    {{-- </data-content> --}}
    {{-- Modal para filtrar --}}
    @include('panel.system.business.shared.filter-modal')
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
<script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
<script>
    (function() {

        var app = new App();
        app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
        //app.scrollNavActions();
        app.tooltip();
    })();
</script>
@endpush
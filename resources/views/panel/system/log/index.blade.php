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
                    @if (count($logs['all']) == 0)
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
                                @foreach ($logs['all'] as $index => $log)
                                    @if ($index == 0)
                                        <a href="{{ $navigation['base'] }}/info/{{ $log->tbCTOID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item active">
                                            <h4 class="list-group-item-heading">
                                                {{ $log->CTOLog_Registro  }}-{{ $log->CTOLog_Accion }} <span class="fa fa-caret-right fa-fw"></span>
                                            </h4>
                                            <p class="small">
                                                {{ Carbon\Carbon::parse($log->CTOLog_Timestamp)->formatLocalized('%d %B %Y') }}
                                            </p>
                                        </a>
                                        @continue
                                    @endif
                                    <a href="{{ $navigation['base'] }}/info/{{ $log->tbCTOID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">
                                            {{ $log->CTOLog_Registro  }}-{{ $log->CTOLog_Accion }} <span class="fa fa-caret-right fa-fw"></span>
                                        </h4>
                                        <p class="small">
                                            {{ Carbon\Carbon::parse($log->CTOLog_Timestamp)->formatLocalized('%d %B %Y') }}
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
                                    @include('panel.system.business.shared.delete-form', ['business' => [ 'one' => $logs['all'][0]]])
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
                                            <p>{{ $logs['all'][0]->user->CTOUsuarioNombre  }} - {{ $logs['all'][0]->user->person->PersonaAlias  }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Tabla</label>
                                            <p>{{ $logs['all'][0]->CTOLog_Tabla  }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Acción</label>
                                            <p>{{ $logs['all'][0]->CTOLog_Accion  }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Registro</label>
                                            <p>{{ $logs['all'][0]->CTOLog_Registro  }}</p>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-4">
                                            <label>Campo</label>
                                            <p>{{ $logs['all'][0]->CTOLog_Campo  }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor anterior</label>
                                            <p>{{ $logs['all'][0]->CTOLog_ValorOLD  }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Valor nuevo</label>
                                            <p>{{ $logs['all'][0]->CTOLog_ValorNEW  }}</p>
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
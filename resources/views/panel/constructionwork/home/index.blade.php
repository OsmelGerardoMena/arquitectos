@extends('layouts.base')
{{--
    Content
    Cuerpo de la vista
--}}
@section('content')
	{{--
        Alertas
        Se mostraran las alertas que el sistema envíe
        si se redirecciona a index
    --}}
	@include('shared.alerts', ['errors' => $errors])
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section bg-white">
					<div class="col-sm-8">
                        @if ($works['one']->RegistroInactivo == 0)
                            <ul class="nav nav-pills nav-works">
                                <li class="{{ $navigation['current']['child'] == 'home' ? 'active' : '' }}">
                                    <a href="{{ url('panel/constructionwork/home')  }}/{{ $works['one']->tbObraID }}">Inicio</a>
                                </li>
                                <li role="presentation" class="dropdown {{ $navigation['current']['father'] == 'coordinations' ? 'active' : '' }}">
                                    <a href="#" id="cordinationDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Coordinación
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="cordinationDropdownMenu">
                                        <li class="{{ $navigation['current']['child'] == 'daily' ? 'active' : '' }}">
                                        <!--<a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/estimates">Diario de la obra</a>-->
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/dailys_work/info">Diario de la obra</a>
                                        </li>
                                    </ul>
                                </li>
                                <li role="presentation" class="dropdown {{ $navigation['current']['father'] == 'datas' ? 'active' : '' }}">
                                    <a href="#" id="dataDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Ubicación
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dataDropdownMenu">
                                        <li class="{{ $navigation['current']['child'] == 'buildings' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/buildings/info">Edificios</a>
                                        </li>
                                        <li class="{{ $navigation['current']['child'] == 'levels' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/levels/info">Niveles</a>
                                        </li>
                                        <li class="{{ $navigation['current']['child'] == 'locals' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/locals/info">Locales</a>
                                        </li>
                                    </ul>
                                </li>
                                <li role="presentation" class="dropdown {{ $navigation['current']['father'] == 'directory' ? 'active' : '' }}">
                                    <a href="#" id="directoriesDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Directorios
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="directoriesDropdownMenu">
                                        <li class="{{ $navigation['current']['child'] == 'business' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/business/info">Empresas</a>
                                        </li>
                                        <li class="{{ $navigation['current']['child'] == 'persons' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/persons/info">Personas</a>
                                        </li>
                                    </ul>
                                </li>
                                <li role="presentation" class="dropdown {{ $navigation['current']['father'] == 'finances' ? 'active' : '' }}">
                                    <a href="#" id="financesDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Finanzas
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="financesDropdownMenu">
                                        <li class="{{ $navigation['current']['child'] == 'catalogs' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/catalogs">Catálogos</a>
                                        </li>
                                        <li class="{{ $navigation['current']['child'] == 'generators' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/generators">Generadores</a>
                                        </li>
                                        <li class="{{ $navigation['current']['child'] == 'estimates' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/estimates">Estimaciones</a>
                                        </li>
                                    </ul>
                                </li>
                                <li role="presentation" class="dropdown {{ $navigation['current']['father'] == 'legal' ? 'active' : '' }}">
                                    <a href="#" id="legalDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Legal
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="legalDropdownMenu">
                                        <li class="{{ $navigation['current']['child'] == 'contracts' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/contracts">Contratos</a>
                                        </li>
                                        <li class="{{ $navigation['current']['child'] == 'binnacles' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/binnacles/info">Bitácoras</a>
                                        </li>
                                        <li class="{{ $navigation['current']['child'] == 'trades' ? 'active' : '' }}">
                                            <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/trades/info">Oficios</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        @else
                            <ul class="nav nav-pills nav-works">
                                <li class="disabled">
                                    <a href="#">Inicio</a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">
                                        Coordinación
                                        <span class="caret"></span>
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">
                                        Ubicación
                                        <span class="caret"></span>
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">
                                        Directorios
                                        <span class="caret"></span>
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">
                                        Finanzas
                                        <span class="caret"></span>
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#">
                                        Legal
                                        <span class="caret"></span>
                                    </a>
                                </li>
                            </ul>
                        @endif
					</div>
					<div class="col-sm-4">
						{{--
                            Nav Actions
                            Se incluyen los botones de acción para los registros

                            @param $model['count'] total de registros
                            @param $model['id'] id del registro
                        --}}
						@include('shared.nav-actions-info',
                            [ 'model' =>
                                [
                                    'count' => $works['all']->count(),
                                    'id' => ($works['all']->count() > 0) ? $works['one']->getKey() : 0,
                                    'inactive' => ($works['all']->count() > 0) ? $works['one']->RegistroInactivo : 0,
                                    'closed' => ($works['all']->count() > 0) ? $works['one']->RegistroCerrado : 0
                                ]
                            ]
                        )
					</div>
					<div class="clearfix"></div>
					{{--
                        Content Info
                        Se incluye la estructura donde se muestra la información del registro
                    --}}
					@include('panel.constructionwork.home.shared.content-info')
				</div>
			</div>
		</div>
	</div>
	{{--
        Este campo sirve para indicar en el listado que registro se esta visualizando
    --}}
	<input type="hidden" name="_recordId" value="{{ ($works['all']->count() > 0) ? $works['one']->getKey() : 0 }}">
	{{--
        Modals
        Se incluyen los modales que se requieren para el registro
    --}}
	@include('panel.constructionwork.home.shared.filter-modal')
	@include('shared.record-closed-modal')
	@include('shared.record-opened-modal')
	@include('shared.record-delete-modal', [
        'id' => ($works['all']->count() > 0) ? $works['one']->getKey() : 0,
        'work' => $works['one']->tbObraID,
    ])
	@include('shared.record-restore-modal', [
        'id' => ($works['all']->count() > 0) ? $works['one']->getKey() : 0,
        'work' => $works['one']->tbObraID,
    ])
@endsection
{{--
    Scripts footer
    Se agregan script js al archivo base de las vistas
--}}
@push('scripts_footer')
	<script src="{{ asset('assets/js/hr.js') }}"></script>
	<script src="{{ asset('assets/js/numeral.min.js') }}"></script>
	<script>
        (function() {
            var app = new App();
            app.initItemsList({ fitRelationHeight: 275 });
            app.filterModal();
            app.tooltip();
            app.highlightSearch();
            app.onPageTab();

            app.closeRecord({
                url: "{{ url('/ajax/action/records/closed') }}",
                table: "{{  ($works['all']->count() > 0) ? $works['one']->getTable() : '' }}",
                tableId: "{{  ($works['all']->count() > 0) ? $works['one']->getKeyName() : '' }}"
            });
			
        })();
	</script>
@endpush
@extends('layouts.base')
@section('content')
	{{--
		Alertas
		Se mostraran las alertas que el sistema envíe
		si se redirecciona a index

		<alerts>
	--}}
    @include('layouts.alerts', ['errors' => $errors])
	{{-- </alerts> --}}
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default panel-section bg-white">
					<div class="col-sm-8">
						@include('panel.constructionwork.shared.submenu')
					</div>
                    <div class="col-sm-4"></div>
					<div class="clearfix"></div>
					<div class="panel-body padding-top--clear margin-top--5">
                        <div>
                            <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
                                <div class="list-group-item padding-clear padding-bottom--5">
                                    <h4 class="margin-clear margin-top--5">Información de obra</h4>
                                </div>
                                <div id="item{{ $works['one']->tbObraID  }}" class="list-group-item active">
                                    <h4 class="list-group-item-heading">{{ $works['one']->ObraAlias }}</h4>
                                    <p class="small">
                                        {{ $works['one']->ObraNombreCompleto }}
                                    </p>
                                </div>
                                <div id="itemsList">

                                </div>
                            </div>
                            <div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-left--clear padding-right--clear" style="position: relative;">
                                <div class="col-sm-12 margin-bottom--5">
                                    <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#general" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                                                General
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#dates" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                                                Fechas
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#details" aria-controls="general" role="tab" data-toggle="tab" data-type="own">
                                                Detalles
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content col-sm-12 margin-bottom--20">
                                    <div role="tabpanel" class="tab-pane active row padding-top--5" id="general">
                                        <div class="col-sm-4">
                                            @if( empty($works['one']->ObraImagen) )
                                                <div class="panel-item--image"><p>No disponible.</p></div>
                                            @else
                                                <div class="panel-item--image" style="background-image: url({{ url('panel/images/'.$dailywork['one']->DiarioImagen) }}) ">
                                                    <div class="panel-item--image_nav">
                                                        <button type="button" class="btn btn-primary btn-xs is-tooltip" title="Ver" data-image="{{ url('panel/images/'.$works['one']->ObraImagen) }}" data-toggle="modal" data-target="#showImageModal" data-placement="bottom">
                                                            <span class="fa fa-eye fa-fw"></span>
                                                        </button>

                                                        <a href="{{ url('panel/images/'.$works['one']->ObraImagen) }}" class="btn btn-primary btn-xs is-tooltip" title="Descargar" data-placement="bottom" download>
                                                            <span class="fa fa-download fa-fw"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-8">
                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label class="form-label-full">Obra</label>
                                                    <p class="help-block">{{ ifempty($works['one']->ObraAlias) }}</p>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label-full">Índice</label>
                                                    <p class="help-block">{{ ifempty($works['one']->ObraIndice) }}</p>
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label-full">Clave</label>
                                                    <p class="help-block">{{ ifempty($works['one']->ObraClave) }}</p>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label class="form-label-full">Propietario</label>
                                                    <p class="help-block"> - </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-12">
                                            <label class="form-label-full">Nombre completo</label>
                                            <p class="help-block">{{ ifempty($works['one']->ObraNombreCompleto) }}</p>
                                        </div>
                                        <div class="form-group col-sm-8">
                                            <label class="form-label-full">Descripción completa</label>
                                            <p class="help-block help-block--textarea">{{ ifempty($works['one']->ObraDescripcion) }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Descripción corta</label>
                                            <p class="help-block help-block--textarea">{{ ifempty($works['one']->ObraDescripcionCorta) }}</p>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label class="form-label-full">Sucursal </label>
                                            <p class="help-block">{{ ifempty($works['one']->ObraSucursalNombre) }}</p>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label class="form-label-full">Domicilio</label>
                                            <p class="help-block"> - </p>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane row padding-top--5" id="dates">
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Inicio oficial</label>
                                            <p class="help-block">{{ Carbon\Carbon::parse( $works['one']->ObraFechaInicioOficial )->formatLocalized('%A %d %B %Y') }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Término oficial</label>
                                            <p class="help-block">{{ Carbon\Carbon::parse( $works['one']->ObraTerminoFechaOficial )->formatLocalized('%A %d %B %Y') }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Duración oficial</label>
                                            <p class="help-block">{{ ifempty($works['one']->ObraDuracionOficial, '0') }} Días</p>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Inicio real</label>
                                            <p class="help-block">{{ Carbon\Carbon::parse( $works['one']->ObraFechaInicioReal)->formatLocalized('%A %d %B %Y') }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Término real</label>
                                            <p class="help-block">{{ Carbon\Carbon::parse( $works['one']->ObraFechaTerminoReal )->formatLocalized('%A %d %B %Y') }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Duración real</label>
                                            <p class="help-block">{{ ifempty($works['one']->ObraDuracionReal, '0') }} Días</p>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane row padding-top--5" id="details">
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Superficie interior</label>
                                            <p class="help-block">{{ number_format( ifempty($works['one']->ObraSuperficieInterior, 0), 2) }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Superficie exterior</label>
                                            <p class="help-block">{{ number_format( ifempty($works['one']->ObraSuperficieExterior, 0), 2) }}</p>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Superficie total</label>
                                            <p class="help-block">{{ number_format(ifempty($works['one']->ObraSuperficieTotal, 0), 2) }}</p>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group col-sm-4">
                                            <label class="form-label-full">Tipo de obra</label>
                                            <p class="help-block">{{ ifempty($works['one']->ObraTipo) }}</p>
                                        </div>
                                        <!--div class="form-group col-sm-4">
                                            <label class="form-label-full">Genero</label>
                                            <p class="help-block">{{ ifempty($works['one']->TnGeneroID_Obra)}}</p>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('scripts_footer')
    <script>
    	(function() {

            var app = new App();
            app.initItemsList({ fitListHeight: 255 });
            app.tooltip();
    	})();
    </script>
@endpush
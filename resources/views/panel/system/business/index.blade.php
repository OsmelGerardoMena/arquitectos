@extends('layouts.base')

@section('content')

    @include('layouts.alerts', ['errors' => $errors])
    {{--

        Contenido de la sección

        Se mostra toda la lista de registro y la información de un registro
        seleccionado.

        <data-content>
    --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default panel-section">
                    @if (count($business['all']) == 0)
                        <div class="panel-body text-center">
                            <h3>No hay empresas registradas</h3>
                            <a href="{{ $navigation['base'].'/save' }}" class="btn btn-link btn-lg">Agregar empresa</a>
                        </div>
                    @else
                        <div class="panel-body">
                            <div class="list-group col-sm-5 col-md-4 col-lg-3 margin-clear panel-item" style="position: relative;">
                                <div class="list-group-item padding-clear padding-bottom--10">
                                    <form action="{{ $navigation['base'].'/search' }}">
                                        <div class="input-group input-group-sm">
                                            <input id="search" name="q" type="text" class="form-control form-control-plain" placeholder="Busqueda">
                                            <span class="input-group-btn">
										    	<button class="btn btn-default" type="submit">
										    		<span class="fa fa-search fa-fw"></span>
										    	</button>
                                                <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalFilter">
										    		<span class="fa fa-filter fa-fw"></span>
										    	</button>
										    </span>
                                        </div>
                                    </form>
                                </div>
                                @foreach ($business['all'] as $index => $b)

                                    @if ($index == 0)
                                        <a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item active">
                                            <h4 class="list-group-item-heading">
                                                {{ $b->EmpresaRazonSocial }}
                                            </h4>
                                            <p class="small">
                                                {{ $b->EmpresaNombreComercial }}
                                            </p>
                                        </a>
                                        @continue
                                    @endif

                                    <a href="{{ $navigation['base'] }}/info/{{ $b->tbDirEmpresaID  }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="list-group-item">
                                        <h4 class="list-group-item-heading">
                                            {{ $b->EmpresaRazonSocial }}
                                        </h4>
                                        <p class="small">
                                            {{ $b->EmpresaNombreComercial }}
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                            <div>
                                <div class="col-sm-7 col-md-8 col-lg-9 panel-item padding-right--clear" style="position: relative;">
                                    {{--

                                        Formulario para eliminar un registro
                                        Si es index hay que sobre escribre la variable $model['one']
                                    --}}
                                    @include('panel.system.business.shared.delete-form', ['business' => [ 'one' => $business['all'][0]]])
                                    <div class="col-sm-12">
                                        <ul class="nav nav-actions navbar-nav navbar-right">
                                            <li>
                                                <a href="{{ $navigation['base'].'/save' }}" class="is-tooltip" data-placement="bottom" title="Nueva empresa">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-plus fa-stack-1x fa-inverse"></i>
									</span>
                                                </a>
                                            </li>
                                            <!--<li>
                                                <a href="#" data-placement="bottom" title="Filtrar"
                                                   data-toggle="modal" data-target="#modalFilter"
                                                   class="is-tooltip">
											<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-filter fa-stack-1x fa-inverse"></i>
									</span>
                                                </a>
                                            </li>-->
                                            @if (count($business['all']) > 0)
                                                <li>
                                                    <a href="{{ $navigation['base'] }}/update/{{ $business['all'][0]->tbDirEmpresaID }}{{ ($navigation['page'] > 0) ? '?page='.$navigation['page'] : '' }}" class="is-tooltip" data-placement="bottom" title="Editar">
									<span class="fa-stack fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
									</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" id="confirmDeleteButton" class="is-tooltip" data-placement="bottom" title="Eliminar">
									<span class="fa-stack text-danger fa-lg">
										<i class="fa fa-circle fa-stack-2x"></i>
										<span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
									</span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                        <ul class="nav nav-tabs nav-tabs-works" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a>
                                            </li>
                                        <!--<li role="presentation">
                                                        <a href="#concepts" aria-controls="profile" role="tab" data-toggle="tab">Conceptos</a>
                                                    </li>-->
                                            <li role="presentation">
                                                <a href="#addresses" aria-controls="addresses" role="tab" data-toggle="tab">Direcciones</a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comentarios</a>
                                            </li>
                                        </ul>
                                        <form action="{{ $navigation['base'].'/action/update' }}" method="post" accept-charset="utf-8" class="cto_form row margin-top--10 padding-bottom--30">
                                            <div class="tab-content col-sm-12">
                                                <div role="tabpanel" class="tab-pane active" id="general">
                                                    <div class="row">
                                                        <div class="form-group col-sm-4">
                                                            <img src="https://placeholdit.imgix.net/~text?txtsize=20&bg=dddddd&txtclr=333333&txt=Perfil&w=200&h=200" alt="" class="img-responsive" style="margin: 0 auto">
                                                        </div>
                                                        <div class="form-group col-sm-8">
                                                            <label for="cto34_alias">Alias</label>
                                                            <input id="cto34_alias"
                                                                   name="cto34_alias"
                                                                   value="{{ $business['all'][0]->EmpresaAlias }}"
                                                                   readonly="readonly"
                                                                   class="form-control form-control-plain form-control-ghost input-sm">
                                                            <label for="cto34_legalName">Razón Social</label>
                                                            <input id="cto34_legalName"
                                                                   name="cto34_legalName"
                                                                   value="{{ $business['all'][0]->EmpresaRazonSocial }}"
                                                                   readonly="readonly"
                                                                   class="form-control form-control-plain form-control-ghost input-sm">
                                                            <label for="cto34_commercialName">Nombre Comercial</label>
                                                            <input id="cto34_commercialName"
                                                                   name="cto34_commercialName"
                                                                   value="{{ $business['all'][0]->EmpresaNombreComercial }}"
                                                                   readonly="readonly"
                                                                   class="form-control form-control-plain form-control-ghost input-sm">
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="form-group col-sm-4">
                                                            <label for="cto34_dependency" class="form-label-full">Dependencia</label>
                                                            <input id="cto34_dependency"
                                                                   name="cto34_dependency"
                                                                   type="text"
                                                                   value="{{ ifempty($business['all'][0]->EmpresaDependencia) }}"
                                                                   readonly="readonly"
                                                                   class="form-control form-control-plain form-control-ghost input-sm">
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            <label for="cto34_especiality" class="form-label-full">Especialidad</label>
                                                            <input id="cto34_especiality"
                                                                   name="cto34_especiality"
                                                                   type="text"
                                                                   value="{{ ifempty($business['all'][0]->EmpresaEspecialidad) }}"
                                                                   readonly="readonly"
                                                                   class="form-control form-control-plain form-control-ghost input-sm">
                                                        </div>
                                                        <div class="form-group col-sm-4 is-select">
                                                            <label for="cto34_type" class="form-label-full">Tipo de persona</label>
                                                            <p class="help-block">{{ ifempty($business['all'][0]->EmpresaTipoPersona) }}</p>
                                                            <select id="cto34_type"
                                                                    name="cto34_type"
                                                                    class="form-control input-sm hidden">
                                                                <optgroup label="Opción seleccionada">
                                                                    <option value="{{ $business['all'][0]->EmpresaTipoPersona }}">{{ $business['all'][0]->EmpresaTipoPersona }}</option>
                                                                </optgroup>
                                                                <optgroup label="Opciones">
                                                                    <option value="física">física</option>
                                                                    <option value="moral">moral</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="form-group col-sm-4">
                                                            <label for="cto34_slogan" class="form-label-full">Slogan</label>
                                                            <input id="cto34_slogan"
                                                                   name="cto34_slogan"
                                                                   type="text"
                                                                   value="{{ ifempty($business['all'][0]->EmpresaSlogan) }}"
                                                                   class="form-control form-control-plain form-control-ghost input-sm">
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            <label for="cto34_website" class="form-label-full">Página web</label>
                                                            <input id="cto34_website"
                                                                   name="cto34_website"
                                                                   type="text"
                                                                   value="{{ ifempty($business['all'][0]->EmpresaPaginaWeb) }}"
                                                                   class="form-control form-control-plain form-control-ghost input-sm">
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            <label for="cto34_legalId" class="form-label-full">RFC</label>
                                                            <input id="cto34_legalId"
                                                                   name="cto34_legalId"
                                                                   maxlength="25"
                                                                   type="text"
                                                                   value="{{ ifempty($business['all'][0]->EmpresaRFC) }}"
                                                                   class="form-control form-control-plain form-control-ghost input-sm">
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="form-group col-sm-4">
                                                            <label for="cto34_imssNum" class="form-label-full">Número de IMSS</label>
                                                            <input id="cto34_imssNum"
                                                                   name="cto34_imssNum"
                                                                   type="text"
                                                                   value="{{ ifempty($business['all'][0]->EmpresaIMSSNumero) }}"
                                                                   class="form-control form-control-plain form-control-ghost input-sm">
                                                        </div>
                                                        <div class="form-group col-sm-4">
                                                            <label for="cto34_infonavitNum" class="form-label-full">Número de INFONAVIT</label>
                                                            <input id="cto34_infonavitNum"
                                                                   name="cto34_infonavitNum"
                                                                   type="text"
                                                                   value="{{ ifempty($business['all'][0]->EmpresaINFONAVITNumero) }}"
                                                                   class="form-control form-control-plain form-control-ghost input-sm">
                                                        </div>
                                                        <div class="form-group col-sm-4 is-select">
                                                            <label for="cto34_sector" class="form-label-full">Sector</label>
                                                            <p class="help-block">{{ ifempty($business['all'][0]->EmpresaSector) }}</p>
                                                            <select id="cto34_sector"
                                                                    name="cto34_sector"
                                                                    class="form-control form-control-plain form-control-ghost input-sm hidden">
                                                                @if (!empty($business['all'][0]->EmpresaSector ))
                                                                    <optgroup label="Opción seleccionada">
                                                                        <option value="{{ $business['all'][0]->EmpresaSector }}">{{ $business['all'][0]->EmpresaSector }}</option>
                                                                    </optgroup>
                                                                @endif
                                                                <optgroup label="Opciones">
                                                                    <option value="Privado">Privado</option>
                                                                    <option value="Público">Público</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="addresses">
                                                    @forelse($business['all'][0]->addresses as $businessAddress)
                                                        <div class="col-sm-12">
                                                            <b>Domicilio completo</b><br>
                                                            {{ $businessAddress->address->DirDomicilioCompleto }}
                                                            <hr>
                                                        </div>
                                                    @empty
                                                        <div class="col-sm-12 text-center">
                                                            <h3>No hay direcciones registradas</h3>
                                                        </div>
                                                    @endforelse
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="comments">
                                                    <div class="col-sm-12 margin-bottom--10">
                                                        <a href="#" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalComment">Agregar comentario</a>
                                                    </div>
                                                    @forelse($business['all'][0]->comments as $comment)
                                                        <div class="col-sm-12">
                                                            <b>Comentario por {{ $comment->user->person->PersonaNombreCompleto  }}</b><br>
                                                            <p>
                                                                {{ $comment->Comentario }}<br>
                                                                <small class="text-muted">
                                                                    {{  Carbon\Carbon::parse($comment->ComentarioFecha)->formatLocalized('%A %d %B %Y') }}
                                                                </small>
                                                            </p>
                                                            <hr>
                                                        </div>
                                                    @empty
                                                        <div class="col-sm-12">
                                                            <h3>No hay comentarios registrados</h3>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- </data-content> --}}
    {{-- Modal para filtrar --}}
    @include('panel.system.business.shared.filter-modal')
    @include('layouts.comment', [
        'table' => 'tbDirEmpresa',
        'tableId' => $business['all'][0]->tbDirEmpresaID
    ])
@endsection

{{-- Se registran los archivos js requeridos para esta sección --}}
@push('scripts_footer')
    <script src="{{ asset('assets/js/jquery.matchHeight.js') }}"></script>
    <script>
        (function() {

            var app = new App();
            app.deleteConfirm('confirmDeleteAlert', 'confirmDeleteButton', 'cancelDeleteButton');
            app.tooltip();

            $('#modalFilter').on('show.bs.modal', function() {

                var search = $('#search');
                var searchFilter = $('#searchFilter');
                var filterSearchInput = $('#searchInputFilter');

                if (search.val().length === 0) {
                    searchFilter.removeClass('visible').addClass('hidden');
                } else {
                    searchFilter.removeClass('hidden').addClass('visible');
                    filterSearchInput.val(search.val());
                }

            });

        })();
    </script>
@endpush
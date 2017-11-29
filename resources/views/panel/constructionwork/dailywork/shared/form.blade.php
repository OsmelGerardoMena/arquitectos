<form id="{{ $form['id'] }}" action="{{ $form['action'] }}" method="{{ $form['method'] }}" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="col-sm-7">
        <div class="row">
            <div class="form-group col-sm-12">
                <label id="cto34_folio" class="form-label-full">Folio</label>
                @if ($form['with']['model'])
                <p class="help-block">{{ $dailywork['one']->DiarioFolio }}</p>
                @elseif ($form['with']['values'])
                    <input id="cto34_folio" name="cto34_folio" type="text" class="form-control form-control-plain input-sm" value="{{ $count }}" readonly>
                @endif
            </div>
            <div class="form-group col-sm-12">
                <label for="cto34_date_daily" class="form-label-full">Fecha del diario</label>
                <?php $dailyDate = !empty($dailywork['one']->DiarioFecha) ? $dailywork['one']->DiarioFecha : '' ?>
                @if ($form['mode'] == 'read')
                    <p class="help-block">{{ Carbon\Carbon::parse($dailyDate)->formatLocalized('%A %d %B %Y') }}</p>
                @else
                   <?php $value = $form['with']['model'] ? Carbon\Carbon::parse($dailyDate)->formatLocalized('%A %d %B %Y') : old('cto34_date_daily') ?>
                    <div id="cto34_date_dailyContainer" class="input-group input-group-sm date-field">
                        <input id="cto34_date_daily"
                               name="cto34_date_daily"
                               type="text"
                               readonly="readonly"
                               placeholder="{{ $value }}"
                               class="form-control form-control-plain input-sm date-formated">
                        <input name="cto34_date_daily_format" type="hidden"  value="{{ $dailyDate }}">
                        <span class="input-group-addon" style="background-color: #fff">
                            <span class="fa fa-calendar fa-fw text-primary"></span>
                        </span>
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-default btn-date-minus">
                            <span class="fa fa-minus fa-fw"></span>
                        </button>
                        <button type="button" class="btn btn-default btn-today">Hoy</button>
                        <button type="button" class="btn btn-default btn-date-plus">
                            <span class="fa fa-plus fa-fw"></span>
                        </button>
                    </span>
                    </div>
                @endif
            </div>
            <div class="form-group col-sm-12">
                <label class="form-label-full">Asunto</label>
                <?php $value = !empty($dailywork['one']->DiarioAsunto) ? $dailywork['one']->DiarioAsunto : '' ?>
                @if ($form['mode'] == 'read')
                    <p class="help-block">{{ ifempty($dailywork['one']->DiarioAsunto) }}</p>
                @else
                    <input id="cto34_subject"
                           name="cto34_subject"
                           type="text"
                           value="{{ $value }}"
                           class="form-control form-control-plain input-sm">
                @endif
            </div>
            <div class="form-group col-sm-12">
                <label class="form-label-full">Descripción</label>
                @if ($form['mode'] == 'read')
                    <p class="help-block help-block--textarea">{{ ifempty($dailywork['one']->DiarioDescripcion) }}</p>
                @else
                    <?php $value = !empty($dailywork['one']->DiarioDescripcion) ? $dailywork['one']->DiarioDescripcion : '' ?>
                    <textarea maxlength="4000" name="cto34_description" id="cto34_description" class="form-control input-sm" rows="3">{{ $value }}</textarea>
                    <small class="form-count small text-muted margin-clear"><span class="form-counter">0</span>/4000</small>
                @endif
            </div>
            <div class="form-group col-sm-12">
                <label class="form-label-full">Autor</label>
                @if ($form['mode'] == 'read')
                    @if( empty( $dailywork['one']->author ) )
                        <p class="help-block"> - </p>
                    @else
                        <a href="#" data-id="{{  $dailywork['one']->tbDirPersonaObraID_DiarioAutor }}" data-toggle="modal" data-target="#showPersonModal" class="help-block">
                            {{ $dailywork['one']->author->PersonaNombreDirecto  }}
                        </a>
                    @endif
                @else
                    <div class="input-group input-group-sm">
                        <select id="cto34_author"
                                name="cto34_author"
                                data-live-search="true"
                                data-width="100%"
                                data-style="btn-sm btn-default"
                                data-modal-title="Autor"
                                class="selectpicker with-ajax">
                            @if( !empty($dailywork['one']->author->PersonaNombreDirecto) )
                                <option value="{{ $dailywork['one']->tbDirPersonaObraID_DiarioAutor }}" selected="selected">
                                    {{ $dailywork['one']->author->PersonaNombreDirecto }}
                                </option>
                            @endif
                        </select>
                        @if (!empty($dailywork['one']->author->PersonaNombreDirecto))
                            <input type="hidden" id="cto34_authorName" name="cto34_authorName" value="{{ $dailywork['one']->author->PersonaNombreDirecto }}">
                        @endif
                        <span class="input-group-btn">
                            <button id="personMe" type="button" class="btn btn-default" data-id="{{ Auth::user()['person']->tbDirPersonaID }}" data-name="{{ Auth::user()['person']->PersonaNombreDirecto }}">Yo</button>
                        </span>
                    </div>
                @endif
            </div>
            <div class="form-group col-sm-12">
                <label class="form-label-full">Capturado por</label>
                @if ($form['mode'] == 'read')
                    <p class="help-block">
                        @if( empty( $dailywork['one']->catcher) ) )
                            No disponible
                        @else
                            {{ ifempty($dailywork['one']->catcher->person->PersonaNombreDirecto) }}
                        @endif
                    </p>
                @else
                    @if ($form['with']['model'])
                        <p class="help-block">
                            @if( empty( $dailywork['one']->catcher) ) )
                                No disponible
                            @else
                                {{ ifempty($dailywork['one']->catcher->person->PersonaNombreDirecto) }}
                            @endif
                        </p>
                    @else
                        <p class="help-block">{{ Auth::user()['person']->PersonaNombreDirecto }}</p>
                    @endif
                @endif
            </div>
            <div class="form-group col-sm-12">
                <label class="form-label-full">Fecha de captura</label>
                @if ($form['mode'] == 'read')
                    <p class="help-block">{{ Carbon\Carbon::parse( $dailywork['one']->DiarioCapturoTimestamp )->formatLocalized('%A %d %B %Y') }}</p>
                @else
                    <p class="help-block">{{ Carbon\Carbon::now()->formatLocalized('%A %d %B %Y') }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="row">
            <div class="form-group col-sm-12">
                <label class="form-label-full">
                    Tipo
                    @if ($form['mode'] == 'read')
                        <a href="{{ $navigation['base'].'/search?q=&filter=true&type='.ifempty($dailywork['one']->DiarioTipo, '') }}">
                            <span class="fa fa-filter fa-fw"></span>
                        </a>
                    @endif
                </label>
                @if ($form['mode'] == 'read')
                    <p class="help-block">{{ ifempty($dailywork['one']->DiarioTipo) }}</p>
                @else
                    <select name="cto34_type_daily" id="cto34_type_daily" class="form-control input-sm">
                        <option value="">Seleccionar opción</option>
                        @foreach (type_daily_options() as $option)

                            @if ($form['with']['model'])
                                {{ $selected = $dailywork['one']->DiarioTipo==$option['value'] ? 'selected': '' }}
                            @else
                                {{ $selected = '' }}
                            @endif

                            <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="form-group col-sm-12">
                <label class="form-label-full">Imagen</label>
                @if( empty($dailywork['one']->DiarioImagen) )
                    <div class="panel-item--image" style="height: 240px"><p>No disponible.</p></div>
                @else
                    <div class="panel-item--image" style="height: 240px; background-image: url({{ url('panel/images/'.$dailywork['one']->DiarioImagen) }}) ">
                        <div class="panel-item--image_nav">
                            <button type="button" class="btn btn-primary btn-xs is-tooltip" title="Ver" data-image="{{ url('panel/images/'.$dailywork['one']->DiarioImagen) }}" data-toggle="modal" data-target="#showImageModal" data-placement="bottom">
                                <span class="fa fa-eye fa-fw"></span>
                            </button>

                            <a href="{{ url('panel/images/'.$dailywork['one']->DiarioImagen) }}" class="btn btn-primary btn-xs is-tooltip" title="Descargar" data-placement="bottom" download>
                                <span class="fa fa-download fa-fw"></span>
                            </a>
                        </div>
                    </div>
                @endif
                @if ($form['mode'] == 'read')
                    <input id="cto34_img"
                           name="cto34_img"
                           type="file"
                           value="{{ old('cto34_img') }}"
                           class="form-control form-control-plain input-sm"
                           disabled                           
                           accept="image/gif, image/jpeg, image/jpg, image/png" />
                @else
                    @if ($form['with']['model'])
                        <input id="cto34_img"
                               name="cto34_img"
                               type="file"
                               value=""
                               class="form-control form-control-plain input-sm"
                               accept="image/gif, image/jpeg, image/jpg, image/png" />
                        <input id="cto34_imgOld"
                               name="cto34_imgOld"
                               type="hidden"
                               value="{{ $dailywork['one']->DiarioImagen }}" />
                    @else
                        <input id="cto34_img"
                               name="cto34_img"
                               type="file"
                               value=""
                               class="form-control form-control-plain input-sm"
                               accept="image/gif, image/jpeg, image/jpg, image/png" />
                    @endif
                @endif
                <span>Tamaño maximo 1200 x 1200 pixeles</span>
            </div>
            <div class="form-group col-sm-12">
                <label for="cto34_bottom_copy" class="form-label-full">Pie de foto</label>
                @if ($form['mode'] == 'read')
                    <p class="help-block help-block--textarea">{{ $dailywork['one']->DiarioImagenPieFoto }}</p>
                @else
                    <?php $value = !empty($dailywork['one']->DiarioImagenPieFoto) ? $dailywork['one']->DiarioImagenPieFoto : '' ?>
                    <textarea id="cto34_bottom_copy" maxlength="4000" name="cto34_bottom_copy" rows="3" class="form-control form-control-plain input-sm">{{ $value }}</textarea>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group col-sm-12 text-right">
        <label for="cto34_closed">
            Cerrado:
            <input type="checkbox"  id="cto34_closed" value="{{ old('cto34_closed') }}" name="cto34_close">
        </label>
    </div>
    <div>
        @if ($form['with']['model'])
            <input type="hidden" value="{{ $dailywork['one']->getKey() }}" name="cto34_id">
        @endif        
        <input type="hidden" value="{{ $works['one']->tbObraID }}" name="cto34_work">
        <input type="hidden" name="_method" value="{{ $form['crud'] }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_query" value="{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}">
        <input type="hidden" name="_hasSearch" value="{{ isset($filter['queries']['q']) ? 1 : 0 }}">
    </div>
</form>
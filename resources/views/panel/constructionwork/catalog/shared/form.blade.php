<form id="{{ $form['id'] }}" action="{{ $form['action'] }}" method="{{ $form['method'] }}" accept-charset="utf-8">
    <div class="form-group col-sm-12">
        <label class="form-label-full">Código</label>
        @if ($form['with']['model'])
            <p class="help-block">{{ $catalogs['one']->CatalogoConceptoCodigo  }}</p>
        @elseif ($form['with']['values'])
            <input type="text" name="" class="form-control form-control-plain form-control-sm" readonly>
        @endif
    </div>
    <div class="form-group col-sm-6">
        <label id="cto34_level" class="form-label-full">Ubicación del concepto</label>
        @if ($form['with']['model'])
            <p class="help-block">{{ (!empty($catalogs['one']->level->UbicaNivelAlias)) ? $catalogs['one']->level->UbicaNivelAlias : ' - ' }}</p>
        @elseif ($form['with']['values'])
            <select name="cto34_level" id="cto34_level" class="form-control input-sm">
                <option value="">Seleccionar opción</option>
                @foreach($levels['all'] as $level)
                    <option value="{{ $level->tbUbicaNivelID }}">{{ $level->UbicaNivelAlias }}</option>
                @endforeach
            </select>
        @endif
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-sm-6">
        <div class="row">
            <div class="form-group col-sm-12">
                <label class="form-label-full">Descripción completa</label>
                @if ($form['with']['model'])
                    <p class="help-block help-block--textarea" style="height: 150px">{{ $catalogs['one']->CatalogoDescripcion  }}</p>
                @elseif ($form['with']['values'])
                    <textarea id="cto34_fullDescription"
                              name="cto34_fullDescription"
                              rows="5"
                              maxlength="4000"
                              class="form-control form-control-plain">{{ old('cto34_fullDescription')  }}</textarea>
                @endif
            </div>
            <div class="form-group col-sm-12">
                <label class="form-label-full">Descripción corta</label>
                @if ($form['with']['model'])
                    <p class="help-block help-block--textarea">{{ $catalogs['one']->CatalogoDescripcionCorta  }}</p>
                @elseif ($form['with']['values'])
                    <textarea rows="3"
                              maxlength="4000"
                              class="form-control form-control-plain" disabled></textarea>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group col-sm-6">
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="cto34_unity" class="form-label-full">Unidad</label>
                @if ($form['with']['model'])
                    <p class="help-block">{{ (!empty($catalogs['one']->unity->UnidadAlias)) ? $catalogs['one']->unity->UnidadAlias: ' - '  }}</p>
                @elseif ($form['with']['values'])
                    <select name="cto34_unity" id="cto34_unity" class="form-control input-sm">
                        <option value="0">Seleccionar opción</option>
                        @foreach($unities['all'] as $unity)
                            <option value="{{ $unity->tbUnidadID  }}">{{ $unity->UnidadAlias }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="form-group col-sm-6">
                <label for="cto34_quantity" class="form-label-full">Cantidad</label>
                @if ($form['with']['model'])
                    <p class="help-block">{{ number_format(ifempty($catalogs['one']->CatalogoCantidad, 0), 2)  }}</p>
                @elseif ($form['with']['values'])
                    <input id="cto34_quantity"
                           name="cto34_quantity"
                           type="number"
                           value="{{ old('cto34_quantity') }}"
                           min="1"
                           autocomplete="off"
                           class="form-control form-control-plain input-sm">
                @endif
            </div>
        </div>
    </div>
</form>
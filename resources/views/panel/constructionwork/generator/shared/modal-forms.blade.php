{{--
Modal agregar partida

--}}
<div class="modal fade" id="modalDeparture" tabindex="-1" role="dialog" aria-labelledby="modalDeparture">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Partida</h4>
                <form id="saveDepartureForm" action="{{ url('ajax/action/save/departureWork') }}" method="post" accept-charset="utf-8" class="row">
                    <div class="form-group col-sm-4">
                        <label for="cto34_code">Clave</label>
                        <input id="cto34_code"
                               name="cto34_code"
                               type="text"
                               class="form-control input-sm">
                    </div>
                    <div class="form-group col-sm-8">
                        <label for="cto34_name">Nombre</label>
                        <input id="cto34_name"
                               name="cto34_name"
                               type="text"
                               class="form-control input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-action btn-large">
                            <span class="fa fa-floppy-o fa-fw"></span> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--
    Modal agregar subpartida
 --}}
<div class="modal fade" id="modalSubdeparture" tabindex="-1" role="dialog" aria-labelledby="modalSubdeparture">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title margin-bottom--10">Subpartida</h4>
                <form id="saveSubdepartureForm" action="{{ url('ajax/action/save/subdepartureWork') }}" method="post" accept-charset="utf-8" class="row">
                    <div class="form-group col-sm-4">
                        <label for="cto34_subdeparture_departure" class="form-label-full">Partida</label>
                        <select name="cto34_subdeparture_departure" id="cto34_subdeparture_departure" class="form-control input-sm">
                            <option value="" class="option-default">Seleccionar opción</option>
                            @foreach($departures['all'] as $departure)
                                <option value="{{ $departure->tbPartidaID  }}">
                                    {{ $departure->PartidaNombre  }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-4">
                        <label for="cto34_code">Clave</label>
                        <input id="cto34_code"
                               name="cto34_code"
                               type="text"
                               class="form-control input-sm">
                    </div>
                    <div class="form-group col-sm-8">
                        <label for="cto34_name">Nombre</label>
                        <input id="cto34_name"
                               name="cto34_name"
                               type="text"
                               class="form-control input-sm">
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="hidden" name="cto34_work" value="{{ $works['one']->tbObraID }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="btn btn-action btn-large">
                            <span class="fa fa-floppy-o fa-fw"></span> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@extends('layouts.base')
@push('styles_head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}">
@endpush
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default panel-section bg-white">
                <div class="panel-heading">
                    <table class="table table-clear">
                        <tbody>
                        <tr class="td-center">
                            <td>{{ $page['title'] }}</td>
                            <td class="text-right">
                                <a href="{{ $navigation['base'] }}" class="btn btn-default btn-sm is-tooltip" data-placement="bottom" title="Cerrar">
                                    <div class="text-danger"><span class="fa fa-times fa-fw"></span></div>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                    <div class="col-sm-12">
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ $errors->first() }}
                        </div>
                    </div>
                    @endif
                    @if(session()->has('success'))
                    <div class="col-sm-12">
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ session()->get('success') }}
                        </div>
                    </div>
                    @endif
                    <form id="saveForm" action="{{ $navigation['base'] }}/action/save" method="POST" accept-charset="utf-8" class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-info">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_alias" class="form-label-full">Alias</label>
                                            <input id="cto34_alias"
                                                   name="cto34_alias"
                                                   type="text"
                                                   value="{{ old('cto34_alias') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_index" class="form-label-full">Indice</label>
                                            <input id="cto34_index"
                                                   name="cto34_index"
                                                   type="text"
                                                   value="{{ old('cto34_index') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_code" class="form-label-full">Clave</label>
                                            <input id="cto34_code"
                                                   name="cto34_code"
                                                   type="text"
                                                   value="{{ old('cto34_code') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_customer" class="form-label-full">Propietario</label>
                                            <input id="cto34_customer"
                                                   name="cto34_customer"
                                                   type="text"
                                                   value="{{ old('cto34_customer') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label for="cto34_fullName" class="form-label-full">Nombre completo de obra</label>
                                            <input id="cto34_fullName"
                                                   name="cto34_fullName"
                                                   type="text"
                                                   value="{{ old('cto34_fullName') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div class="form-group">
                                            <label for="cto34_description" class="form-label-full">Descripción</label>
                                            <textarea name="cto34_description" id="cto34_description" cols="30" rows="3" class="form-control"></textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div class="form-group">
                                            <label for="cto34_shortDescription" class="form-label-full">Descripción corta</label>
                                            <textarea name="cto34_shortDescription" id="cto34_shortDescription" cols="30" rows="2" class="form-control"></textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_branch" class="form-label-full">Surcursal</label>
                                            <input id="cto34_branch"
                                                   name="cto34_branch"
                                                   type="text"
                                                   value="{{ old('cto34_branch') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label for="cto34_address" class="form-label-full">Dirección</label>
                                            <input id="cto34_address"
                                                   name="cto34_address"
                                                   type="text"
                                                   value="{{ old('cto34_address') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_startOficialDate" class="form-label-full">Fecha de inicio oficial</label>
                                            <input id="cto34_startOficialDate"
                                                   name="cto34_startOficialDate"
                                                   type="text"
                                                   value="{{ old('cto34_startOficialDate') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_endOficialDate" class="form-label-full">Fecha de termino oficial</label>
                                            <input id="cto34_endOficialDate"
                                                   name="cto34_endOficialDate"
                                                   type="text"
                                                   value="{{ old('cto34_endOficialDate') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_oficialDuration" class="form-label-full">Duración oficial</label>
                                            <input id="cto34_oficialDuration"
                                                   name="cto34_oficialDuration"
                                                   type="text"
                                                   value="{{ old('cto34_oficialDuration') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_startRealDate" class="form-label-full">Fecha de inicio real</label>
                                            <input id="cto34_startRealDate"
                                                   name="cto34_startRealDate"
                                                   type="text"
                                                   value="{{ old('cto34_startOficialDate') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_endRealDate" class="form-label-full">Fecha de termino real</label>
                                            <input id="cto34_endRealDate"
                                                   name="cto34_endRealDate"
                                                   type="text"
                                                   value="{{ old('cto34_endRealDate') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_realDuration" class="form-label-full">Duración real</label>
                                            <input id="cto34_realDuration"
                                                   name="cto34_realDuration"
                                                   type="text"
                                                   value="{{ old('cto34_realDuration') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_openingDate" class="form-label-full">Fecha de inauguración</label>
                                            <input id="cto34_openingDate"
                                                   name="cto34_openingDate"
                                                   type="text"
                                                   value="{{ old('cto34_openingDate') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_type" class="form-label-full">Obra tipo</label>
                                            <input id="cto34_type"
                                                   name="cto34_type"
                                                   type="text"
                                                   value="{{ old('cto34_type') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_kind" class="form-label-full">Genero</label>
                                            <input id="cto34_kind"
                                                   name="cto34_kind"
                                                   type="text"
                                                   value="{{ old('cto34_kind') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_innerSurface" class="form-label-full">Superficie Interior</label>
                                            <input id="cto34_innerSurface"
                                                   name="cto34_innerSurface"
                                                   type="text"
                                                   value="{{ old('cto34_innerSurface') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_outerSurface" class="form-label-full">Superficie Exterior</label>
                                            <input id="cto34_outerSurface"
                                                   name="cto34_outerSurface"
                                                   type="text"
                                                   value="{{ old('cto34_outerSurface') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="cto34_totalSurface" class="form-label-full">Superficie Total</label>
                                            <input id="cto34_totalSurface"
                                                   name="cto34_totalSurface"
                                                   type="text"
                                                   value="{{ old('cto34_totalSurface') }}"
                                                   class="form-control form-control-plain input-sm">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button id="addSubmitButton" type="submit" class="btn btn-default btn-lg-hr btn-submit">Guardar</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts_footer')
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/person.js') }}"></script>
<script>
    (function() {

        var person = new Person();
        person.addNameByLast('#cto34_name', '#cto34_lastName', '#cto34_lastName2', '#cto34_nameByLast');
        person.addDirectName('#cto34_name', '#cto34_lastName', '#cto34_lastName2', '#cto34_directName');
        person.addFullName('#cto34_personPrefix','#cto34_name', '#cto34_lastName', '#cto34_lastName2', '#cto34_fullName');

        var app = new App();
        app.animateSubmit("saveForm", "addSubmitButton");

        var searchOptions = {
            type : 'POST',
            dataType : 'json',
            q :  '@{{{q}}}',
            log : 0
        }

        var es = {
            emptyTitle: 'Seleccionar Persona',
            currentlySelected : 'Opción seleccionada',
            statusInitialized : 'Escribe nombre de la persona',
            searchPlaceholder : 'Buscar',
            statusNoResults : 'No hay resultados'
        }

        var searchClientOptions = {

            ajax: {
                url: '{{ url("ajax/action/search/persons") }}',
                type: searchOptions.type,
                dataType: searchOptions.dataType,
                data: {
                    q: searchOptions.q
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            },

            locale: {
                emptyTitle: es.emptyTitle,
                currentlySelected : es.currentlySelected,
                statusInitialized : es.statusInitialized,
                searchPlaceholder : es.searchPlaceholder,
                statusNoResults : es.statusNoResults,
            },

            log: searchOptions.log,

            preprocessData: function (data) {
                var i, l = data.persons.length, array = [];

                console.log(data);
                console.log(data.persons[0].Nombre);

                if (l) {
                    for (i = 0; i < l; i++) {
                        array.push($.extend(true, data[i], {
                            text : data.persons[i].Nombre,
                            value: data.persons[i].tbDirPersonasID,
                        }));
                    }
                }

                console.log(array);
                // You must always return a valid array when processing data. The
                // data argument passed is a clone and cannot be modified directly.
                return array;
            }
        };

        $('#cto34_person')
            .selectpicker()
            .filter('.with-ajax')
            .ajaxSelectPicker(searchClientOptions);

    })();
</script>
@endpush
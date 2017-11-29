var ConstructionWork = (function() {

    /**
     * Class constructor
     */
    function ConstructionWork() {}

    /**
     * Save Business With Ajax
     * Guardar empresa en obras mediante AJAX
     *
     * @param string form - selector del formulario a enviar
     * @param mixed callback retorna un error o datos
     * @return void
     */
    ConstructionWork.prototype.saveBusinessWithAjax = function(form, callback) {

        $(form).submit(function(event) {

            event.preventDefault();

            var form = $(this);
            var btn = $(form).find('button[type="submit"]');
            var btnValue = btn.html();

            var request = $.ajax({
                url: form.prop('action'),
                type: form.prop('method'),
                dataType: 'html',
                timeout: 90000,
                cache: false,
                data: form.serialize(),
                beforeSend: function() {
                    btn.html('<i class="fa fa-spinner fa-pulse"></i>');
                    btn.prop('disabled', true);
                },
            });

            request.done(function(response) {

                var result = jQuery.parseJSON(response);

                if (result.status === false) {

                    btn.html(btnValue);
                    btn.prop('disabled', false);
                    callback(result.message, null);

                } else {

                    btn.html(btnValue);
                    btn.prop('disabled', false);

                    callback(null, result);
                }
            });

            request.fail(function(xhr, textStatus) {
                btn.html(btnValue);
                btn.prop('disabled', false);
                callback('Error: ' + textStatus, null);
            });
        });
    };

    /**
     * Save Person With Ajax
     * Guardar persona en obras mediante AJAX
     *
     * @param string form - selector del formulario a enviar
     * @param mixed callback retorna un error o datos
     * @return void
     */
    ConstructionWork.prototype.savePersonWithAjax = function(form, callback) {

        $(form).submit(function(event) {

            event.preventDefault();

            var form = $(this);
            var btn = $(form).find('button[type="submit"]');
            var btnValue = btn.html();

            var request = $.ajax({
                url: form.prop('action'),
                type: form.prop('method'),
                dataType: 'html',
                timeout: 90000,
                cache: false,
                data: form.serialize(),
                beforeSend: function() {
                    btn.html('<i class="fa fa-spinner fa-pulse"></i>');
                    btn.prop('disabled', true);
                },
            });

            request.done(function(response) {

                var result = jQuery.parseJSON(response);

                if (result.status === false) {

                    btn.html(btnValue);
                    btn.prop('disabled', false);
                    callback(result.message, null);

                } else {

                    btn.html(btnValue);
                    btn.prop('disabled', false);

                    callback(null, result);
                }
            });

            request.fail(function(xhr, textStatus) {
                btn.html(btnValue);
                btn.prop('disabled', false);
                callback('Error: ' + textStatus, null);
            });
        });
    };

    /**
     * Save Business With Ajax
     * Guardar empresa en obras mediante AJAX
     *
     * @param string form - selector del formulario a enviar
     * @param mixed callback retorna un error o datos
     * @return void
     */
    ConstructionWork.prototype.saveDepartureWithAjax = function(form, callback) {

        $(form).submit(function(event) {

            event.preventDefault();

            var form = $(this);
            var btn = $(form).find('button[type="submit"]');
            var btnValue = btn.html();

            var request = $.ajax({
                url: form.prop('action'),
                type: form.prop('method'),
                dataType: 'html',
                timeout: 90000,
                cache: false,
                data: form.serialize(),
                beforeSend: function() {
                    btn.html('<i class="fa fa-spinner fa-pulse"></i>');
                    btn.prop('disabled', true);
                },
            });

            request.done(function(response) {

                var result = jQuery.parseJSON(response);

                if (result.status === false) {

                    btn.html(btnValue);
                    btn.prop('disabled', false);
                    callback(result.message, null);

                } else {

                    btn.html(btnValue);
                    btn.prop('disabled', false);

                    callback(null, result);
                }
            });

            request.fail(function(xhr, textStatus) {
                btn.html(btnValue);
                btn.prop('disabled', false);
                callback('Error: ' + textStatus, null);
            });
        });
    };

    /**
     * Save Business With Ajax
     * Guardar empresa en obras mediante AJAX
     *
     * @param string form - selector del formulario a enviar
     * @param mixed callback retorna un error o datos
     * @return void
     */
    ConstructionWork.prototype.saveSubdepartureWithAjax = function(form, callback) {

        $(form).submit(function(event) {

            event.preventDefault();

            var form = $(this);
            var btn = $(form).find('button[type="submit"]');
            var btnValue = btn.html();

            var request = $.ajax({
                url: form.prop('action'),
                type: form.prop('method'),
                dataType: 'html',
                timeout: 90000,
                cache: false,
                data: form.serialize(),
                beforeSend: function() {
                    btn.html('<i class="fa fa-spinner fa-pulse"></i>');
                    btn.prop('disabled', true);
                },
            });

            request.done(function(response) {

                var result = jQuery.parseJSON(response);

                if (result.status === false) {

                    btn.html(btnValue);
                    btn.prop('disabled', false);
                    callback(result.message, null);

                } else {

                    btn.html(btnValue);
                    btn.prop('disabled', false);

                    callback(null, result);
                }
            });

            request.fail(function(xhr, textStatus) {
                btn.html(btnValue);
                btn.prop('disabled', false);
                callback('Error: ' + textStatus, null);
            });
        });
    };

    /**
     * Busca una obra mediante Ajax
     *
     * @important Require de las libreria jquery.min.js, bootstrap-select.min.js y ajax-bootstrap-select.min.js
     * @param string element el id o clase del elemento
     * @param string url
     * @param object options opciones para la busqueda
     * @return void
     */
    ConstructionWork.prototype.searchWithAjax = function(element, url, options = {}) {

        var lang;

        if (!options.hasOwnProperty('lang')) {
            lang = {
                emptyTitle: 'Seleccionar persona',
                currentlySelected : 'Opción seleccionada',
                statusInitialized : 'Escribe nombre de la persona',
                searchPlaceholder : 'Buscar',
                statusNoResults : 'No hay resultados',
                statusSearching: 'Buscando...',
                errorText: 'No se puede mostrar la información'
            }
        }

        var searchOptions = {

            ajax: {
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    q: '{{{q}}}'
                },
                headers: (options.hasOwnProperty('headers')) ? options.headers : {},
            },

            locale: lang,
            log: (options.hasOwnProperty('log')) ? options.log : 0,

            preprocessData: function (data) {
                var i, l = data.persons.length, array = [];

                if (l) {
                    for (i = 0; i < l; i++) {
                        array.push($.extend(true, data[i], {
                            text : data.persons[i].PersonaNombreDirecto,
                            value: data.persons[i].tbDirPersonasID,
                        }));
                    }
                }

                return array;
            },

            preserveSelected: (options.hasOwnProperty('preserveSelected')) ? options.preserveSelected : true,
        };

        $(element)
            .selectpicker()
            .filter('.with-ajax')
            .ajaxSelectPicker(searchOptions);
    };



    /*ConstructionWork.prototype.searchBusinessWithAjax = function(element, options) {

        var search = $(element);
        var inputSearch = search.find('.search-field');
        var inputSearchHidden = search.find('.search-hidden');
        var searchMessage = search.find('.search-message');
        var searchOptions = search.find('.search-result');
        var optionSelected = search.find('.search-option');

        $(inputSearch).on('keyup', function() {

            var inputSearch = $(this);

            var request = $.ajax({
                url: (options.hasOwnProperty('url')) ? options.url : '',
                type: 'post',
                dataType: 'html',
                timeout: 90000,
                cache: false,
                data: {
                    q: inputSearch.val(),
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            });

            request.done(function(response) {

                var result = jQuery.parseJSON(response);

                searchMessage.nextAll('li').remove();

                if (result.works.length == 0 || result.works == null ) {
                    searchMessage.html((options.hasOwnProperty('noResults')) ? options.noResults : 'No hay coincidencias de tu busqueda');
                    searchOptions.html('');
                    return;
                }

                var resultList = '';
                var elementList;

                searchMessage.html('');

                $.each(result.works, function( index, value ) {

                    var businessWorkId = value.tbDirEmpresaObraID;

                    $.each(value.business, function(index, value) {
                        elementList = $('<li></li>');
                        var elementLink = $('<a href="#" class="search-option" data-id="' + businessWorkId + '">' + value.EmpresaRazonSocial +'</a>');

                        elementLink.on('click', function(event) {
                            event.preventDefault();

                            var id = $(this).data('id');
                            var name = $(this).html();

                            inputSearch.val(name);
                            inputSearchHidden.val(id);

                            searchMessage.nextAll('li').remove();
                            searchMessage.html('');
                        });

                        elementList.html(elementLink);
                        searchMessage.after(elementList);
                    })
                });

            });

            request.fail(function(xhr, textStatus) {
                searchMessage.html((options.hasOwnProperty('noResults')) ? options.error : 'Ocurrio un error');
            });

        }).focusout(function() {
            searchMessage.html('');
            searchOptions.html('');
            setTimeout(function() {
                searchMessage.nextAll('li').remove();
                search.removeClass('open');
            }, 100);
        }).focus(function() {
            search.addClass('open');
        });
    };*/

    /*ConstructionWork.prototype.searchPersonWithAjax = function(element, options) {

        var search = $(element);
        var inputSearch = search.find('.search-field');
        var inputSearchHidden = search.find('.search-hidden');
        var searchMessage = search.find('.search-message');
        var searchOptions = search.find('.search-result');
        var optionSelected = search.find('.search-option');

        $(inputSearch).on('keyup', function() {

            var inputSearch = $(this);

            var request = $.ajax({
                url: (options.hasOwnProperty('url')) ? options.url : '',
                type: 'post',
                dataType: 'html',
                timeout: 90000,
                cache: false,
                data: {
                    q: inputSearch.val(),
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            });

            request.done(function(response) {

                var result = jQuery.parseJSON(response);

                searchMessage.nextAll('li').remove();

                if (result.works.length == 0 || result.works == null ) {
                    searchMessage.html((options.hasOwnProperty('noResults')) ? options.noResults : 'No hay coincidencias de tu busqueda');
                    searchOptions.html('');
                    return;
                }

                var resultList = '';
                var elementList;

                searchMessage.html('');

                $.each(result.works, function( index, value ) {

                    var personWorkId = value.tbDirPersonaObraID;

                    $.each(value.persons, function(index, value) {
                        elementList = $('<li></li>');
                        var elementLink = $('<a href="#" class="search-option" data-id="' + personWorkId + '">' + value.PersonaNombreDirecto +'</a>');

                        elementLink.on('click', function(event) {
                            event.preventDefault();

                            var id = $(this).data('id');
                            var name = $(this).html();

                            inputSearch.val(name);
                            inputSearchHidden.val(id);

                            searchMessage.nextAll('li').remove();
                            searchMessage.html('');
                        });

                        elementList.html(elementLink);
                        searchMessage.after(elementList);
                    })
                });

            });

            request.fail(function(xhr, textStatus) {
                searchMessage.html((options.hasOwnProperty('noResults')) ? options.error : 'Ocurrio un error');
            });

        }).focusout(function() {
            searchMessage.html('');
            searchOptions.html('');
            setTimeout(function() {
                searchMessage.nextAll('li').remove();
                search.removeClass('open');
            }, 200);
        }).focus(function() {
            search.addClass('open');
        });
    };*/

    ConstructionWork.prototype.searchBusinessWithAjax = function(element, options, callback) {

        var searchOptions = {
            type : 'POST',
            dataType : 'json',
            q :  '{{{q}}}',
            log : (options.hasOwnProperty('log')) ? options.log : false,
            canAdd: (options.hasOwnProperty('canAdd')) ? options.canAdd : true,
        }

        var locale = {}
        var rows = null;

        if (options.hasOwnProperty('noResults')) {
            locale = options.locale
        } else {

            locale = {
                emptyTitle: 'Seleccionar empresa',
                currentlySelected : 'Opción seleccionada',
                statusInitialized : 'Busqueda de empresa',
                searchPlaceholder : 'Buscar',
                statusNoResults : 'No hay resultados',
                statusSearching: 'Buscando...',
                errorText: 'No se puede mostrar la información'
            }
        }

        var searchClientOptions = {

            ajax: {
                url: options.url,
                type: searchOptions.type,
                dataType: searchOptions.dataType,
                data: {
                    q: searchOptions.q,
                    workId: (options.hasOwnProperty('workId')) ? options.workId : 0,
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            },

            locale: locale,
            log: searchOptions.log,

            preprocessData: function (data) {
                var i, l = data.businessAll.length, array = [];

                if (l) {

                    rows = data.businessAll;

                    for (i = 0; i < l; i++) {
                        for (j = 0; j < data.businessAll[i].business.length; j++) {

                            array.push({
                                text : data.businessAll[i].business[j].EmpresaAlias,
                                value: data.businessAll[i].tbDirEmpresaObraID,
                                class: (options.hasOwnProperty('optionListClass')) ? options.optionListClass : ''
                            });
                        }
                    }
                }

                if (array.length == 0) {
                    array.push({
                        text : 'No hay resultados',
                        value: '-1',
                        disabled: true,
                    });
                }

                if (searchOptions.canAdd) {
                    array.push({
                        text : 'Agregar nueva empresa',
                        value: '0',
                        class: (options.hasOwnProperty('optionClass')) ? options.optionClass : '',
                    });
                }

                return array;
            },

            preserveSelected: false,
        };

        $(element)
            .selectpicker()
            .filter('.with-ajax')
            .ajaxSelectPicker(searchClientOptions);


        $(document).on('click', (options.hasOwnProperty('optionListClass')) ? '.' + options.optionListClass : '' , function(event) {

            var data = {
                event: event,
                element: element,
                action: 'optionClicked',
                rows: rows
            };

            return callback(data);

        });

        $(document).on('click', (options.hasOwnProperty('optionClass')) ? '.' + options.optionClass : '', function(event) {

            var data = {
                event: event,
                element: element,
                action: 'newClicked',
                rows: rows
            };

            return callback(data);

        });

    };

    ConstructionWork.prototype.searchPersonWithAjax = function(element, options, callback) {

        var searchOptions = {
            type : 'POST',
            dataType : 'json',
            q :  '{{{q}}}',
            log : (options.hasOwnProperty('log')) ? options.log : false,
            canAdd: (options.hasOwnProperty('canAdd')) ? options.canAdd : true,
        }

        var locale = {}
        var rows = null;

        if (options.hasOwnProperty('noResults')) {
            locale = options.locale
        } else {

            locale = {
                emptyTitle: 'Seleccionar persona',
                currentlySelected : 'Opción seleccionada',
                statusInitialized : 'Busqueda de personas',
                searchPlaceholder : 'Buscar',
                statusNoResults : 'No hay resultados',
                statusSearching: 'Buscando...',
                errorText: 'No se puede mostrar la información'
            }
        }

        var searchClientOptions = {

            ajax: {
                url: options.url,
                type: searchOptions.type,
                dataType: searchOptions.dataType,
                data: {
                    q: searchOptions.q,
                    workId: (options.hasOwnProperty('workId')) ? options.workId : 0,
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            },

            locale: locale,
            log: searchOptions.log,

            preprocessData: function (data) {
                var i, l = data.personWorks.length, array = [];

                if (l) {

                    rows = data.personWorks;

                    for (i = 0; i < l; i++) {

                        for (j = 0; j < data.personWorks[i].persons_business.length; j++) {

                            if (data.personWorks[i].persons_business[j].person != null) {
                                array.push({
                                    text : data.personWorks[i].persons_business[j].person.PersonaNombreDirecto,
                                    value: data.personWorks[i].tbDirPersonaEmpresaObraID,
                                    class: (options.hasOwnProperty('optionListClass')) ? options.optionListClass : ''
                                });
                            }

                        }
                    }
                }

                if (array.length == 0) {
                    array.push({
                        text : 'No hay resultados',
                        value: '-1',
                        disabled: true,
                    });
                }

                if (searchOptions.canAdd) {

                    array.push({
                        text: 'Agregar nueva persona',
                        value: '0',
                        class: (options.hasOwnProperty('optionClass')) ? options.optionClass : '',
                    });

                }

                return array;
            },

            preserveSelected: false,
        };

        $(element)
            .selectpicker()
            .filter('.with-ajax')
            .ajaxSelectPicker(searchClientOptions);

        $(document).on('click', (options.hasOwnProperty('optionListClass')) ? '.' + options.optionListClass : '' , function(event) {

            var data = {
                event: event,
                element: element,
                action: 'optionClicked',
                rows: rows
            };

            return callback(data);

        });

        $(document).on('click', (options.hasOwnProperty('optionClass')) ? '.' + options.optionClass : '', function(event) {

            var data = {
                event: event,
                element: element,
                action: 'newClicked',
                rows: rows
            };

            return callback(data);

        });
    };

    ConstructionWork.prototype.searchContractWithAjax = function(element, options) {

        var rows;
        var search = $(element);
        var inputSearch = search.find('.search-field');
        var inputSearchHidden = search.find('.search-hidden');
        var searchMessage = search.find('.search-message');
        var searchOptions = search.find('.search-result');
        var optionSelected = search.find('.search-option');

        $(inputSearch).on('keydown', function() {

            var inputSearch = $(this);

            var request = $.ajax({
                url: (options.hasOwnProperty('url')) ? options.url : '',
                type: 'post',
                dataType: 'html',
                timeout: 90000,
                cache: false,
                data: {
                    q: inputSearch.val(),
                    workId: (options.hasOwnProperty('workId')) ? options.workId : 0,
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            });

            request.done(function(response) {

                var result = jQuery.parseJSON(response);

                searchMessage.nextAll('li').remove();

                if (result.contracts.length == 0 || result.contracts == null ) {
                    searchMessage.html((options.hasOwnProperty('noResults')) ? options.noResults : 'No hay coincidencias de tu busqueda');
                    searchOptions.html('');
                    return;
                }

                var resultList = '';
                var elementList;

                searchMessage.html('');

                $.each(result.contracts, function(index, value) {
                    elementList = $('<li></li>');
                    var elementLink = $('<a href="#" class="search-option" data-id="' + value.TbContratosID + '">' + value.ContratoAlias +'</a>');

                    elementLink.on('click', function(event) {
                        event.preventDefault();

                        var id = $(this).data('id');
                        var name = $(this).html();

                        inputSearch.val(name);
                        inputSearchHidden.val(id);

                        searchMessage.nextAll('li').remove();
                        searchMessage.html('');
                    });

                    elementList.html(elementLink);
                    searchMessage.after(elementList);
                })

            });

            request.fail(function(xhr, textStatus) {
                searchMessage.html((options.hasOwnProperty('noResults')) ? options.error : 'Ocurrio un error');
            });

        }).focusout(function() {
            searchMessage.html('');
            searchOptions.html('');

            if (inputSearchHidden.val() == '') {
                inputSearch.val('');
            }

            setTimeout(function() {
                searchMessage.nextAll('li').remove();
                search.removeClass('open');
            }, 200);

        }).focus(function() {
            search.addClass('open');
        });
    };

    ConstructionWork.prototype.searchAdvanceContractWithAjax = function(element, options, callback) {

        var searchOptions = {
            type : 'POST',
            dataType : 'json',
            q :  '{{{q}}}',
            log : (options.hasOwnProperty('log')) ? options.log : false,
            canAdd: (options.hasOwnProperty('canAdd')) ? options.canAdd : true,
        }

        var locale = {};
        var rows = [];

        if (options.hasOwnProperty('noResults')) {
            locale = options.locale
        } else {

            locale = {
                emptyTitle: 'Seleccionar contrato',
                currentlySelected : 'Opción seleccionada',
                statusInitialized : 'Busqueda de contrato',
                searchPlaceholder : 'Buscar',
                statusNoResults : 'No hay resultados',
                statusSearching: 'Buscando...',
                errorText: 'No se puede mostrar la información'
            }
        }

        var searchClientOptions = {

            ajax: {
                url: options.url,
                type: searchOptions.type,
                dataType: searchOptions.dataType,
                data: {
                    q: searchOptions.q,
                    workId: (options.hasOwnProperty('workId')) ? options.workId : 0,
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            },

            locale: locale,
            log: searchOptions.log,

            preprocessData: function (data) {
                var i, l = data.contracts.length, array = [];
                rows = data.contracts;

                if (l) {
                    for (i = 0; i < l; i++) {

                            array.push({
                                text : data.contracts[i].ContratoAlias,
                                value: data.contracts[i].TbContratoID,
                                class: (options.hasOwnProperty('optionListClass')) ? options.optionListClass : ''
                            });
                    }
                }

                if (array.length == 0) {
                    array.push({
                        text : 'No hay resultados',
                        value: '-1',
                        disabled: true,
                    });
                }

                if (searchOptions.canAdd) {

                    array.push({
                        text: 'Agregar nuevo contrato',
                        value: '0',
                        class: (options.hasOwnProperty('optionClass')) ? options.optionClass : '',
                    });

                }

                return array;
            },

            preserveSelected: false,
            preserveSelectedPosition: 'before'
        };

        $(element)
            .selectpicker()
            .filter('.with-ajax')
            .ajaxSelectPicker(searchClientOptions).on('hide.bs.select', function (e) {

            var data = {
                event: event,
                element: element,
                action: 'hide',
                rows: rows
            };

            return callback(data);
        });;


        $(document).on('click', (options.hasOwnProperty('optionListClass')) ? '.' + options.optionListClass : '' , function(event) {

            var data = {
                event: event,
                element: element,
                action: 'optionClicked',
                rows: rows
            };

            return callback(data);

        });

        $(document).on('click', (options.hasOwnProperty('optionClass')) ? '.' + options.optionClass : '', function(event) {

            var data = {
                event: event,
                element: element,
                action: 'newClicked',
                row: []
            };

            return callback(data);

        });
    };

    return ConstructionWork;
}());
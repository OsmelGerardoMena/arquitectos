var Customer = (function() {

    /**
     * Class constructor
     */
    function Customer() {}


    /**
     * Crear usuario mediante AJAX desde contrato
     *
     * @return void
     */
    Customer.prototype.saveWithAjax = function(form, callback) {

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

                    form.find('input').val('');
                    form.find('textarea').val('');
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
     * Busca una empresa mediante Ajax
     *
     * @important Require de las libreria jquery.min.js, bootstrap-select.min.js y ajax-bootstrap-select.min.js
     * @param string element el id o clase del elemento
     * @param string url
     * @param object options opciones para la busqueda
     * @return void
     */
    /**
     * Crear usuario mediante AJAX desde contrato
     *
     * @return void
     */
    /*Customer.prototype.searchWithAjax = function(element, options) {

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

                if (result.Customer.length == 0 || result.Customer == null ) {
                    searchMessage.html((options.hasOwnProperty('noResults')) ? options.noResults : 'No hay coincidencias de tu busqueda');
                    searchOptions.html('');
                    return;
                }

                var resultList = '';
                var elementList;

                searchMessage.html('');

                $.each(result.Customer, function( index, value ) {

                    elementList = $('<li></li>');
                    var elementLink = $('<a href="#" class="search-option" data-id="' + value.tbDirEmpresaID + '">' + value.EmpresaRazonSocial +'</a>');

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

    Customer.prototype.searchWithAjax = function(element, options, callback) {

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
                emptyTitle: 'Seleccionar cliente',
                currentlySelected : 'Opción seleccionada',
                statusInitialized : 'Busqueda de cliente',
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
                    q: searchOptions.q
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            },

            locale: locale,
            log: searchOptions.log,

            preprocessData: function (data) {
                var i, l = data.customers.length, array = [];

                if (l) {

                    rows = data.customers;

                    for (i = 0; i < l; i++) {

                        if (data.customers[i].business != null) {

                            array.push({
                                text: data.customers[i].business.EmpresaAlias,
                                value: data.customers[i].TbClientesID,
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
                        text : 'Agregar nuevo cliente',
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

    Customer.prototype.searchInMyBusinessWithAjax = function(element, options, callback) {

        var searchOptions = {
            type : 'POST',
            dataType : 'json',
            q :  '{{{q}}}',
            log : (options.hasOwnProperty('log')) ? options.log : false,
            canAdd: (options.hasOwnProperty('canAdd')) ? options.canAdd : true,
        }

        var locale = {}

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
                    q: searchOptions.q
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            },

            locale: locale,
            log: searchOptions.log,

            preprocessData: function (data) {
                var i, l = data.customers.length, array = [];

                if (l) {
                    for (i = 0; i < l; i++) {

                        if (data.customers[i].person != null) {

                            array.push({
                                text : data.customers[i].person.PersonaAlias,
                                value: data.customers[i].TbClientesID,
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
                action: 'optionClicked'
            };

            return callback(data);

        });

        $(document).on('click', (options.hasOwnProperty('optionClass')) ? '.' + options.optionClass : '', function(event) {

            var data = {
                event: event,
                element: element,
                action: 'newClicked'
            };

            return callback(data);

        });
    };

    return Customer;
}());
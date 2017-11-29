var Person = (function() {

	/**
	 * Class constructor
	 */
	function Person() {}

	/**
	 * Add Name By Last
	 *
	 * @return void
	 */
	Person.prototype.addNameByLast = function(inputName, inputLast, inputLast2, output) {

		var name = [];
		var output = $(output);

		name[0] = '';
		name[1] = '';
		name[2] = '';

		$(inputLast).on('keyup', function() {
			name[0] = $(this).val();
			output.val(name[0] + ' ' + name[1] + ', ' + name[2]);
		});

		$(inputLast2).on('keyup', function() {
			name[1] = $(this).val();
			output.val(name[0] + ' ' + name[1] + ', ' + name[2]);
		});

		$(inputName).on('keyup', function() {
			name[2] = $(this).val();
			output.val(name[0] + ' ' + name[1] + ', ' + name[2]);
		});
	};

	/**
	 * Add Direct Name
	 *
	 * @return void
	 */
	Person.prototype.addDirectName = function(inputName, inputLast, inputLast2, output) {

		var name = [];
		var output = $(output);

		name[0] = '';
		name[1] = '';
		name[2] = '';

		$(inputName).on('keyup', function() {
			name[0] = $(this).val();
			output.val(name[0] + ' ' + name[1] + ' ' + name[2]);
		});

		$(inputLast).on('keyup', function() {
			name[1] = $(this).val();
			output.val(name[0] + ' ' + name[1] + ' ' + name[2]);
		});

		$(inputLast2).on('keyup', function() {
			name[2] = $(this).val();
			output.val(name[0] + ' ' + name[1] + ' ' + name[2]);
		});
	};

	/**
	 * Add Full Name
	 *
	 * @return void
	 */
	Person.prototype.addFullName = function(prefix, inputName, inputLast, inputLast2, output) {

		var name = [];
		var output = $(output);

		name[0] = '';
		name[1] = '';
		name[2] = '';
		name[3] = '';

		$(prefix).on('keyup change', function() {
			name[0] = $(this).val();
			output.val(name[0] + ' ' + name[1] + ' ' + name[2] + ' ' + name[3]);
		});

		$(inputName).on('keyup', function() {
			name[1] = $(this).val();
			output.val(name[0] + ' ' + name[1] + ' ' + name[2] + ' ' + name[3]);
		});

		$(inputLast).on('keyup', function() {
			name[2] = $(this).val();
			output.val(name[0] + ' ' + name[1] + ' ' + name[2] + ' ' + name[3]);
		});

		$(inputLast2).on('keyup', function() {
			name[3] = $(this).val();
			output.val(name[0] + ' ' + name[1] + ' ' + name[2] + ' ' + name[3]);
		});
	};

	/**
	 * Crear usuario mediante AJAX
	 *
	 * @return void
	 */
	Person.prototype.saveWithAjax = function(form, callback) {

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
     * Busca una persona mediante Ajax
     *
     * @important Require de las libreria jquery.min.js, bootstrap-select.min.js y ajax-bootstrap-select.min.js
     * @param string element el id o clase del elemento
     * @param string url
     * @param object options opciones para la busqueda
     * @return void
     */
    /*Person.prototype.searchWithAjax = function(element, options) {

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

                if (result.persons.length == 0 || result.persons == null ) {
                    searchMessage.html((options.hasOwnProperty('noResults')) ? options.noResults : 'No hay coincidencias de tu busqueda');
                    searchOptions.html('');
                    return;
                }

                var resultList = '';
                var elementList;

                searchMessage.html('');

                $.each(result.persons, function( index, value ) {

                    elementList = $('<li></li>');
                    var elementLink = $('<a href="#" class="search-option" data-id="' + value.tbDirPersonasID + '">' + value.PersonaNombreDirecto +'</a>');

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

    Person.prototype.searchWithAjax = function(element, options, callback) {

        var searchOptions = {
            type : 'POST',
            dataType : 'json',
            q :  '{{{q}}}',
            log : (options.hasOwnProperty('log')) ? options.log : false,
            canAdd: (options.hasOwnProperty('canAdd')) ? options.canAdd : true
        }

        var locale = {}

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
                    q: searchOptions.q
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            },

            locale: locale,
            log: searchOptions.log,

            preprocessData: function (data) {
                var i, l = data.persons.length, array = [];

                if (l) {
                    for (i = 0; i < l; i++) {

                        array.push({
                            text : data.persons[i].PersonaNombreDirecto,
                            value: data.persons[i].tbDirPersonaID,
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
                        text: 'Agregar nueva persona',
                        value: '0',
                        class: (options.hasOwnProperty('optionClass')) ? options.optionClass : '',
                    });
                }

                return array;
            },

            preserveSelected: true,
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

    Person.prototype.searchWithAjax = function(element, options, callback) {

        var searchOptions = {
            type : 'POST',
            dataType : 'json',
            q :  '{{{q}}}',
            log : (options.hasOwnProperty('log')) ? options.log : false,
            canAdd: (options.hasOwnProperty('canAdd')) ? options.canAdd : true
        }

        var locale = {}

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
                    q: searchOptions.q
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            },

            locale: locale,
            log: searchOptions.log,

            preprocessData: function (data) {
                var i, l = data.persons.length, array = [];

                if (l) {
                    for (i = 0; i < l; i++) {

                        array.push({
                            text : data.persons[i].PersonaNombreDirecto,
                            value: data.persons[i].tbDirPersonaID,
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
                        text: 'Agregar nueva persona',
                        value: '0',
                        class: (options.hasOwnProperty('optionClass')) ? options.optionClass : '',
                    });
                }

                return array;
            },

            preserveSelected: true,
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

    Person.prototype.searchInMyBusinessWithAjax = function(element, options, callback) {

        var searchOptions = {
            type : 'POST',
            dataType : 'json',
            q :  '{{{q}}}',
            log : (options.hasOwnProperty('log')) ? options.log : false,
            canAdd: (options.hasOwnProperty('canAdd')) ? options.canAdd : true
        }

        var locale = {}

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
                    q: searchOptions.q
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            },

            locale: locale,
            log: searchOptions.log,

            preprocessData: function (data) {
                var i, l = data.persons.length, array = [];

                console.log(data.persons);

                if (l) {
                    for (i = 0; i < l; i++) {

                        if (data.persons[i].person != null) {

                            var businessName = ""

                            if (data.persons[i].business != null) {
                                businessName = " / " + data.persons[i].business.EmpresaAlias
                            }

                            array.push({
                                text: data.persons[i].person.PersonaAlias + businessName,
                                value: data.persons[i].tbDirPersonaMiEmpresaID,
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
                        text: 'Agregar nueva persona',
                        value: '0',
                        class: (options.hasOwnProperty('optionClass')) ? options.optionClass : '',
                    });
                }

                return array;
            },

            preserveSelected: true,
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

    Person.prototype.searchInBusinessWithAjax = function(element, options, callback) {

        var searchOptions = {
            type : 'POST',
            dataType : 'json',
            q :  '{{{q}}}',
            log : (options.hasOwnProperty('log')) ? options.log : false,
            canAdd: (options.hasOwnProperty('canAdd')) ? options.canAdd : true
        }

        var locale = {}

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
                    q: searchOptions.q
                },
                headers: {
                    'X-CSRF-TOKEN': (options.hasOwnProperty('token')) ? options.token : '',
                }
            },

            locale: locale,
            log: searchOptions.log,

            preprocessData: function (data) {
                var i, l = data.persons.length, array = [];

                console.log(data.persons);

                if (l) {
                    for (i = 0; i < l; i++) {

                            array.push({
                                text: data.persons[i].PersonaAlias,
                                value: data.persons[i].person_business.tbDirPersonaEmpresaID,
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
                        text: 'Agregar nueva persona',
                        value: '0',
                        class: (options.hasOwnProperty('optionClass')) ? options.optionClass : '',
                    });
                }

                return array;
            },

            preserveSelected: true,
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

	return Person;
}());
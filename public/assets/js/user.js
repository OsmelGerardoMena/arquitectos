var User = (function() {

	/**
	 * Class constructor
	 */
	function User() {}

	/**
	 * Toggle Button
	 * Action for toggle button
	 *
	 * @return void
	 */
	User.prototype.toggleButton = function(element, callback) {

		var button = document.querySelector(element);

		button.addEventListener('click', function(event) {
			callback(event);
		});
	};

	User.prototype.searchWithAjax = function(element, options, callback) {

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
	            emptyTitle: 'Seleccionar usuario',
	            currentlySelected : 'Opción seleccionada',
	            statusInitialized : 'Busqueda de usuarios',
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
	            var i, l = data.users.length, array = [];

	            if (l) {	
	                for (i = 0; i < l; i++) {

	                    array.push({
	                        text : data.users[i].CTOUsuarioNombre,
	                        value: data.users[i].TbCTOUsuarioID,
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



	return User;
}());
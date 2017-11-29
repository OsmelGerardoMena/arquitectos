var Contract = (function() {

    /**
     * Class constructor
     */
    function Contract() {}


    /**
     * Crear usuario mediante AJAX desde contrato
     *
     * @return void
     */
    Contract.prototype.saveWithAjax = function(form, callback) {

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
    Contract.prototype.searchWithAjax = function(element, options) {

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

                if (result.business.length == 0 || result.business == null ) {
                    searchMessage.html((options.hasOwnProperty('noResults')) ? options.noResults : 'No hay coincidencias de tu busqueda');
                    searchOptions.html('');
                    return;
                }

                var resultList = '';
                var elementList;

                searchMessage.html('');

                $.each(result.business, function( index, value ) {

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
    };

    return Contract;
}());
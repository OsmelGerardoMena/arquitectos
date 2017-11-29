var App = (function() {

    /**
     * Class constructor
     */
    function App() {}

    /**
     * Animate Submit
     * Fake loading before submit a form
     *
     * @param string form - form id
     * @param string button - button id
     * @return void
     */
    App.prototype.animateSubmit = function(form, button) {


        var form = $('#' + form);//document.getElementById(form);
        var button = '#' + button;//document.getElementById(button);

        if (form == null|| button == null)
            return;

        /*button.addEventListener('click', function(event) {
            event.preventDefault();

            var loadingHtml = '<span class="fa-stack fa-lg">';
            loadingHtml += '<i class="fa fa-circle fa-stack-2x"></i>';
            loadingHtml += '<i class="fa fa-spinner fa-spin  fa-stack-1x fa-inverse"></i>';
            loadingHtml += '</span>';

            button.innerHTML = loadingHtml;

            setTimeout(function() {
                form.submit();
            }, 1000);
        });*/

        $(document).on('click', button, function(event) {
            event.preventDefault();

            var button = $(this);

            var loadingHtml = '<span class="fa-stack fa-lg">';
            loadingHtml += '<i class="fa fa-circle fa-stack-2x"></i>';
            loadingHtml += '<i class="fa fa-spinner fa-spin  fa-stack-1x fa-inverse"></i>';
            loadingHtml += '</span>';

            button.html(loadingHtml);

            setTimeout(function() {
                form.submit();
            }, 1000);
        });
    };

    /**
     * Delete message confirmation
     * Add an alert before delete a data
     *
     * @param string form - form id
     * @param string button - button id
     * @return void
     */
    App.prototype.deleteConfirm = function(box, confirmButon, cancelButton) {

        var confirmButton = $('#' + confirmButon);
        var cancelButton = $('#' + cancelButton);
        var alertBox = $('#' + box);

        if (confirmButon.length > 0 && cancelButton.length > 0) {

            confirmButton.on('click', function (event) {
                event.preventDefault();
                alertBox.show();
            });

            cancelButton.on('click', function (event) {
                event.preventDefault();
                alertBox.hide();
            });

        }
    };

    /**
     * Login Help Button
     * Show a message alert to user
     *
     * @return void
     */
    App.prototype.loginHelpButton = function(message) {

        var button = document.getElementById('loginHelpButton');

        button.addEventListener('click', function(event) {
            event.preventDefault();
            alert(message);
        });
    };


    /**
     * Login Help Button
     * Show a message alert to user
     *
     * @return void
     */
    App.prototype.scrollNavActions = function(message) {

        var originalTop = $('.navbar-actions').offset().top;

        $(window).scroll( function() {
            if ( ($(window).scrollTop() - 30) > originalTop) {
                $('.navbar-actions').addClass('navbar-fixed-top');
                $('.navbar-actions').removeClass('navbar-static-top');
            }

            if ($(window).scrollTop() < originalTop) {
                $('.navbar-actions').removeClass('navbar-fixed-top');
                $('.navbar-actions').addClass('navbar-static-top');
            }
        });
    };

    /**
     * Tootlip
     * Activa el tooltip de bootstrap mediante el selector .is-tooltip
     *
     * @important Require de las libreria jquery.min.js y boostrap.min.js
     * @return void
     */
    App.prototype.tooltip = function(message) {
        $(document).tooltip({selector: '.is-tooltip'});
    };

    /**
     * Match Height
     * Forza a dos o más elementos html a tener la misma altura
     *
     * @important Require de las libreria jquery.min.js y jquery.matchHeight.min.js
     * @return void
     */
    App.prototype.matchHeight = function(element) {
        $(element).matchHeight({byRow: true});
    };

    /**
     * Date Time Picker Field
     * Convierte un campo en formato de fecha y mustra un calendario flotante
     *
     * @important Require de las libreria jquery.min.js, bootstrap-datetimepicker.min.js y moment.js
     * @return void
     */
    App.prototype.dateTimePickerField = function(element, ignoreReadonly = true) {

        var el = $(element);

        $('.date-field').datetimepicker({
            format: 'dddd DD [de] MMMM [del] YYYY',
            locale: 'es',
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: 'fa fa-arrow-left',
                next: 'fa fa-arrow-right',
            },
            useCurrent: false,
            ignoreReadonly: ignoreReadonly,
        }).change(function(event) {
            console.log("Evento change");
        });

        $(".date-field").on('dp.change', function(event) {

            $(this).find('input[type="hidden"]').val(moment(event.date).format('YYYY-MM-DD'));
        });

        if (el.length > 0) {

            var countDay = 0;

            el.find('.btn-today').on('click', function() {

                var dateHuman = moment().locale('es').format('dddd DD [de] MMMM [del] YYYY');
                var date = moment().format('YYYY-MM-DD');
                countDay = 0;

                $(this).parent().parent().find('.date-formated').focus();
                $(this).parent().parent().find('.date-formated').val(dateHuman);
                $(this).parent().parent().find('input[type="hidden"]').val(date);
                $(this).parent().parent().find('.date-formated').blur();
            });

            el.find('.btn-date-minus').on('click', function() {

                countDay--;

                var dateHuman = moment().locale('es').add(countDay, 'days').format('dddd DD [de] MMMM [del] YYYY');
                var date = moment().add(countDay, 'days').format('YYYY-MM-DD');

                $(el).find('.date-formated').focus();
                $(el).find('.date-formated').val(dateHuman);
                $(el).find('input[type="hidden"]').val(date);
                $(el).find('.date-formated').blur();
            });

            el.find('.btn-date-plus').on('click', function() {

                countDay++;

                var dateHuman = moment().locale('es').add(countDay, 'days').format('dddd DD [de] MMMM [del] YYYY');
                var date = moment().add(countDay, 'days').format('YYYY-MM-DD');

                $(el).find('.date-formated').focus();
                $(el).find('.date-formated').val(dateHuman);
                $(el).find('input[type="hidden"]').val(date);
                $(el).find('.date-formated').blur();
            });

        }
    };

    /**
     * formErrors
     * Marca los campos que tienen errores en el formulario enviado
     *
     * @param string element
     * @important Require de la libreria jquery.min.js
     * @return void
     */
    App.prototype.formErrors = function(element) {

        var form = $(element);

        if (form.length > 0) {

            var inputErrors = $('input[name="_errors"]');

            if (inputErrors.length > 0) {
                var errors = JSON.parse(inputErrors.val());

                $.each(errors, function(key, error) {

                    var input = form.find('input[name="' + key + '"]');
                    var select = form.find('select[name="' + key + '"]');
                    var textarea = form.find('textarea[name="' + key + '"]');
                    var style = {
                        element: 'border-color',
                        color: '#ef5350'
                    }

                    if (input.length > 0) {
                        input.css(style.element, style.color);
                    }

                    if (select.length > 0) {
                        select.css(style.element, style.color);
                    }

                    if (select.hasClass('selectpicker') > 0) {
                        $('#' + key).selectpicker()
                            .selectpicker('setStyle', 'btn-danger--outline')
                            .selectpicker('refresh');
                    }

                    if (textarea.length > 0) {
                        textarea.css(style.element, style.color);
                    }
                });
            }
        }
    };

    /**
     * preventClose
     * Previene el cierre, salida o actualización de la ventana
     * si detecta que uno o más campos del formulario tienen un valor
     *
     * @param string element
     * @important Require de la libreria jquery.min.js
     * @return void
     */
    App.prototype.preventClose = function(element) {

        var formHasChanged = false;
        var submitted = false;

        $("form").submit(function() {
            submitted = true;
        });

        // Detecta cuando el usuario ha ingesado un valor en cualquier campo del formulario.
        $(document).on('change', 'form input, form select, form textarea', function () {
            formHasChanged = true;
        });

        // Detecte en la carga si al menos un campo del formulario tiene un dato.
        // A difrencia del evento change este se usa cuando el servidor regresa error
        $('input[type!="hidden"][type!="checkbox"][type!="file"], select, textarea').each(
            function(){

                var val = $(this);

                if (val.length > 0 && val.hasOwnProperty('val')) {
                    if (val.val().trim() !== ''){
                        formHasChanged = true;
                    }
                }


            }
        );

        window.onbeforeunload = function (e) {

            if (formHasChanged && !submitted) {
                var message = "No has guardado los cambios.", e = e || window.event;
                if (e) {
                    e.returnValue = message;
                }
                return message;
            }
        }
    };

    /**
     * preventClose
     * Previene el cierre, salida o actualización de la ventana
     * si detecta que uno o más campos del formulario tienen un valor
     *
     * @param string element
     * @important Require de la libreria jquery.min.js
     * @return void
     */
    App.prototype.initItemsList = function(options) {

        options = options || {};

        var windowHeight = $(window).height();
        var itemsList = $('#itemsList');
        var itemsRelation = $('.items-relation');

        if (itemsRelation.length > 0 ) {

            itemsRelation.height(windowHeight - (options.hasOwnProperty('fitRelationHeight') ? options.fitRelationHeight : 245));
            $(itemsRelation).on('scroll mouseenter mouseover', function () {
                $('body').css('height', windowHeight).css('overflow', 'hidden');
            });

            $(itemsRelation).on('mouseleave', function () {
                $('body').css('height', '100%').css('overflow', 'auto');
            });
        }

        if (itemsList.length > 0 ) {

            var itemsListPaddingBottom = 210;
            itemsList.height(windowHeight - (options.hasOwnProperty('fitListHeight') ? options.fitListHeight : 215));

            $(itemsList).css('padding-bottom', itemsListPaddingBottom);

            $(itemsList).on('scroll mouseenter mouseover', function () {
                $('body').css('height', windowHeight).css('overflow', 'hidden');
            });

            $(itemsList).on('mouseleave', function () {
                $('body').css('height', '100%').css('overflow', 'auto');
            });

            if (window.location.hash) {

                itemsList.animate({
                    scrollTop: $(window.location.hash).position().top - 200
                }, 50);

            }

            if ($('input[name="_recordId"]').length > 0) {

                if ($('input[name="_recordId"]').val().length > 0) {

                    itemsList.animate({
                        scrollTop: $('#item' + $('input[name="_recordId"]').val()).position().top - 200
                    }, 50);
                }
            }

            if (getParameterByName('_item')) {

                itemsList.animate({
                    scrollTop: $('#' + getParameterByName('_item')).position().top - 200
                }, 50);

            }
        }
    };

    /**
     * preventClose
     * Previene el cierre, salida o actualización de la ventana
     * si detecta que uno o más campos del formulario tienen un valor
     *
     * @param string element
     * @important Require de la libreria jquery.min.js
     * @return void
     */
    App.prototype.limitInput = function(el, limit = 0, showCounter = true) {

        var element = $(el);

        if (element.length > 0 ) {

            if (showCounter) {

                var formCounter = $(element).next('.form-count');
                ouputCounter = formCounter.find('.form-counter');

                ouputCounter.text(element.val().length);

                element.on('keyup keydown', function(e) {

                    var elementValue = $(this).val();

                    if (elementValue.length >= limit) {
                        ouputCounter.text(limit);
                        if(e.keyCode != 46 && e.keyCode != 8 ) return false;
                    } else if (elementValue.length === 0) {
                        ouputCounter.text(0);
                    } else {
                        ouputCounter.text(elementValue.length);
                    }

                });

            }

        }
    };

    /**
     * preventClose
     * Previene el cierre, salida o actualización de la ventana
     * si detecta que uno o más campos del formulario tienen un valor
     *
     * @param string element
     * @important Require de la libreria jquery.min.js
     * @return void
     */
    App.prototype.commentModal = function() {

        var modal = $('#showCommentModal');

        var modalInfo = modal.find('.modalContent');
        var modalForm = modal.find('.modalForm');
        var modalSaveButton = modal.find('.modalSaveButton');
        var modalUpdateButton = modal.find('.modalUpdateButton');
        var modalDeleteButton = modal.find('.modalDeleteButton');

        if (modal.length > 0 ) {

            modal.on('show.bs.modal', function(event) {

                var button = $(event.relatedTarget);
                var author = button.find('#commentAuthor').html();
                var date = button.find('#commentDate').html();
                var comment = button.find('#commentDescription').html();
                var authorId = button.data('author');
                var user = button.data('user');
                var commentId = button.data('id');

                console.log('Author: ' + authorId + " User: " + user);

                modalInfo.find('#modalCommentAuthor').html(author);
                modalInfo.find('#modalCommentDate').html(date);
                modalInfo.find('#modalCommentDescription').html(comment);

                modalForm.find('input[name="cto34_author"]').val(author);
                modalForm.find('input[name="cto34_date"]').val(date);
                modalForm.find('textarea[name="cto34_comment"]').val(comment);
                modalForm.find('input[name="cto34_id"]').val(commentId);

                if (authorId != user) {

                    modal.find('.user-own').addClass('disabled');
                    modalSaveButton.prop('disabled', true);
                    modalDeleteButton.prop('disabled', true);

                } else {

                    modal.find('.user-own').removeClass('disabled');
                    modalSaveButton.prop('disabled', false);
                    modalDeleteButton.prop('disabled', false);
                }

                if (authorId == user) {

                    console.log('Is the correct user');

                    modalUpdateButton.on('click', function (event) {
                        event.preventDefault();

                        var button = $(this);


                        if (!button.hasClass('_editing')) {

                            button.html('<span class="fa fa-ban fa-fw"></span>');
                            button.addClass('_editing');
                            modalSaveButton.removeClass('disabled');
                            modalInfo.addClass('hidden');
                            modalForm.removeClass('hidden');

                        } else {

                            var styleName = 'border-color';
                            var styleValue = '#ddd';

                            button.html('<span class="fa fa-pencil fa-fw"></span>');
                            modalSaveButton.addClass('disabled');
                            modalInfo.removeClass('hidden');
                            modalForm.addClass('hidden');
                            button.removeClass('_editing');
                            modalForm.find('form').find('input, select, textarea').css(styleName, styleValue);
                        }

                    });

                } else {
                    modalUpdateButton.off('click');
                }

            });

            modal.on('hide.bs.modal', function(event) {

                var styleName = 'border-color';
                var styleValue = '#ddd';

                modalUpdateButton.html('<span class="fa fa-pencil fa-fw"></span>');
                modalSaveButton.addClass('disabled');
                modalInfo.removeClass('hidden');
                modalForm.addClass('hidden');
                modalUpdateButton.removeClass('_editing');
                modalForm.find('form').find('input, select, textarea').css(styleName, styleValue);
                modalUpdateButton.off('click');
            });

        }

        modalSaveButton.on('click', function(event) {

            if (!$(this).hasClass('disabled')) {

                $(this).html('<span class="fa fa-spinner fa-spin fa-fw"></span>')
                    .prop('disabled', true);

                var form = modalForm.find('.saveForm');
                form.submit();
            }
        });

        modalDeleteButton.on('click', function(event) {

            if (!$(this).hasClass('disabled')) {

                $(this).html('<span class="fa fa-spinner fa-spin fa-fw"></span>')
                    .prop('disabled', true);

                var confirmAction = confirm("Confirmar elimimar éste registro");

                if (confirmAction) {

                    var form = modalForm.find('.deleteForm');
                    form.submit();

                } else {

                    $(this).html('<span class="fa fa-trash fa-fw"></span>')
                        .prop('disabled', false);

                }
            }


        });

        /*if (modalSave.length > 0 ) {

            modalSave.find('.saveButton').on('click', function(event) {

                var form = modalSave.find('form');
                var button = $(this);
                var defaultHtml = button.html();

                if (button.hasClass('disabled')) {
                    return false;
                }

                var request = $.ajax({
                    url: form.prop('action'),
                    type: form.prop('method'),
                    dataType: 'html',
                    timeout: 90000,
                    cache: false,
                    data: form.serialize(),
                    beforeSend: function() {

                        button.addClass('disabled');
                        button.html('<span class="fa fa-spinner fa-spin fa-fw"></span>');
                    }
                });

                request.done(function(response) {

                    var result = jQuery.parseJSON(response);

                    button.removeClass('disabled');
                    button.html(defaultHtml);

                    if (result.status === false) {

                        $.each(result.errors.all, function(key, error) {

                            var input = form.find('input[name="' + key + '"]');
                            var select = form.find('select[name="' + key + '"]');
                            var textarea = form.find('textarea[name="' + key + '"]');
                            var styleName = 'border-color';
                            var styleValue = '#ef5350';

                            if (input.length > 0) {
                                input.css(styleName, styleValue);
                            }

                            if (select.length > 0) {
                                select.css(styleName, styleValue);
                            }

                            if (textarea.length > 0) {
                                textarea.css(styleName, styleValue);
                            }
                        });

                        alert(result.message);


                    } else {

                        button.html(defaultHtml);
                        alert('Registro guardado correctamente');
                        window.location.href = result.data.base;
                    }
                });

                request.fail(function(xhr, textStatus) {

                    button.html(defaultHtml);
                    button.removeClass('disabled');
                    alert('No se puede guardar.');

                });

            });

        }*/

    };

    App.prototype.filterModal = function() {

        var modalFilter = $('#modalFilter');

        if (modalFilter.length > 0) {

            $(modalFilter).on('show.bs.modal', function() {

                var search = $('#search');

                if (search.length > 0) {

                    var searchFilter = $('#searchFilter');
                    var filterSearchInput = $('#searchInputFilter');

                    if (search.val().length === 0) {
                        searchFilter.removeClass('visible').addClass('hidden');
                    } else {
                        searchFilter.removeClass('hidden').addClass('visible');
                        filterSearchInput.val(search.val());
                    }
                }
            });
        }
    }

    App.prototype.imgModal = function() {

        var imgModal = $('#showImageModal');

        if (imgModal.length > 0) {

            $(imgModal).on('show.bs.modal', function(event) {

                var button = $(event.relatedTarget);
                var modal = $(this);

                modal.find('img').attr('src', button.data('image'));

            });
        }
    }

    App.prototype.highlightSearch = function() {

        var search = $('#search');

        if (search.length > 0) {

            if (search.val().length !== 0) {

                new HR(".help-block", {
                    highlight: search.val().replace("*", ''),
                    backgroundColor: '#e1f5fe'
                }).hr();
            }
        }
    }

    App.prototype.closeRecord = function(options) {

        var close = $('input[name="cto34_close"]');
        var modalClose = $('#modalCloseRecord');
        var modalOpen = $('#modalOpenRecord');

        if (close.length > 0 && modalOpen.length > 0 && modalClose.length > 0) {

            var url = (options.hasOwnProperty('url')) ? options.url : '';
            var defaultStatus = (close.is(':checked')) ? true : false;
            var recordId = $('input[name="_recordId"]').val();

            close.on('click', function() {

                var input = $(this);
                var workId = input.data('work');

                if (defaultStatus) {

                    modalOpen.modal('show');
                    modalOpen.find('input[name="cto34_work"]').val(workId);
                    modalOpen.find('input[name="cto34_id"]').val(recordId);

                    input.prop('checked', true);
                } else {

                    modalClose.modal('show');
                    modalClose.find('input[name="cto34_work"]').val(workId);
                    modalClose.find('input[name="cto34_id"]').val(recordId);

                    input.prop('checked', false);
                }
            });

            $('#closeRecordButton').on('click', function(event) {

                event.preventDefault();

                var workId = modalClose.find('input[name="cto34_work"]').val();
                var token = modalClose.find('input[name="_token"]').val();
                var method = modalClose.find('input[name="_method"]').val();
                var table = (options.hasOwnProperty('table')) ? options.table : '';
                var tableId = (options.hasOwnProperty('tableId')) ? options.tableId : '';
                var button = $(this);
                var defaultValue = button.html();

                button.prop('disabled', true);
                button.html('Cerrando...');

                var request = $.post(url,
                    {
                        id: recordId,
                        work: workId,
                        table: table,
                        tableId: tableId,
                        status: 1,
                        _token: token,
                        _method: method
                    }
                );

                request.done(function(response) {

                    if (response.status) {

                        alert('Cerrado correctamente.');
                        location.reload();

                    } else {

                        alert(response.message);
                        button.prop('disabled', false);
                        button.html(defaultValue);

                    }
                });
                request.fail(function(error) {
                    alert(error);
                    button.prop('disabled', false);
                    button.html(defaultValue);
                });
            })

            $('#openRecordButton').on('click', function(event) {

                event.preventDefault();

                var workId = modalOpen.find('input[name="cto34_work"]').val();
                var token = modalOpen.find('input[name="_token"]').val();
                var method = modalOpen.find('input[name="_method"]').val();
                var table = (options.hasOwnProperty('table')) ? options.table : '';
                var tableId = (options.hasOwnProperty('tableId')) ? options.tableId : '';
                var button = $(this);
                var defaultValue = button.html();

                button.prop('disabled', true);
                button.html('Abriendo...');

                var request = $.post(url,
                    {
                        id: recordId,
                        work: workId,
                        table: table,
                        tableId: tableId,
                        status: 0,
                        _token: token,
                        _method: method
                    }
                );

                request.done(function(response) {

                    console.log(response);

                    if (response.status) {

                        alert('Abierto correctamente.');
                        location.reload();

                    } else {

                        alert(response.message);
                        button.prop('disabled', false);
                        button.html(defaultValue);

                    }
                });
                request.fail(function(error) {
                    alert(error);
                    button.prop('disabled', false);
                    button.html(defaultValue);
                });
            })
        }
    }

    App.prototype.onPageTab = function() {

        var navOwn = $('.nav-own');
        var navRelation = $('.nav-relation');

        if (getParameterByName('_tab')) {

            var tab = $('a[href="#' + getParameterByName('_tab') +'"]');
            var type = $(tab).data('type');
            var target = $(tab).data('element');

            if (tab.length > 0) {
                tab.tab('show');

                if (type === 'relation') {
                    navOwn.addClass('hidden');
                    navRelation.removeClass('hidden');
                    $('#navRelationSave').attr('data-target', target);
                } else {

                    navOwn.removeClass('hidden');
                    navRelation.addClass('hidden');
                }
            }
        }

        $('a[data-toggle="tab"]').on('show.bs.tab', function (event) {

            var currentTab = event.target
            var type = $(currentTab).data('type');
            var target = $(currentTab).data('element');
            var hash = $(currentTab).attr('href');
            var itemsList = $('#itemsList');
            var navActions = $('.nav-actions');
            var hashtagInput = $('input[name="_hashtag"]');
            window.location.hash = hash;

            if (itemsList.length > 0 ) {

                itemsList.children('a').each(function () {

                    var link = $(this);
                    var hasHash = link.attr('href').split('#');

                    if (hasHash.length > 0) {
                        link.attr('href', hasHash[0] + hash);
                    } else {
                        link.attr('href', link.attr('href') + hash);
                    }


                });

                navActions.children('li').each(function () {

                    var link = $(this).find('a');

                    if (!link.hasClass('disabled')) {
                        var hasHash = link.attr('href').split('#');

                        if (hasHash.length > 0) {
                            link.attr('href', hasHash[0] + hash);
                        } else {
                            link.attr('href', link.attr('href') + hash);
                        }
                    }

                });

            }


            if (hashtagInput.length > 0) {

                hashtagInput.val(hash);

            }

            if (type === 'relation') {
                navOwn.addClass('hidden');
                navRelation.removeClass('hidden');
                $('#navRelationSave').attr('data-target', target);
            } else {

                navOwn.removeClass('hidden');
                navRelation.addClass('hidden');
            }

        });

        if (window.location.hash) {

            var initTab = window.location.hash;

            if ($(initTab).length > 0) {
                $('a[href="' + initTab +'"]').tab('show');
            }
        }

    }

    App.prototype.relationModal = function(label, options) {

        var modalInfo = $(options.hasOwnProperty('info') ? options.info.hasOwnProperty('element') ? options.info.element : '' : '');
        var modalSave = $(options.hasOwnProperty('save') ? options.save.hasOwnProperty('element') ? options.save.element : '' : '');

        if (modalInfo.length > 0 ) {

            modalInfo.on('show.bs.modal', function(event) {

                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                var modalNav = modal.find('.modalNavAction');
                var modalSaveButton = modal.find('.modalSaveButton');
                var modalUpdateButton = modal.find('.modalUpdateButton');
                var modalLoading = modal.find('.modalLoading');
                var modalContent = modal.find('.modalContent');
                var modalForm = modal.find('.modalForm');
                var modalAlert = modal.find('.modalAlert');

                if (!options.info.hasOwnProperty('url')) {
                    console.log('Agregar la opción url');
                } else {

                    var getRequest = $.get(options.info.url, { id: id });

                    getRequest.done(function(response) {

                        modalLoading.addClass('hidden');

                        modalSaveButton.on('click', function(event) {
                            event.preventDefault();

                            var button = $(this);
                            var defaultHtml = button.html();

                            if (button.hasClass('disabled')) {
                                return false;
                            }

                            var form = modal.find('.modalForm form');

                            var request = $.ajax({
                                url: form.prop('action'),
                                type: form.prop('method'),
                                dataType: 'html',
                                timeout: 90000,
                                cache: false,
                                data: form.serialize(),
                                beforeSend: function() {

                                    button.addClass('disabled');
                                    button.html('<span class="fa fa-spinner fa-spin fa-fw"></span>');
                                }
                            });

                            request.done(function(response) {

                                var result = jQuery.parseJSON(response);

                                button.removeClass('disabled');
                                button.html(defaultHtml);

                                if (result.status === false) {

                                    $.each(result.errors.all, function(key, error) {

                                        var input = form.find('input[name="' + key + '"]');
                                        var select = form.find('select[name="' + key + '"]');
                                        var textarea = form.find('textarea[name="' + key + '"]');

                                        if (input.length > 0) {
                                            input.css('border-color', '#ef5350');
                                        }

                                        if (select.length > 0) {
                                            select.css('border-color', '#ef5350');
                                        }

                                        if (textarea.length > 0) {
                                            textarea.css('border-color', '#ef5350');
                                        }
                                    });

                                    alert(result.message);


                                } else {

                                    button.html(defaultHtml);
                                    alert('Registro actualizado correctamente');
                                    window.location.href = result.data.base;
                                }
                            });

                            request.fail(function(xhr, textStatus) {

                                button.html(defaultHtml);
                                button.prop('disabled', false);
                                alert('No se puede actualizar.');

                            });
                        });

                        modalUpdateButton.on('click', function(event) {
                            event.preventDefault();

                            var button = $(this);

                            if (!button.hasClass('_editing')) {

                                button.html('<span class="fa fa-ban fa-fw"></span>');
                                button.addClass('_editing');
                                modalSaveButton.removeClass('disabled');
                                modalContent.addClass('hidden');
                                modalForm.removeClass('hidden');

                            } else {

                                var styleName = 'border-color';
                                var styleValue = '#ddd';

                                button.html('<span class="fa fa-pencil fa-fw"></span>');
                                modalSaveButton.addClass('disabled');
                                modalContent.removeClass('hidden');
                                modalForm.addClass('hidden');
                                button.removeClass('_editing');
                                modalForm.find('form').find('input, select, textarea').css(styleName, styleValue);
                                //modalForm.find('form')[0].reset();
                            }

                        });

                        if (response != null) {

                            modalContent.removeClass('hidden');
                            modalNav.removeClass('hidden');
                            modalUpdateButton.removeClass('disabled');

                            $.event.trigger({
                                type:    "info:request.rm." + label,
                                response: response,
                                modal: modalInfo
                            });

                        } else {

                            modalAlert.removeClass('hidden');
                            modalAlert.children('div').html('No se puede obtener los datos.');

                            $.event.trigger({
                                type:    "info:fail.rm." + label,
                                response: null,
                                modal: modalInfo
                            });
                        }

                    });

                    getRequest.fail(function(error) {

                        modalLoading.addClass('hidden');
                        modalAlert.removeClass('hidden');
                        modalAlert.children('div').html('No se puede obtener los datos.');

                    });
                }

            });

            $(modalInfo).on('hide.bs.modal', function(event) {

                var modal = $(this);
                var modalNav = modal.find('.modalNavAction');
                var modalSaveButton = modal.find('.modalSaveButton');
                var modalUpdateButton = modal.find('.modalUpdateButton');
                var modalLoading = modal.find('.modalLoading');
                var modalContent = modal.find('.modalContent');
                var modalForm = modal.find('.modalForm');
                var modalAlert = modal.find('.modalAlert');
                var styleName = 'border-color';
                var styleValue = '#ddd';

                modalNav.addClass('hidden');
                modalSaveButton.addClass('disabled')
                    .off('click');
                modalUpdateButton.removeClass('_editing')
                    .html('<span class="fa fa-pencil fa-fw"></span>')
                    .off('click');
                modalLoading.removeClass('hidden');
                modalContent.addClass('hidden');
                modalForm.addClass('hidden');
                modalAlert.addClass('hidden');
                modalAlert.children('div').html('');
                modal.find('input[name="cto34_id"]').val('');
                modalForm.find('form').find('input, select, textarea').css(styleName, styleValue);

            });

        }

        if (modalSave.length > 0 ) {

            modalSave.find('.saveButton').on('click', function(event) {

                var form = modalSave.find('form');
                var button = $(this);
                var defaultHtml = button.html();

                if (button.hasClass('disabled')) {
                    return false;
                }

                var request = $.ajax({
                    url: form.prop('action'),
                    type: form.prop('method'),
                    dataType: 'html',
                    timeout: 90000,
                    cache: false,
                    data: form.serialize(),
                    beforeSend: function() {

                        button.addClass('disabled');
                        button.html('<span class="fa fa-spinner fa-spin fa-fw"></span>');
                    }
                });

                request.done(function(response) {

                    var result = jQuery.parseJSON(response);

                    button.removeClass('disabled');
                    button.html(defaultHtml);

                    if (result.status === false) {

                        $.each(result.errors.all, function(key, error) {

                            var input = form.find('input[name="' + key + '"]');
                            var select = form.find('select[name="' + key + '"]');
                            var textarea = form.find('textarea[name="' + key + '"]');
                            var styleName = 'border-color';
                            var styleValue = '#ef5350';

                            if (input.length > 0) {
                                input.css(styleName, styleValue);
                            }

                            if (select.length > 0) {
                                select.css(styleName, styleValue);
                            }

                            if (textarea.length > 0) {
                                textarea.css(styleName, styleValue);
                            }
                        });

                        alert(result.message);


                    } else {

                        button.html(defaultHtml);
                        alert('Registro guardado correctamente');
                        window.location.href = result.data.base;
                    }
                });

                request.fail(function(xhr, textStatus) {

                    button.html(defaultHtml);
                    button.removeClass('disabled');
                    alert('No se puede guardar.');

                });

            });

            $(modalSave).on('show.bs.modal', function(event) {

                $.event.trigger({
                    type:    "save:show.rm." + label,
                    modal: modalSave
                });
            });

            $(modalSave).on('hide.bs.modal', function(event) {

                var modal = $(this);
                var form = modal.find('form');

                var styleName = 'border-color';
                var styleValue = '#ddd';

                form.find('input[name!="_base"][name!="cto34_work"][name!="_token"][data-clip!="true"], select, textarea').val('');
                form.find('input[type="checkbox"]').prop('checked', false);
                form.find('input, select, textarea').css(styleName, styleValue);

            });
        }

    };

    return App;

}());
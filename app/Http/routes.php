<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * Index
 * Solo redirecciona a login
 * @return RedirectResponse
 */
Route::get('/', function() {    
	return redirect('login');
});

/**
 * Language
 * Cambia el lenguaje del sistema
 *
 * @param string locale
 * @return Response
 */
Route::get('/language/{locale}', function($locale) {

	Cookie::queue(cookie()->forever('locale', $locale));
	return Redirect::to(URL::previous());
});

/**
 * Rutas de Samuel Medina
 */
Route::post('empresas',['as' => 'empresas','uses' => '\App\Http\Controllers\Empresa\EmpresaController@indexEmpresa']);

Route::get('cartasPoder/{id}','\App\Http\Controllers\Empresa\ComunicacionesController@cartapoder');

Route::get('prueba',['as' => 'prueba','uses' => '\App\Http\Controllers\Empresa\ComunicacionesController@obligaciones']);

/**
 * Rutas de Samuel Medina
 */



/**
 * Rutas de usuario
 */
Route::group(['namespace' => 'Auth'], function () {
    Route::get('/login', 'UserController@showLogin');
	Route::post('/action/login', 'UserController@doLogin');

    Route::get('/action/logout', 'UserController@doLogout');
});


/**
 * Rutas para peticiones mediante AJAX
 */
Route::group(['namespace' => 'Ajax'], function () {


    //Rutas generales
    Route::put('/ajax/action/records/closed', 'ActionController@doRecordClosed');

    Route::get('/ajax/action/contract/deliverables/one', 'SearchController@doSearchContractDeliverableByID');
    Route::post('/ajax/action/contract/deliverables/save', 'ActionController@doContractDeliverableSave');
    Route::put('/ajax/action/contract/deliverables/update', 'ActionController@doContractDeliverableUpdate');

    // Rutas ajax para personas
    Route::post('/ajax/search/persons', 'SearchController@doPersonSearch');
    Route::post('/ajax/action/save/persons', 'ActionController@doPersonSave');

    // Rutas ajax para empresa
    Route::post('/ajax/search/business', 'SearchController@doBusinessSearch');
    Route::post('/ajax/action/save/business', 'ActionController@doBusinessSave');

    // Rutas ajax para buscar empresas en obra
    Route::post('/ajax/search/businessWork', 'SearchController@doBusinessWorkSearch');
    Route::get('/ajax/search/businessWork/one', 'SearchController@doBusinessWorkByIDSearch');
    Route::get('/ajax/search/businessWork/{id}', 'SearchController@doBusinessWorkSearchByID');
    Route::post('/ajax/search/businessAddressWork', 'SearchController@doBusinessAddressWorkSearch');

    // Rutas ajax para buscar personas en obra
    Route::post('/ajax/search/personsWork', 'SearchController@doPersonWorkSearch');
    Route::get('/ajax/search/personsWork/one', 'SearchController@doPersonWorkByIDSearch');
    Route::post('/ajax/search/personWorkByBusinessId', 'SearchController@doPersonWorkSearchByBusinessId');

    // Rutas ajax para buscar personas mi empresa
    Route::post('/ajax/search/personsMyBusiness', 'SearchController@doPersonMyBusinessSearch');
    Route::post('/ajax/search/personsBusiness', 'SearchController@doPersonBusinessSearch');

    // Rutas ajax para buscar Empresas mi empresa
    Route::post('/ajax/search/businessMyBusiness', 'SearchController@doBusinessMyBusinessSearch');

    // Rutas ajax para obras - directorio empresas
    Route::post('/ajax/action/save/businessWork', 'ActionController@doBusinessWorkSave');
    Route::post('/ajax/action/save/personsWork', 'ActionController@doPersonWorkSave');

    // Rutas ajax para partidas
    Route::post('/ajax/action/save/departureWork', 'ActionController@doDepartureWorkSave');

    // Rutas ajax para subpartidas
    Route::post('/ajax/action/search/subdeparture', 'SearchController@doSubdepartureSearch');
    Route::post('/ajax/action/save/subdepartureWork', 'ActionController@doSubdepartureWorkSave');

    // Rutas ajax para obras - contratos
    Route::post('/ajax/action/search/contracts', 'SearchController@doContractWorkSearch');

    // Rutas ajax para obras - catalogos
    Route::post('/ajax/action/search/catalogs', 'SearchController@doCatalogSearch');

    // Rutas ajax para obras - catalogos
    Route::post('/ajax/action/search/customers', 'SearchController@doCustomerSearch');


    Route::post('/ajax/search/businessAddress', 'SearchController@doBusinessAddressSearch');

    Route::post('/ajax/search/users', 'SearchController@doUserSearch');
    Route::post('/ajax/search/generators', 'SearchController@doGeneratorsSearch');

    Route::get('/ajax/search/buildings/one', 'SearchController@doBuildingByIDSearch');
    Route::put('/ajax/buildings/actions/update', 'ActionController@doBuildingUpdate');
    Route::put('/ajax/action/buildings/closed', 'ActionController@doBuildingClosed');

    Route::get('/ajax/search/levels', 'SearchController@doLevelsSearch');
    Route::get('/ajax/search/levels/one', 'SearchController@doLevelsByIDSearch');
    Route::post('/ajax/action/levels/save', 'ActionController@doLevelSave');
    Route::post('/ajax/action/levels/update', 'ActionController@doLevelUpdate');

    Route::get('/ajax/search/locals/one', 'SearchController@doLocalByIDSearch');
    Route::post('/ajax/action/locals/save', 'ActionController@doLocalSave');
    Route::post('/ajax/action/locals/update', 'ActionController@doLocalUpdate');

    Route::group(['namespace' => 'ConstructionWork'], function () {

        Route::post('/ajax/action/buildings/update', 'BuildingController@doBuildingUpdate');
        Route::put('/ajax/action/buildings/closed', 'BuildingController@doBuildingClosed');
    });

});

/**
 * Rutas del panel
 *
 * Todos los controladores para panel deben de estar en la carpeta Panel
 */
Route::group(['namespace' => 'Panel'], function () {

	// Ruta escritorio
    Route::get('/panel', 'HomeController@showIndex');
    Route::post('/panel/comments/save', 'CommentController@doSave');
    Route::put('/panel/comments/update', 'CommentController@doUpdate');
    Route::delete('/panel/comments/delete', 'CommentController@doDelete');

    /**
     * Rutas de Imagenes
     */
    Route::get('/panel/images/{image}', 'ImageController@showIndex');

    // Rutas de Sistema
    // Todos los controladores para sistema deben de estar en la carpeta Panel / System
    Route::group(['namespace' => 'System'], function () {

        // Rutas de usuarios en sistema
        Route::get('/panel/system/users/info', 'UserController@showIndex');
        Route::get('/panel/system/users/info/{id}', 'UserController@showIndex');
        Route::get('/panel/system/users/search', 'UserController@showSearch');
        Route::get('/panel/system/users/search/{id}', 'UserController@showSearch');
        Route::get('/panel/system/users/save', 'UserController@showSave');
        Route::get('/panel/system/users/update/{id}', 'UserController@showUpdate');
        Route::post('/panel/system/users/action/save', 'UserController@doSave');
        Route::put('/panel/system/users/action/update', 'UserController@doUpdate');
        Route::put('/panel/system/users/action/update/password', 'UserController@doUpdatePassword');
        Route::put('/panel/system/users/action/restore', 'UserController@doRestore');
        Route::delete('/panel/system/users/action/delete', 'UserController@doDelete');

        // Rutas de personas en sistema
        Route::get('/panel/system/persons/info', 'PersonController@showIndex');
        Route::get('/panel/system/persons/info/{id}', 'PersonController@showIndex');
        Route::get('/panel/system/persons/search', 'PersonController@showSearch');
        Route::get('/panel/system/persons/search/{id}', 'PersonController@showSearch');
        Route::get('/panel/system/persons/save', 'PersonController@showSave');
        Route::get('/panel/system/persons/update', 'PersonController@showUpdate');
        Route::get('/panel/system/persons/update/{id}', 'PersonController@showUpdate');
        Route::post('/panel/system/persons/action/save', 'PersonController@doSave');
        Route::put('/panel/system/persons/action/update', 'PersonController@doUpdate');
        Route::put('/panel/system/persons/action/restore', 'PersonController@doRestore');
        Route::delete('/panel/system/persons/action/delete', 'PersonController@doDelete');

        // Rutas de empresas en sistema
        Route::get('/panel/system/business', 'BusinessController@showIndex');
        Route::get('/panel/system/business/info/{id}', 'BusinessController@showInfo');
        Route::get('/panel/system/business/search', 'BusinessController@showSearch');
        Route::get('/panel/system/business/search/info/{id}', 'BusinessController@showSearchInfo');
        Route::get('/panel/system/business/filter', 'BusinessController@showFilter');
        Route::get('/panel/system/business/filter/info/{id}', 'BusinessController@showFilterInfo');
        Route::get('/panel/system/business/save', 'BusinessController@showSave');
        Route::post('/panel/system/business/action/save', 'BusinessController@doSave');
        Route::get('/panel/system/business/update/{id}', 'BusinessController@showUpdate');
        Route::put('/panel/system/business/action/update', 'BusinessController@doUpdate');
        Route::delete('/panel/system/business/action/delete', 'BusinessController@doDelete');

        // Rutas de clientes en sistema
        Route::get('/panel/system/customers/info', 'CustomerController@showIndex');
        Route::get('/panel/system/customers/info/{id}', 'CustomerController@showIndex');
        Route::get('/panel/system/customers/search', 'CustomerController@showSearch');
        Route::get('/panel/system/customers/search/{id}', 'CustomerController@showSearch');
        Route::get('/panel/system/customers/save', 'CustomerController@showSave');
        Route::get('/panel/system/customers/update', 'CustomerController@showUpdate');
        Route::get('/panel/system/customers/update/{id}', 'CustomerController@showUpdate');
        Route::post('/panel/system/customers/action/save', 'CustomerController@doSave');
        Route::put('/panel/system/customers/action/update', 'CustomerController@doUpdate');
        Route::put('/panel/system/customers/action/restore', 'CustomerController@doRestore');
        Route::delete('/panel/system/customers/action/delete', 'CustomerController@doDelete');

        // Rutas para IMSS en sistema
        Route::get('/panel/system/imss/info', 'ImssController@showIndex');
        Route::get('/panel/system/imss/info/{id}', 'ImssController@showIndex');
        Route::get('/panel/system/imss/search', 'ImssController@showSearch');
        Route::get('/panel/system/imss/search/{id}', 'ImssController@showSearch');
        Route::get('/panel/system/imss/save/', 'ImssController@showSave');
        Route::post('/panel/system/imss/action/save', 'ImssController@doSave');
        Route::get('/panel/system/imss/update/{id}', 'ImssController@showUpdate');
        Route::put('/panel/system/imss/action/update', 'ImssController@doUpdate');
        Route::delete('/panel/system/imss/action/delete', 'ImssController@doDelete');
        Route::put('/panel/system/imss/action/restore', 'ImssController@doRestore');


        // Rutas para Arancel en sistema
        Route::get('/panel/system/arancel/info', 'ArancelController@showIndex');
        Route::get('/panel/system/arancel/info/{id}', 'ArancelController@showIndex');
        Route::get('/panel/system/arancel/search', 'ArancelController@showSearch');
        Route::get('/panel/system/arancel/search/{id}', 'ArancelController@showSearch');
        Route::get('/panel/system/arancel/save/', 'ArancelController@showSave');
        Route::post('/panel/system/arancel/action/save', 'ArancelController@doSave');
        Route::get('/panel/system/arancel/update/{id}', 'ArancelController@showUpdate');
        Route::put('/panel/system/arancel/action/update', 'ArancelController@doUpdate');
        Route::delete('/panel/system/arancel/action/delete', 'ArancelController@doDelete');
        Route::put('/panel/system/arancel/action/restore', 'ArancelController@doRestore');

        // Rutas para Unidades en sistema
        Route::get('/panel/system/unities/info', 'UnityController@showIndex');
        Route::get('/panel/system/unities/info/{id}', 'UnityController@showIndex');
        Route::get('/panel/system/unities/search', 'UnityController@showSearch');
        Route::get('/panel/system/unities/search/{id}', 'UnityController@showSearch');
        Route::get('/panel/system/unities/save/', 'UnityController@showSave');
        Route::post('/panel/system/unities/action/save', 'UnityController@doSave');
        Route::get('/panel/system/unities/update/{id}', 'UnityController@showUpdate');
        Route::put('/panel/system/unities/action/update', 'UnityController@doUpdate');
        Route::delete('/panel/system/unities/action/delete', 'UnityController@doDelete');
        Route::put('/panel/system/unities/action/restore', 'UnityController@doRestore');

        // Rutas para Mis empresas en sistema
        Route::get('/panel/system/myBusiness', 'MyBusinessController@showIndex');
        Route::get('/panel/system/myBusiness/info/{id}', 'MyBusinessController@showInfo');
        Route::get('/panel/system/myBusiness/save/', 'MyBusinessController@showSave');
        Route::post('/panel/system/myBusiness/action/save', 'MyBusinessController@doSave');
        Route::get('/panel/system/myBusiness/update/{id}', 'MyBusinessController@showUpdate');
        Route::put('/panel/system/myBusiness/action/update', 'MyBusinessController@doUpdate');
        Route::delete('/panel/system/myBusiness/action/delete', 'MyBusinessController@doDelete');

        // Rutas para Rubros en sistema
        Route::get('/panel/system/items/info', 'ItemController@showIndex');
        Route::get('/panel/system/items/info/{id}', 'ItemController@showIndex');
        Route::get('/panel/system/items/search', 'ItemController@showSearch');
        Route::get('/panel/system/items/search/{id}', 'ItemController@showSearch');
        Route::get('/panel/system/items/save/', 'ItemController@showSave');
        Route::post('/panel/system/items/action/save', 'ItemController@doSave');
        Route::get('/panel/system/items/update/{id}', 'ItemController@showUpdate');
        Route::put('/panel/system/items/action/update', 'ItemController@doUpdate');
        Route::delete('/panel/system/items/action/delete', 'ItemController@doDelete');
        Route::put('/panel/system/items/action/restore', 'ItemController@doRestore');

        // Rutas para Aplicacion en sistema
        Route::get('/panel/system/aplications/info', 'AplicationController@showIndex');
        Route::get('/panel/system/aplications/info/{id}', 'AplicationController@showIndex');
        Route::get('/panel/system/aplications/search', 'AplicationController@showSearch');
        Route::get('/panel/system/aplications/search/{id}', 'AplicationController@showSearch');
        Route::get('/panel/system/aplications/save/', 'AplicationController@showSave');
        Route::post('/panel/system/aplications/action/save', 'AplicationController@doSave');
        // Route::get('/panel/system/items/update/{id}', 'ItemController@showUpdate');
        // Route::put('/panel/system/items/action/update', 'ItemController@doUpdate');
        Route::delete('/panel/system/aplications/action/delete', 'AplicationController@doDelete');
        Route::put('/panel/system/aplications/action/restore', 'AplicationController@doRestore');

        // Rutas de facturas en sistema
        Route::get('/panel/system/invoices', 'InvoiceController@showIndex');
        Route::get('/panel/system/invoices/info/{id}', 'InvoiceController@showInfo');
        Route::get('/panel/system/invoices/search', 'BusinessController@showSearch');
        Route::get('/panel/system/invoices/search/info/{id}', 'InvoiceController@showSearchInfo');
        Route::get('/panel/system/invoices/filter', 'InvoiceController@showFilter');
        Route::get('/panel/system/invoices/filter/info/{id}', 'InvoiceController@showFilterInfo');
        Route::get('/panel/system/invoices/save', 'InvoiceController@showSave');
        Route::post('/panel/system/invoices/action/save', 'InvoiceController@doSave');
        Route::get('/panel/system/invoices/update/{id}', 'InvoiceController@showUpdate');
        Route::put('/panel/system/invoices/action/update', 'InvoiceController@doUpdate');
        Route::delete('/panel/system/invoices/action/delete', 'InvoiceController@doDelete');

        // Rutas de correos en sistema
        Route::get('/panel/system/emails/info', 'EmailController@showIndex');
        Route::get('/panel/system/emails/info/{id}', 'EmailController@showIndex');
        Route::get('/panel/system/emails/search', 'EmailController@showSearch');
        Route::get('/panel/system/emails/search/{id}', 'EmailController@showSearch');
        Route::get('/panel/system/emails/save/', 'EmailController@showSave');
        Route::post('/panel/system/emails/action/save', 'EmailController@doSave');
        Route::get('/panel/system/emails/update/{id}', 'EmailController@showUpdate');
        Route::put('/panel/system/emails/action/update', 'EmailController@doUpdate');
        Route::delete('/panel/system/emails/action/delete', 'EmailController@doDelete');
        Route::put('/panel/system/emails/action/restore', 'EmailController@doRestore');

        // Rutas de telefonos en sistema
        Route::get('/panel/system/phones/info', 'PhoneController@showIndex');
        Route::get('/panel/system/phones/info/{id}', 'PhoneController@showIndex');
        Route::get('/panel/system/phones/search', 'PhoneController@showSearch');
        Route::get('/panel/system/phones/search/{id}', 'PhoneController@showSearch');
        Route::get('/panel/system/phones/save/', 'PhoneController@showSave');
        Route::post('/panel/system/phones/action/save', 'PhoneController@doSave');
        Route::get('/panel/system/phones/update/{id}', 'PhoneController@showUpdate');
        Route::put('/panel/system/phones/action/update', 'PhoneController@doUpdate');
        Route::delete('/panel/system/phones/action/delete', 'PhoneController@doDelete');
        Route::put('/panel/system/phones/action/restore', 'PhoneController@doRestore');


        // Rutas de monedas en sistema
        Route::get('/panel/system/currencies/info', 'CurrencyController@showIndex');
        Route::get('/panel/system/currencies/info/{id}', 'CurrencyController@showIndex');
        Route::get('/panel/system/currencies/search', 'CurrencyController@showSearch');
        Route::get('/panel/system/currencies/search/{id}', 'CurrencyController@showSearch');
        Route::get('/panel/system/currencies/save/', 'CurrencyController@showSave');
        Route::post('/panel/system/currencies/action/save', 'CurrencyController@doSave');
        Route::get('/panel/system/currencies/update/{id}', 'CurrencyController@showUpdate');
        Route::put('/panel/system/currencies/action/update', 'CurrencyController@doUpdate');
        Route::delete('/panel/system/currencies/action/delete', 'CurrencyController@doDelete');
        Route::put('/panel/system/currencies/action/restore', 'CurrencyController@doRestore');

        // Rutas de grupos en sistema
        Route::get('/panel/system/groups/info', 'GroupController@showIndex');
        Route::get('/panel/system/groups/info/{id}', 'GroupController@showIndex');
        Route::get('/panel/system/groups/search', 'GroupController@showSearch');
        Route::get('/panel/system/groups/search/{id}', 'GroupController@showSearch');
        Route::get('/panel/system/groups/save/', 'GroupController@showSave');
        Route::post('/panel/system/groups/action/save', 'GroupController@doSave');
        Route::get('/panel/system/groups/update/{id}', 'GroupController@showUpdate');
        Route::put('/panel/system/groups/action/update', 'GroupController@doUpdate');
        Route::delete('/panel/system/groups/action/delete', 'GroupController@doDelete');
        Route::put('/panel/system/groups/action/restore', 'GroupController@doRestore');

        // Rutas de logs en sistema
        Route::get('/panel/system/logs', 'LogController@showIndex');
        Route::get('/panel/system/logs/info/{id}', 'LogController@showInfo');

        // Rutas de sesiones en sistema
        Route::get('/panel/system/sessions', 'SessionController@showIndex');
        Route::get('/panel/system/sessions/info/{id}', 'SessionController@showInfo');
        
    });

    // Rutas de Empresa
    // Todos los controladores para empresa deben de estar en la carpeta Panel / Business
    Route::group(['namespace' => 'Business'], function () {

        // Rutas de usuarios en sistema
        Route::get('/panel/business/users/info', 'OfficeController@showIndex');
        // Route::get('/panel/system/users/info/{id}', 'UserController@showIndex');
        // Route::get('/panel/system/users/search', 'UserController@showSearch');
        // Route::get('/panel/system/users/search/{id}', 'UserController@showSearch');
        // Route::get('/panel/system/users/save', 'UserController@showSave');
        // Route::get('/panel/system/users/update/{id}', 'UserController@showUpdate');
        // Route::post('/panel/system/users/action/save', 'UserController@doSave');
        // Route::put('/panel/system/users/action/update', 'UserController@doUpdate');
        // Route::put('/panel/system/users/action/update/password', 'UserController@doUpdatePassword');
        // Route::put('/panel/system/users/action/restore', 'UserController@doRestore');
        // Route::delete('/panel/system/users/action/delete', 'UserController@doDelete');

    });  

    // Rutas de Obras
    // Todos los controladores para obras deben de estar en la carpeta Panel / ConstructionWork
    Route::group(['namespace' => 'ConstructionWork'], function () {

        /*
         * Rutas de ámbito obra
         * - Obra
         */

        // Rutas de obra
        Route::get('/panel/constructionwork/home', 'HomeController@showIndex');
        Route::get('/panel/constructionwork/home/{id}', 'HomeController@showIndex');
        Route::get('/panel/constructionwork/info/{id}', 'HomeController@showInfo');
        Route::get('/panel/constructionwork/search', 'HomeController@showSearch');
        Route::get('/panel/constructionwork/search/{id}', 'HomeController@showSearch');
        Route::get('/panel/constructionwork/save', 'HomeController@showSave');
        Route::post('/panel/constructionwork/action/save', 'HomeController@doSave');

        /*
         * Rutas de ámbito Coordicación
         * - Catálogos
         * - Generadores
         * - Estimaciones
         */

        // Rutas de diario de la obra
        Route::get('/panel/constructionwork/{workId}/dailys_work/info', 'DailyWorkController@showIndex');
        Route::get('/panel/constructionwork/{workId}/dailys_work/info/{id}', 'DailyWorkController@showIndex');
        Route::get('/panel/constructionwork/{workId}/dailys_work/search', 'DailyWorkController@showSearch');
        Route::get('/panel/constructionwork/{workId}/dailys_work/search/{id}', 'DailyWorkController@showSearch');
        Route::get('/panel/constructionwork/{workId}/dailys_work/save', 'DailyWorkController@showSave');
        Route::get('/panel/constructionwork/{workId}/dailys_work/update', 'DailyWorkController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/dailys_work/update/{id}', 'DailyWorkController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/dailys_work/action/save', 'DailyWorkController@doSave');
        Route::put('/panel/constructionwork/{workId}/dailys_work/action/update', 'DailyWorkController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/dailys_work/action/restore', 'DailyWorkController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/dailys_work/action/delete', 'DailyWorkController@doDelete');

        /*
         * Rutas de ámbito Ubicación
         * - Edificios
         * - Niveles
         * - Locales
         */

        // Rutas de edificios
        Route::get('/panel/constructionwork/{workId}/buildings/info', 'BuildingController@showIndex');
        Route::get('/panel/constructionwork/{workId}/buildings/info/{id}', 'BuildingController@showIndex');
        Route::get('/panel/constructionwork/{workId}/buildings/search', 'BuildingController@showSearch');
        Route::get('/panel/constructionwork/{workId}/buildings/search/{id}', 'BuildingController@showSearch');
        Route::get('/panel/constructionwork/{workId}/buildings/save', 'BuildingController@showSave');
        Route::get('/panel/constructionwork/{workId}/buildings/update', 'BuildingController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/buildings/update/{id}', 'BuildingController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/buildings/action/save', 'BuildingController@doSave');
        Route::put('/panel/constructionwork/{workId}/buildings/action/update', 'BuildingController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/buildings/action/restore', 'BuildingController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/buildings/action/delete', 'BuildingController@doDelete');

        // Rutas de niveles
        Route::get('/panel/constructionwork/{workId}/levels/info', 'LevelController@showIndex');
        Route::get('/panel/constructionwork/{workId}/levels/info/{id}', 'LevelController@showIndex');
        Route::get('/panel/constructionwork/{workId}/levels/search', 'LevelController@showSearch');
        Route::get('/panel/constructionwork/{workId}/levels/search/{id}', 'LevelController@showSearch');
        Route::get('/panel/constructionwork/{workId}/levels/save', 'LevelController@showSave');
        Route::get('/panel/constructionwork/{workId}/levels/update', 'LevelController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/levels/update/{id}', 'LevelController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/levels/action/save', 'LevelController@doSave');
        Route::put('/panel/constructionwork/{workId}/levels/action/update', 'LevelController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/levels/action/restore', 'LevelController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/levels/action/delete', 'LevelController@doDelete');

        // Rutas de locales
        Route::get('/panel/constructionwork/{workId}/locals/info', 'LocalController@showIndex');
        Route::get('/panel/constructionwork/{workId}/locals/info/{id}', 'LocalController@showIndex');
        Route::get('/panel/constructionwork/{workId}/locals/search', 'LocalController@showSearch');
        Route::get('/panel/constructionwork/{workId}/locals/search/{id}', 'LocalController@showSearch');
        Route::get('/panel/constructionwork/{workId}/locals/save', 'LocalController@showSave');
        Route::get('/panel/constructionwork/{workId}/locals/update', 'LocalController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/locals/update/{id}', 'LocalController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/locals/action/save', 'LocalController@doSave');
        Route::put('/panel/constructionwork/{workId}/locals/action/update', 'LocalController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/locals/action/restore', 'LocalController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/locals/action/delete', 'LocalController@doDelete');

        /*
         * Rutas de ámbito Directiorios
         * - Empresas
         * - Personas
         */

        // Rutas de empresas
        Route::get('/panel/constructionwork/{workId}/business/info', 'BusinessController@showIndex');
        Route::get('/panel/constructionwork/{workId}/business/info/{id}', 'BusinessController@showIndex');
        Route::get('/panel/constructionwork/{workId}/business/search', 'BusinessController@showSearch');
        Route::get('/panel/constructionwork/{workId}/business/search/{id}', 'BusinessController@showSearch');
        Route::get('/panel/constructionwork/{workId}/business/save', 'BusinessController@showSave');
        Route::get('/panel/constructionwork/{workId}/business/update', 'BusinessController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/business/update/{id}', 'BusinessController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/business/action/save', 'BusinessController@doSave');
        Route::put('/panel/constructionwork/{workId}/business/action/update', 'BusinessController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/business/action/restore', 'BusinessController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/business/action/delete', 'BusinessController@doDelete');

        // Rutas de personas
        Route::get('/panel/constructionwork/{workId}/persons/info', 'PersonController@showIndex');
        Route::get('/panel/constructionwork/{workId}/persons/info/{id}', 'PersonController@showIndex');
        Route::get('/panel/constructionwork/{workId}/persons/search', 'PersonController@showSearch');
        Route::get('/panel/constructionwork/{workId}/persons/search/{id}', 'PersonController@showSearch');
        Route::get('/panel/constructionwork/{workId}/persons/save', 'PersonController@showSave');
        Route::get('/panel/constructionwork/{workId}/persons/update', 'PersonController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/persons/update/{id}', 'PersonController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/persons/action/save', 'PersonController@doSave');
        Route::put('/panel/constructionwork/{workId}/persons/action/update', 'PersonController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/persons/action/restore', 'PersonController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/persons/action/delete', 'PersonController@doDelete');

        /*
         * Rutas de ámbito Finanzas
         * - Catálogos
         * - Generadores
         * - Estimaciones
         */

        // Rutas de catálogos
        Route::get('/panel/constructionwork/{workId}/catalogs/info', 'CatalogController@showIndex');
        Route::get('/panel/constructionwork/{workId}/catalogs/info/{id}', 'CatalogController@showIndex');
        Route::get('/panel/constructionwork/{workId}/catalogs/search', 'CatalogController@showSearch');
        Route::get('/panel/constructionwork/{workId}/catalogs/search/{id}', 'CatalogController@showSearch');
        Route::get('/panel/constructionwork/{workId}/catalogs/save', 'CatalogController@showSave');
        Route::get('/panel/constructionwork/{workId}/catalogs/update', 'CatalogController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/catalogs/update/{id}', 'CatalogController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/catalogs/action/save', 'CatalogController@doSave');
        Route::put('/panel/constructionwork/{workId}/catalogs/action/update', 'CatalogController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/catalogs/action/restore', 'CatalogController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/catalogs/action/delete', 'CatalogController@doDelete');

        // Rutas de generadores
        Route::get('/panel/constructionwork/{workId}/generators/info', 'GeneratorController@showIndex');
        Route::get('/panel/constructionwork/{workId}/generators/info/{id}', 'GeneratorController@showIndex');
        Route::get('/panel/constructionwork/{workId}/generators/search', 'GeneratorController@showSearch');
        Route::get('/panel/constructionwork/{workId}/generators/search/{id}', 'GeneratorController@showSearch');
        Route::get('/panel/constructionwork/{workId}/generators/save', 'GeneratorController@showSave');
        Route::get('/panel/constructionwork/{workId}/generators/update', 'GeneratorController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/generators/update/{id}', 'GeneratorController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/generators/action/save', 'GeneratorController@doSave');
        Route::put('/panel/constructionwork/{workId}/generators/action/update', 'GeneratorController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/generators/action/restore', 'GeneratorController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/generators/action/delete', 'GeneratorController@doDelete');
        Route::put('/panel/constructionwork/{workId}/generators/action/save/revision', 'GeneratorController@doSaveRevision');
        Route::put('/panel/constructionwork/{workId}/generators/action/save/authorization', 'GeneratorController@doSaveAuthorization');

        // Rutas de estimaciones
        Route::get('/panel/constructionwork/{workId}/estimates/info', 'EstimateController@showIndex');
        Route::get('/panel/constructionwork/{workId}/estimates/info/{id}', 'EstimateController@showIndex');
        Route::get('/panel/constructionwork/{workId}/estimates/search', 'EstimateController@showSearch');
        Route::get('/panel/constructionwork/{workId}/estimates/search/{id}', 'EstimateController@showSearch');
        Route::get('/panel/constructionwork/{workId}/estimates/save', 'EstimateController@showSave');
        Route::get('/panel/constructionwork/{workId}/estimates/update', 'EstimateController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/estimates/update/{id}', 'EstimateController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/estimates/action/save', 'EstimateController@doSave');
        Route::put('/panel/constructionwork/{workId}/estimates/action/update', 'EstimateController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/estimates/action/restore', 'EstimateController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/estimates/action/delete', 'EstimateController@doDelete');


        /*
         * Rutas de ámbito Legal
         * - Contrato
         * - Bitácoras
         * - Oficios
         */

        // Rutas de contratos
        Route::get('/panel/constructionwork/{workId}/contracts/info', 'ContractController@showIndex');
        Route::get('/panel/constructionwork/{workId}/contracts/info/{id}', 'ContractController@showIndex');
        Route::get('/panel/constructionwork/{workId}/contracts/search', 'ContractController@showSearch');
        Route::get('/panel/constructionwork/{workId}/contracts/search/{id}', 'ContractController@showSearch');
        Route::get('/panel/constructionwork/{workId}/contracts/save', 'ContractController@showSave');
        Route::get('/panel/constructionwork/{workId}/contracts/update', 'ContractController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/contracts/update/{id}', 'ContractController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/contracts/action/save', 'ContractController@doSave');
        Route::put('/panel/constructionwork/{workId}/contracts/action/update', 'ContractController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/contracts/action/restore', 'ContractController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/contracts/action/delete', 'ContractController@doDelete');

        // Rutas de bitácoras
        Route::get('/panel/constructionwork/{workId}/binnacles/info', 'BinnacleController@showIndex');
        Route::get('/panel/constructionwork/{workId}/binnacles/info/{id}', 'BinnacleController@showIndex');
        Route::get('/panel/constructionwork/{workId}/binnacles/search', 'BinnacleController@showSearch');
        Route::get('/panel/constructionwork/{workId}/binnacles/search/{id}', 'BinnacleController@showSearch');
        Route::get('/panel/constructionwork/{workId}/binnacles/save', 'BinnacleController@showSave');
        Route::get('/panel/constructionwork/{workId}/binnacles/update', 'BinnacleController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/binnacles/update/{id}', 'BinnacleController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/binnacles/action/save', 'BinnacleController@doSave');
        Route::put('/panel/constructionwork/{workId}/binnacles/action/update', 'BinnacleController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/binnacles/action/restore', 'BinnacleController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/binnacles/action/delete', 'BinnacleController@doDelete');

        // Rutas de oficios
        Route::get('/panel/constructionwork/{workId}/trades/info', 'TradeController@showIndex');
        Route::get('/panel/constructionwork/{workId}/trades/info/{id}', 'TradeController@showIndex');
        Route::get('/panel/constructionwork/{workId}/trades/search', 'TradeController@showSearch');
        Route::get('/panel/constructionwork/{workId}/trades/search/{id}', 'TradeController@showSearch');
        Route::get('/panel/constructionwork/{workId}/trades/save', 'TradeController@showSave');
        Route::get('/panel/constructionwork/{workId}/trades/update', 'TradeController@showUpdate');
        Route::get('/panel/constructionwork/{workId}/trades/update/{id}', 'TradeController@showUpdate');
        Route::post('/panel/constructionwork/{workId}/trades/action/save', 'TradeController@doSave');
        Route::put('/panel/constructionwork/{workId}/trades/action/update', 'TradeController@doUpdate');
        Route::put('/panel/constructionwork/{workId}/trades/action/restore', 'TradeController@doRestore');
        Route::delete('/panel/constructionwork/{workId}/trades/action/delete', 'TradeController@doDelete');

    });
});

/**
 * Test
 * Ruta para hacer pruebas
 *
 * @param string locale
 * @return Response
 */
Route::get('/test', function() {

    $business = App\Models\Business::has('myBusiness')->with('myBusiness')->get();
    dd($business[0]->myBusiness);

});
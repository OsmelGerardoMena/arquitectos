<?php
namespace App\Http\Controllers\Panel\System;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Currency;
use App\Models\Business;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.currency.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/currencies');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {
        $currencyAll = Currency::orderBy('tbMonedaID', 'DESC')->paginate($this->paginate);
        $currency = $currencyAll[0];

        if ($id != null)
            $currency = Currency::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Monedas',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $currencyAll->links(),
                    'prev' => $currencyAll->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $currencyAll->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $currencyAll->currentPage(),
                    'first' => $currencyAll->firstItem(),
                    'last' => $currencyAll->lastPage(),
                    'total' => $currencyAll->count()
                ],
            ],

            'currencies' => [
                'all' => $currencyAll,
                'one' => $currency
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showSearch(Request $request, $id = null)
    {
        $currencyAll = null;
        $currency = null;
        $redirect = "{$this->route}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;

        if (!$request->has('filter')) {
            if (!$request->has('q') || empty($request->q)) {
                return redirect($redirect)
                    ->withErrors(['Se debe ingresar un nombre válido'])
                    ->withInput();
            }
        }

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', MonedaAbreviatura,MonedaNombre,MonedaSimbolo,MonedaFraccion)"), 'LIKE', "{$search}"
            ];
        }

        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {
                case 'active':
                    $filters[] = [
                        'RegistroCerrado', '=', 0
                    ];
                    break;

                case 'closed':
                    $filters[] = [
                        'RegistroCerrado', '=', 1
                    ];
                    break;

                case 'deleted':
                    $hasDelete = true;
                    break;

                default:
                    break;
            }

        }

        $currencyAll = Currency::orderBy('tbMonedaID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $currencyAll = Currency::onlyTrashed()
                ->orderBy('tbMonedaID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($currencyAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $currency = $currencyAll[0];

        if ($id != null) {

            $currency = Currency::find($id);

            if ($hasDelete)
                $currency = Currency::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Monedas',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $currencyAll->links(),
                    'prev' => $currencyAll->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $currencyAll->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $currencyAll->currentPage(),
                    'first' => $currencyAll->firstItem(),
                    'last' => $currencyAll->lastPage(),
                    'total' => $currencyAll->count()
                ],
            ],

            'currencies' => [
                'all' => $currencyAll,
                'one' => $currency
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $currencyAll->count(),
                'query' => $request->q,
            ]
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Update user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showSave(Request $request)
    {

        $currencyAll = Currency::orderBy('tbMonedaID', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Monedas',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $currencyAll->links(),
                    'prev' => $currencyAll->setPath("{$this->route}/save")->previousPageUrl(),
                    'next' => $currencyAll->setPath("{$this->route}/save")->nextPageUrl(),
                    'current' => $currencyAll->currentPage(),
                    'first' => $currencyAll->firstItem(),
                    'last' => $currencyAll->lastPage(),
                    'total' => $currencyAll->count()
                ],
            ],

            'currencies' => [
                'all' => $currencyAll
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];

        return view($this->viewsPath.'save', $viewData);
    }

    /**
     * Update user front
     *
     * @param int id - user id
     * @return \Illuminate\Http\Response
     */
    public function showUpdate(Request $request, $id =  null)
    {


        $currencyAll = null;
        $currency = null;

        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', MonedaAbreviatura,MonedaNombre,MonedaSimbolo,MonedaFraccion)"), 'LIKE', "{$search}"
            ];

            $section = '/search';
        }

        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {
                case 'active':
                    $filters[] = [
                        'RegistroCerrado', '=', 0
                    ];
                    break;

                case 'closed':
                    $filters[] = [
                        'RegistroCerrado', '=', 1
                    ];
                    break;

                case 'deleted':
                    $hasDelete = true;
                    break;

                default:
                    break;
            }

            $section = '/search';
        }

        $currencyAll = Currency::orderBy('tbMonedaID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        $currency = $currencyAll[0];

        if ($id != null)
            $currency = Currency::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Monedas',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $currencyAll->links(),
                    'prev' => $currencyAll->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $currencyAll->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $currencyAll->currentPage(),
                    'first' => $currencyAll->firstItem(),
                    'last' => $currencyAll->lastPage(),
                    'total' => $currencyAll->count()
                ],
            ],

            'currencies' => [
                'all' => $currencyAll,
                'one' => $currency
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $currencyAll->count(),
                'query' => $request->q,
            ]
        ];

        return view($this->viewsPath.'update', $viewData);
    }

    /**
     * Insert user
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doSave(Request $request) {

        $redirect = "{$this->route}/save";

        $rules = [
            'cto34_name' => 'required',
            'cto34_abbreviation' => 'required',
            'cto34_symbol' => 'required',
            'cto34_fraction' => 'required',
        ];

        $messages = [
            'cto34_name.required' => 'Nombre requerido.',
            'cto34_abbreviation.required' => 'Abreviación requerida.',
            'cto34_symbol.required' => 'Simbolo requerido.',
            'cto34_fraction.required' => 'Fracción requerida.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $currency = new Currency();
        $currency->setConnection('mysql-writer');

        $currency->MonedaNombre = $request->input('cto34_name');
        $currency->MonedaAbreviatura = $request->input('cto34_abbreviation');
        $currency->MonedaSimbolo = $request->input('cto34_symbol');
        $currency->MonedaFraccion = $request->input('cto34_fraction');

        if (!$currency->save()) {
            return redirect($redirect)
                ->withErrors(['No se puede guarda la moneda, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Moneda agregada correctamente.');
    }

    /**
     * Update person
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doUpdate(Request $request) {

        $id = $request->input('cto34_id');
        $hasSearch = $request->input('_hasSearch');
        $redirectSuccess = "{$this->route}/info/{$id}{$request->input('_query')}";
        $redirectError = URL::previous();

        if ($hasSearch)
            $redirectSuccess = "{$this->route}/search/{$id}{$request->input('_query')}";

        $rules = [
            'cto34_id' => 'required'
        ];

        $messages = [
            'cto34_id.required' => 'Id momeda requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }

        $currency = Currency::find($id)->setConnection('mysql-writer');

        $currency->MonedaNombre = $request->input('cto34_name');
        $currency->MonedaAbreviatura = $request->input('cto34_abbreviation');
        $currency->MonedaSimbolo = $request->input('cto34_symbol');
        $currency->MonedaFraccion = $request->input('cto34_fraction');

        if (!$currency->save()) {
            return redirect($redirect)
                ->withErrors(['No se puede actualizar la moneda, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Moneda actualizada correctamente.');

    }

    /**
     * Delete person
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doDelete(Request $request) {

        $id = $request->input('cto34_id');
        $redirect = "{$this->route}/info";

        $rules = [
            'cto34_id' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de empresa requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $currency = Currency::find($id)->setConnection('mysql-writer');

        if (!$currency->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar la moneda, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Moneda <b>'.$currency->MonedaNombre.'</b> eliminada correctamente.');

    }


    /**
     * Do Restore
     * Realiza la acción de restaurar un registro eliminado
     *
     * @param $request Request
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function doRestore(Request $request) {

        $id = $request->input('cto34_id');
        $redirectSuccess = "{$this->route}/info";
        $redirectError = URL::previous();

        $rules = [
            'cto34_id' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de el arancel requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $currency = Currency::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$currency->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el arancel, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Arancel restaurado correctamente.');
    }

}
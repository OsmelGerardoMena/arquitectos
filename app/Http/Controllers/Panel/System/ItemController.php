<?php
namespace App\Http\Controllers\Panel\System;
use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Item;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.item.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/items');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {

        $itemsAll = Item::orderBy('tbRubroID', 'DESC')->paginate($this->paginate);
        $item = $itemsAll[0];

        if ($id != null)
            $item = Item::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Rubros',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $itemsAll->links(),
                    'prev' => $itemsAll->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $itemsAll->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $itemsAll->currentPage(),
                    'first' => $itemsAll->firstItem(),
                    'last' => $itemsAll->lastPage(),
                    'total' => $itemsAll->count()
                ],
            ],

            'items' => [
                'all' => $itemsAll,
                'one' => $item
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

        $itemsAll = null;
        $item = null;
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
                DB::raw("CONCAT_WS(' ', RubroAlias,RubroGastoTipo,RubroGastoClase,RubroDescripcion)"), 'LIKE', "{$search}"
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

        $itemsAll = Item::orderBy('tbRubroID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $itemsAll = Item::onlyTrashed()
                ->orderBy('tbRubroID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($itemsAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $item = $itemsAll[0];

        if ($id != null) {

            $item = Item::find($id);

            if ($hasDelete)
                $item = Item::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Rubros',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $itemsAll->links(),
                    'prev' => $itemsAll->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $itemsAll->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $itemsAll->currentPage(),
                    'first' => $itemsAll->firstItem(),
                    'last' => $itemsAll->lastPage(),
                    'total' => $itemsAll->count()
                ],
            ],

            'items' => [
                'all' => $itemsAll,
                'one' => $item
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $itemsAll->count(),
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

        $itemsAll = Item::orderBy('tbRubroID', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Rubros',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $itemsAll->links(),
                    'prev' => $itemsAll->setPath("{$this->route}/save")->previousPageUrl(),
                    'next' => $itemsAll->setPath("{$this->route}/save")->nextPageUrl(),
                    'current' => $itemsAll->currentPage(),
                    'first' => $itemsAll->firstItem(),
                    'last' => $itemsAll->lastPage(),
                    'total' => $itemsAll->count()
                ],
            ],

            'items' => [
                'all' => $itemsAll
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
    public function showUpdate(Request $request, $id = null)
    {
        $itemsAll = null;
        $item = null;

        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', RubroAlias,RubroGastoTipo,RubroGastoClase,RubroDescripcion)"), 'LIKE', "{$search}"
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

        $itemsAll = Item::orderBy('tbRubroID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        $item = $itemsAll[0];

        if ($id != null)
            $item = Item::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Rubros',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $itemsAll->links(),
                    'prev' => $itemsAll->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $itemsAll->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $itemsAll->currentPage(),
                    'first' => $itemsAll->firstItem(),
                    'last' => $itemsAll->lastPage(),
                    'total' => $itemsAll->count()
                ],
            ],

            'items' => [
                'all' => $itemsAll,
                'one' => $item
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $itemsAll->count(),
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
            'cto34_item' => 'required',
            'cto34_type' => 'required',
            'cto34_class' => 'required',
            'cto34_description' => 'required',
        ];
   
        $messages = [
            'cto34_item.required' => 'Rubro requerido.',
            'cto34_type.required' => 'Gasto tipo requerido.',
            'cto34_class.required' => 'Gasto clase requerido.',
            'cto34_description.required' => 'Descripción requerido.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $items = new Item();
        $items->setConnection('mysql-writer');

       
        $items->RubroAlias = $request->input('cto34_item');
        $items->RubroGastoTipo = $request->input('cto34_type');
        $items->RubroGastoClase = $request->input('cto34_class');
        $items->RubroDescripcion = $request->input('cto34_description');


        if( $request->input('cto34_closed') ){
            $items->RegistroCerrado = 1;
            //$items->tbCTOUsuarioID_Rol = auth_permissions_id(Auth::user()['role']);
        }
        



        if (!$items->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar el rubro, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Rubro agregado correctamente.');
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
            'cto34_id.required' => 'Id Rubro requerido.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }


        $items = Item::find($id)->setConnection('mysql-writer');

        $items->RubroAlias = $request->input('cto34_item');
        $items->RubroGastoTipo = $request->input('cto34_type');
        $items->RubroGastoClase = $request->input('cto34_class');
        $items->RubroDescripcion = $request->input('cto34_description');

        
     
        if (!$items->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar el rubro, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Rubro actualizado correctamente.');
        
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
            'cto34_id.required' => 'Id de cliente requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $item = Item::find($id)->setConnection('mysql-writer');

        if (!$item->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar la empresa, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Rubro <b>'.$item->RubroAlias.'</b> eliminado correctamente.');
        
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
            'cto34_id.required' => 'Id rubro requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $item = Item::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$item->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el rubro, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Rubro restaurado correctamente.');
    }
}
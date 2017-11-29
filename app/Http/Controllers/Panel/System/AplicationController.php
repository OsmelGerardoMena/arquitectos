<?php
namespace App\Http\Controllers\Panel\System;
use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Aplication;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AplicationController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.aplication.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/aplications');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {

        $aplicationAll = Aplication::orderBy('TbAplicacionID', 'DESC')->paginate($this->paginate);
        $aplication = $aplicationAll[0];

        if ($id != null)
            $aplication = Aplication::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Aplicaciones',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $aplicationAll->links(),
                    'prev' => $aplicationAll->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $aplicationAll->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $aplicationAll->currentPage(),
                    'first' => $aplicationAll->firstItem(),
                    'last' => $aplicationAll->lastPage(),
                    'total' => $aplicationAll->count()
                ],
            ],

            'aplications' => [
                'all' => $aplicationAll,
                'one' => $aplication
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
    public function showSearch(Request $request)
    {
        $aplicationAll = null;
        $aplication = null;
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
                DB::raw("CONCAT_WS(' ', AplicacionAlias,AplicacionDescripcion)"), 'LIKE', "{$search}"
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

        $aplicationAll = Aplication::orderBy('TbAplicacionID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $aplicationAll = Aplication::onlyTrashed()
                ->orderBy('TbAplicacionID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($aplicationAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $aplication = $aplicationAll[0];

        if ($id != null) {

            $aplication = Aplication::find($id);

            if ($hasDelete)
                $aplication = Aplication::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Aplicaciones',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $aplicationAll->links(),
                    'prev' => $aplicationAll->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $aplicationAll->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $aplicationAll->currentPage(),
                    'first' => $aplicationAll->firstItem(),
                    'last' => $aplicationAll->lastPage(),
                    'total' => $aplicationAll->count()
                ],
            ],

            'aplications' => [
                'all' => $aplicationAll,
                'one' => $aplication
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $aplicationAll->count(),
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

        $aplicationAll = Aplication::orderBy('TbAplicacionID', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Aplicaciones',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $aplicationAll->links(),
                    'prev' => $aplicationAll->setPath("{$this->route}/save")->previousPageUrl(),
                    'next' => $aplicationAll->setPath("{$this->route}/save")->nextPageUrl(),
                    'current' => $aplicationAll->currentPage(),
                    'first' => $aplicationAll->firstItem(),
                    'last' => $aplicationAll->lastPage(),
                    'total' => $aplicationAll->count()
                ],
            ],

            'aplications' => [
                'all' => $aplicationAll
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
    public function showUpdate(Request $request, $id)
    {
        $route = "{$this->route}";
        $navigationFrom = "info";
        $search = '';
        $filter = '';
        $hasSearch = false;
        $hasFilter = false;
        $arancel = null;
        $queryString = ($request->has('page')) ? "?page={$request->page}" : '';

        // Url has param 'q' from search
        if ($request->has('q')) {
            
            $search = $request->q;
            $hasSearch = true;
            $route = "{$this->route}/search?q={$search}";
            $navigationFrom = "search";
            $queryString = ($request->has('page')) ? "?page={$request->page}&q={$search}" : "?q={$search}";

            $itemsAll = Item::where('EmpresaAlias', 'LIKE', "%{$search}%")->paginate($this->paginate);

        } else if ($request->has('by')) {
            
            $filter = $request->by;
            $hasFilter = true;
            $route = "{$this->route}/filter?by={$filter}";
            $navigationFrom = "filter";

            switch ($filter) {
                case 'active':
                    $filter = "Activos";
                    $itemsAll = Item::where('RegistroCerrado', '=', 0)->paginate($this->paginate);
                    break;

                case 'inactive':
                    $filter = "Inactivos";
                    $itemsAll = Item::where('RegistroCerrado', '=', 1)->paginate($this->paginate);
                    break;

                case 'deleted':
                    $filter = "Eliminados";
                    $itemsAll = Item::onlyTrashed()->paginate($this->paginate);
                    break;
                
                default:
                    break;
            }

            $queryString = ($request->has('page')) ? "?page={$request->page}&by={$request->by}" : "?by={$request->by}";
        }
        $itemsAll = Item::paginate($this->paginate);
        $items = Item::find($id);

        $viewData = [
            'page' => [
                'title' => 'Rubros / Editar',
            ],

            'navigation' => [
                'base' => $this->route,
                'from' => $navigationFrom,
                'pagination' => $itemsAll->setPath($route)->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
                'filter' => $hasFilter,
                'query_string' => $queryString
            ],

            'search' => [
                'query' => $search,
            ],

            'items' => [
                'all' => $itemsAll,
                'one' => $items,
            ],

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
        ];
   
        $messages = [
            'cto34_item.required' => 'Rubro requerido.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $items = new Item();
        $items->setConnection('mysql-writer');

        $items->tbCTOUsuarioGrupoID_RegistroCerro = \Auth::id();
        $items->RubroAlias = $request->input('cto34_item');
        $items->RubroGastoTipo = $request->input('cto34_type');
        $items->RubroGastoClase = $request->input('cto34_class');
        $items->RubroDescripcion = $request->input('cto34_description');


        if($request->input('cto34_status') == 0){
            $items->RegistroCerrado = $request->input('cto34_status');
        }else{
            $items->RegistroCerrado = $request->input('cto34_status');
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
        $page = $request->input('_page');
        $redirect = URL::previous();

        $rules = [
            'cto34_item' => 'required'
        ];
   
        $messages = [
            'cto34_item.required' => 'Rubro requerido.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }


        $items = Item::find($id)->setConnection('mysql-writer');

        $items->tbCTOUsuarioGrupoID_RegistroCerro = \Auth::id();
        $items->RubroAlias = $request->input('cto34_item');
        $items->RubroGastoTipo = $request->input('cto34_type');
        $items->RubroGastoClase = $request->input('cto34_class');
        $items->RubroDescripcion = $request->input('cto34_description');


        if($request->input('cto34_status') == 0){
            $items->RegistroCerrado = $request->input('cto34_status');
        }else{
            $items->RegistroCerrado = $request->input('cto34_status');
        }
        
     
        if (!$items->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar el rubro, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Rubro actualizado correctamente.');
        
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
            'cto34_id.required' => 'Id de aplicación requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $aplication = Aplication::find($id)->setConnection('mysql-writer');

        if (!$aplication->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar la aplicación, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Aplicación <b>'.$aplication->AplicacionAlias.'</b> eliminado correctamente.');
        
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
            'cto34_id.required' => 'Id de aplicación requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $aplication = Aplication::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$aplication->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar la aplicación, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Aplicación restaurado correctamente.');
    }
}
<?php
namespace App\Http\Controllers\Panel\System;
use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Unity;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnityController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.unity.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/unities');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {

        $unitiesAll = Unity::orderBy('tbUnidadID', 'DESC')
                            ->paginate($this->paginate);
        $unity = $unitiesAll[0];


        if ($id != null){
            $unity = Unity::find($id);
        }

        $viewData = [
            'page' => [
                'title' => "Sistema / Unidades",
            ],

            'navigation' => [
                'base' => "{$this->route}",
                'section' => '/info',
                'pagination' => [
                    'links' => $unitiesAll->links(),
                    'prev' => $unitiesAll->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $unitiesAll->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $unitiesAll->currentPage(),
                    'first' => $unitiesAll->firstItem(),
                    'last' => $unitiesAll->lastPage(),
                    'total' => $unitiesAll->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'coordinations',
                    'child' => 'daily'
                ],
            ],

            'unities' => [
                'all' => $unitiesAll,
                'one' => $unity
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
        $unitiesAll = null;
        $unity = null;
        $redirect = "{$this->route}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;

        if (!$request->has('filter')) {
            if (!$request->has('q') || empty($request->q)) {
                return redirect($redirect)
                    ->withErrors(['Se debe ingresar una busqueda'])
                    ->withInput();
            }
        }

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', UnidadAlias,UnidadNombre,UnidadNombrePlural,UnidadTipo,UnidadDescripcion)"), 'LIKE', "{$search}"
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

        $unitiesAll = Unity::orderBy('tbUnidadID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $unitiesAll = Unity::onlyTrashed()
                ->orderBy('tbUnidadID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($unitiesAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $unity = $unitiesAll[0];

        if ($id != null) {

            $unity = Unity::find($id);

            if ($hasDelete)
                $unity = Unity::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Unidades',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $unitiesAll->links(),
                    'prev' => $unitiesAll->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $unitiesAll->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $unitiesAll->currentPage(),
                    'first' => $unitiesAll->firstItem(),
                    'last' => $unitiesAll->lastPage(),
                    'total' => $unitiesAll->count()
                ],
            ],

            'unities' => [
                'all' => $unitiesAll,
                'one' => $unity
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $unitiesAll->count(),
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

        $unitiesAll = Unity::orderBy('tbUnidadID', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Unidades',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $unitiesAll->links(),
                    'prev' => $unitiesAll->setPath("{$this->route}/save")->previousPageUrl(),
                    'next' => $unitiesAll->setPath("{$this->route}/save")->nextPageUrl(),
                    'current' => $unitiesAll->currentPage(),
                    'first' => $unitiesAll->firstItem(),
                    'last' => $unitiesAll->lastPage(),
                    'total' => $unitiesAll->count()
                ],
            ],

            'unities' => [
                'all' => $unitiesAll
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
        $unitiesAll = null;
        $unity = null;

        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', UnidadAlias,UnidadNombre,UnidadNombrePlural,UnidadTipo,UnidadDescripcion)"), 'LIKE', "{$search}"
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

        $unitiesAll = Unity::orderBy('tbUnidadID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        $unity = $unitiesAll[0];

        if ($id != null)
            $unity = Unity::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Unidades',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $unitiesAll->links(),
                    'prev' => $unitiesAll->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $unitiesAll->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $unitiesAll->currentPage(),
                    'first' => $unitiesAll->firstItem(),
                    'last' => $unitiesAll->lastPage(),
                    'total' => $unitiesAll->count()
                ],
            ],

            'unities' => [
                'all' => $unitiesAll,
                'one' => $unity
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $unitiesAll->count(),
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
            'cto34_unity' => 'required',
            'cto34_type' => 'required',
            'cto34_name' => 'required',
            'cto34_names' => 'required',
            'cto34_description' => 'required',
        ];
   
        $messages = [
            'cto34_unity.required' => 'Unidad requerida.',
            'cto34_type.required' => 'Tipo requerido.',
            'cto34_name.required' => 'Nombre requerido.',
            'cto34_names.required' => 'Nombre plural requerido.',
            'cto34_description.required' => 'Descripción requerida.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $unity = new Unity();
        $unity->setConnection('mysql-writer');

       
        $unity->UnidadAlias = $request->input('cto34_unity');
        $unity->UnidadNombre = $request->input('cto34_name');
        $unity->UnidadNombrePlural = $request->input('cto34_names');
        $unity->UnidadTipo = $request->input('cto34_type');
        $unity->UnidadDescripcion = $request->input('cto34_description');
        //$unity->RegistroCerrado = $request->input('cto34_status');
        $unity->created_at = date('Y-m-d H:i');

        if( $request->input('cto34_closed') == 1 ){
            $unity->RegistroCerrado = 1;
            $unity->tbCTOUsuarioID_Rol = auth_permissions_id(Auth::user()['role']);
        }


        if (!$unity->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar la unidad, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Unidad agregada correctamente.');
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
            'cto34_unity' => 'required',
        ];
   
        $messages = [
            'cto34_unity.required' => 'Unidad requerida.',

        ];


        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }

        $unity = Unity::find($id)->setConnection('mysql-writer');
       
        $unity->UnidadAlias = $request->input('cto34_unity');
        $unity->UnidadNombre = $request->input('cto34_name');
        $unity->UnidadNombrePlural = $request->input('cto34_names');
        $unity->UnidadTipo = $request->input('cto34_type');
        $unity->UnidadDescripcion = $request->input('cto34_description');
        $unity->RegistroCerrado = $request->input('cto34_status');
        $unity->updated_at = date('Y-m-d H:i');


        if (!$unity->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puedo actualizar la unidad, intente nuevamente'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Unidad actualizada correctamente');
        
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
            'cto34_id.required' => 'Id unidad requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $unity = Unity::find($id)->setConnection('mysql-writer');

        if (!$unity->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar la unidad, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Unidad <b>'.$unity->UnidadNombre.'</b> eliminado correctamente.');
        
    }

    /**
     * Do Restore
     * Realiza la acción de restaurar un registro eliminado
     *
     * @param $request Request
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
            'cto34_id.required' => 'Id de la unidad requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $dailywork = Unity::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$dailywork->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar la unidad, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Unidad restaurada correctamente.');


    }
}
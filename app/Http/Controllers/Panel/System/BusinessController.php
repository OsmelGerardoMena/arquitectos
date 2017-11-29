<?php
namespace App\Http\Controllers\Panel\System;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Business;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.business.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/business');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request)
    {
        $business = Business::orderBy('tbDirEmpresaID')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => "Empresas",
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $business->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'type' => 'records'
            ],

            'business' => [
                'all' => $business,
            ],
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Info user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showInfo(Request $request, $id)
    {
        $businessAll = Business::orderBy('tbDirEmpresaID')->paginate($this->paginate);
        $business = Business::find($id);

        $viewData = [
            'page' => [
                'title' => "Empresas",
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $businessAll->setPath("{$this->route}")->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'business' => [
                'all' => $businessAll,
                'one' => $business,
            ],
        ];

        return view($this->viewsPath.'info', $viewData);
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showSearch(Request $request)
    {
        $redirect = "{$this->route}";
        $queries = (array) $request->query();
        $filters = (array) [];

        // Redireccionamos con error si el parametro de busqueda no existe o esta vacio
        if (!$request->has('q') || empty($request->q)) {
            return redirect($redirect)
                        ->withErrors(['Se debe ingresar un dato para la busqueda'])
                        ->withInput();
        }

        // Verificamos si el parametro contiene comodines
        $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

        // Creamos el primer filtro de busqueda concatenando campos
        $filters[] = [
            DB::raw("CONCAT_WS(' ', EmpresaRazonSocial, EmpresaNombreComercial, EmpresaAlias, EmpresaRFC, EmpresaDependencia , EmpresaEspecialidad)"), 'LIKE', "{$search}"
        ];

        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {
                case 'active':
                    $filters[] = [
                        'RegistroInactivo', '=', 0
                    ];
                    break;

                case 'inactive':
                    $filters[] = [
                        'RegistroInactivo', '=', 1
                    ];
                    break;

                case 'deleted':
                    $filters[] = [
                        'deleted_at', '!=', 'NULL'
                    ];
                    break;

                default:
                    break;
            }

        }

        if ($request->has('type') && !empty($request->type)) {
            $filters[] = [
                'EmpresaTipoPersona', '=', $request->type
            ];
        }

        if ($request->has('sector') && !empty($request->sector)) {
            $filters[] = [
                'EmpresaSector', '=', $request->sector
            ];
        }

        $businessAll = Business::Where($filters)->paginate($this->paginate);

        if ($businessAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$search.'</b>');
        }

        $viewData = [
            'page' => [
                'title' => 'Empresas / Busqueda',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $businessAll->appends([$queries])->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'business' => [
                'all' => $businessAll,
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $businessAll->count(),
                'query' => $request->q,
            ]
        ];

        return view($this->viewsPath.'search', $viewData);
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showSearchInfo(Request $request, $id)
    {

        $redirect = URL::previous();
        $search = $request->q;
        $queries = (array) $request->query();
        $filters = (array) [];

        if (!$request->has('q') || empty($request->q)) {
            return redirect($redirect)
                ->withErrors(['Se debe ingresar un dato para la busqueda'])
                ->withInput();
        }

        $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

        $filters[] = [
            DB::raw("CONCAT_WS(' ', EmpresaRazonSocial, EmpresaNombreComercial, EmpresaAlias, EmpresaRFC, EmpresaDependencia , EmpresaEspecialidad)"), 'LIKE', "{$search}"
        ];

        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {
                case 'active':
                    $filters[] = [
                        'RegistroInactivo', '=', 0
                    ];
                    break;

                case 'inactive':
                    $filters[] = [
                        'RegistroInactivo', '=', 1
                    ];
                    break;

                case 'deleted':
                    $filters[] = [
                        'deleted_at', '!=', 'NULL'
                    ];
                    break;

                default:
                    break;
            }

        }

        if ($request->has('type') && !empty($request->type)) {
            $filters[] = [
                'EmpresaTipoPersona', '=', $request->type
            ];
        }

        if ($request->has('sector') && !empty($request->sector)) {
            $filters[] = [
                'EmpresaSector', '=', $request->sector
            ];
        }


        $businessAll = Business::Where($filters)->paginate($this->paginate);
        $business = Business::find($id);

        $viewData = [
            'page' => [
                'title' => 'Empresas / Busqueda',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $businessAll->setPath("{$this->route}/search")->appends(['q' => $search])->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'business' => [
                'all' => $businessAll,
                'one' => $business,
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $businessAll->count(),
                'query' => $request->q,
            ]
        ];

        return view($this->viewsPath.'search-info', $viewData);
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showFilter(Request $request)
    {
        $redirect = URL::previous();
        $users = null;
        $search = '';
        $hasSearch = false;
        $filters = (array) [];

        if (empty($request->status) && empty($request->type) && empty($request->sector)) {
            return redirect($redirect)
                        ->withErrors(['Se debe elegir un filtro'])
                        ->withInput();
        }

        $queries = $request->query();

        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {
                case 'active':
                    $filters[] = [
                        'RegistroInactivo', '=', 0
                    ];
                    break;

                case 'inactive':
                    $filters[] = [
                        'RegistroInactivo', '=', 1
                    ];
                    break;

                case 'deleted':
                    $filters[] = [
                        'deleted_at', '!=', 'NULL'
                    ];
                    break;

                default:
                    break;
            }

        }

        if ($request->has('type') && !empty($request->type)) {
            $filters[] = [
                'EmpresaTipoPersona', '=', $request->type
            ];
        }

        if ($request->has('sector') && !empty($request->sector)) {
            $filters[] = [
                'EmpresaSector', '=', $request->sector
            ];
        }

        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $filters[] = [
                DB::raw("CONCAT_WS(' ', EmpresaAlias, EmpresaRazonSocial, EmpresaRFC, EmpresaEspecialidad)"), 'LIKE', "%{$request->q}%"
            ];
        }

        $businessAll = Business::where($filters)->paginate($this->paginate);

        if ($businessAll->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados.');
        }

        $viewData = [
            'page' => [
                'title' => "Empresas / Filtrar",
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $businessAll->appends($queries)->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
            ],

            'business' => [
                'all' => $businessAll,
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ], 

            'search' => [
                'count' => $businessAll->count(),
                'query' => $search,
            ]
        ];

        return view($this->viewsPath.'filter', $viewData);
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showFilterInfo(Request $request, $id)
    {
        $redirect = URL::previous();
        $business = Business::where('tbDirEmpresaID', $id)
            ->first();
        $users = null;
        $search = '';
        $hasSearch = false;
        $filters = (array) [];

        if (empty($request->status) && empty($request->type) && empty($request->sector)) {
            return redirect($redirect)
                ->withErrors(['Se debe elegir un filtro'])
                ->withInput();
        }

        $queries = $request->query();

        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {
                case 'active':
                    $filters[] = [
                        'RegistroInactivo', '=', 0
                    ];
                    break;

                case 'inactive':
                    $filters[] = [
                        'RegistroInactivo', '=', 1
                    ];
                    break;

                case 'deleted':
                    $filters[] = [
                        'deleted_at', '!=', 'NULL'
                    ];
                    break;

                default:
                    break;
            }

        }

        if ($request->has('type') && !empty($request->type)) {
            $filters[] = [
                'EmpresaTipoPersona', '=', $request->type
            ];
        }

        if ($request->has('sector') && !empty($request->sector)) {
            $filters[] = [
                'EmpresaSector', '=', $request->sector
            ];
        }

        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $filters[] = [
                DB::raw("CONCAT_WS(' ', EmpresaAlias, EmpresaRazonSocial, EmpresaRFC, EmpresaEspecialidad)"), 'LIKE', "%{$request->q}%"
            ];
        }

        $businessAll = Business::where($filters)->paginate($this->paginate);

        if ($businessAll->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados.');
        }

        $viewData = [
            'page' => [
                'title' => "Empresas / Filtrar",
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $businessAll->setPath("{$this->route}/filter")->appends($queries)->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
            ],

            'business' => [
                'all' => $businessAll,
                'one' => $business
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $businessAll->count(),
                'query' => $search,
            ]
        ];

        return view($this->viewsPath.'filter-info', $viewData);
    }

    /**
     * Update user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showSave(Request $request)
    {
        $business = Business::orderBy('tbDirEmpresaID')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => "Empresas / Nueva",
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $business->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'business' => [
                'all' => $business,
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
        $business = null;
        $queryString = ($request->has('page')) ? "?page={$request->page}" : '';

        // Url has param 'q' from search
        if ($request->has('q')) {
            
            $search = $request->q;
            $hasSearch = true;
            $route = "{$this->route}/search?q={$search}";
            $navigationFrom = "search";
            $queryString = ($request->has('page')) ? "?page={$request->page}&q={$search}" : "?q={$search}";

            $businessAll = Business::where('EmpresaAlias', 'LIKE', "%{$search}%")->paginate($this->paginate);

        } else if ($request->has('by')) {
            
            $filter = $request->by;
            $hasFilter = true;
            $route = "{$this->route}/filter?by={$filter}";
            $navigationFrom = "filter";

            switch ($filter) {
                case 'active':
                    $filter = "Activos";
                    $businessAll = Business::where('RegistroInactivo', '=', 0)->paginate($this->paginate);
                    break;

                case 'inactive':
                    $filter = "Inactivos";
                    $businessAll = Business::where('RegistroInactivo', '=', 1)->paginate($this->paginate);
                    break;

                case 'deleted':
                    $filter = "Eliminados";
                    $businessAll = Business::onlyTrashed()->paginate($this->paginate);
                    break;
                
                default:
                    break;
            }

            $queryString = ($request->has('page')) ? "?page={$request->page}&by={$request->by}" : "?by={$request->by}";
        }
        $businessAll = Business::paginate($this->paginate);
        $business = Business::find($id);

        $viewData = [
            'page' => [
                'title' => 'Empresas / Editar',
            ],

            'navigation' => [
                'base' => $this->route,
                'from' => $navigationFrom,
                'pagination' => $businessAll->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
                'filter' => $hasFilter,
                'query_string' => $queryString
            ],

            'search' => [
                'query' => $search,
            ],

            'business' => [
                'all' => $businessAll,
                'one' => $business,
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
            'cto34_alias' => 'required',
        ];
   
        $messages = [
            'cto34_alias.required' => 'Alias requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {

            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $business = new Business();
        $business->setConnection('mysql-writer');

        $business->EmpresaAlias = $request->input('cto34_alias');
        $business->EmpresaRazonSocial = $request->input('cto34_legalName');
        $business->EmpresaNombreComercial = $request->input('cto34_commercialName');
        $business->EmpresaDependencia = $request->input('cto34_dependency');
        $business->EmpresaEspecialidad = $request->input('cto34_especiality');
        $business->EmpresaTipoPersona = $request->input('cto34_type');
        $business->EmpresaSlogan = $request->input('cto34_slogan');
        $business->EmpresaPaginaWeb = $request->input('cto34_website');
        $business->EmpresaRFC = $request->input('cto34_legalId');
        $business->EmpresaIMSSNumero = $request->input('cto34_imssNum');
        $business->EmpresaINFONAVITNumero = $request->input('cto34_infonavitNum');
        $business->EmpresaSector = $request->input('cto34_sector');

        if (!$business->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar la empresa, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Empresa agregada correctamente.');
    }

    /**
     * Update person
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function doUpdate(Request $request) {

        $id = $request->input('cto34_id');
        $page = $request->input('_page');
        $redirect = URL::previous();

        $rules = [
            'cto34_legalName' => 'required',
        ];
   
        $messages = [
            'cto34_legalName.required' => 'Nombre requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $business = Business::find($id)->setConnection('mysql-writer');

        $business->EmpresaAlias = $request->input('cto34_alias');
        $business->EmpresaRazonSocial = $request->input('cto34_legalName');
        $business->EmpresaNombreComercial = $request->input('cto34_commercialName');
        $business->EmpresaDependencia = $request->input('cto34_dependency');
        $business->EmpresaEspecialidad = $request->input('cto34_especiality');
        $business->EmpresaTipoPersona = $request->input('cto34_type');
        $business->EmpresaSlogan = $request->input('cto34_slogan');
        $business->EmpresaPaginaWeb = $request->input('cto34_website');
        $business->EmpresaRFC = $request->input('cto34_legalId');
        $business->EmpresaIMSSNumero = $request->input('cto34_imssNum');
        $business->EmpresaINFONAVITNumero = $request->input('cto34_infonavitNum');
        $business->EmpresaSector = $request->input('cto34_sector');

        if (!$business->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar la empresa, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Empresa actualizada correctamente.');
        
    }
    /*public function doUpdate(Request $request) {

        $id = $request->input('cto34_id');
        $page = $request->input('_page');
        $redirect = URL::previous();
        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Registro actualizado correctamente.',
            'data' => [],
        ];

        $rules = [
            'cto34_alias' => 'required',
            'cto34_legalName' => 'required',
        ];

        $messages = [
            'cto34_alias.required' => 'Alias requerido.',
            'cto34_legalName.required' => 'Nombre requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => 'Error de validación.',
                'data' => [],
                'errors' => [
                    'all' => $errors,
                    'first' => $errors->first()
                ]
            ];

        } else {

            $business = Business::find($id)->setConnection('mysql-writer');

            $business->EmpresaAlias = $request->input('cto34_alias');
            $business->EmpresaRazonSocial = $request->input('cto34_legalName');
            $business->EmpresaNombreComercial = $request->input('cto34_commercialName');
            $business->EmpresaDependencia = $request->input('cto34_dependency');
            $business->EmpresaEspecialidad = $request->input('cto34_especiality');
            $business->EmpresaTipoPersona = $request->input('cto34_type');
            $business->EmpresaSlogan = $request->input('cto34_slogan');
            $business->EmpresaPaginaWeb = $request->input('cto34_website');
            $business->EmpresaRFC = $request->input('cto34_legalId');
            $business->EmpresaIMSSNumero = $request->input('cto34_imssNum');
            $business->EmpresaINFONAVITNumero = $request->input('cto34_infonavitNum');
            $business->EmpresaSector = $request->input('cto34_sector');

            if (!$business->save()) {

                $responseJson = [
                    'status' => false,
                    'code' => 203,
                    'message' => 'No se puede guardar la empresa, intente nuevamente',
                    'data' => []
                ];
            }
        }

        return response()->json($responseJson);

    }*/

    /**
     * Delete person
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doDelete(Request $request) {

        $id = $request->input('cto34_id');
        $redirect = "{$this->route}";

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

        $business = Business::find($id)->setConnection('mysql-writer');

        if (!$business->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar la empresa, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Empresa <b>'.$business->EmpresaAlias.'</b> eliminada correctamente.');
        
    }
}
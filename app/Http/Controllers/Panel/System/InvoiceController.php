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

class InvoiceController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.invoice.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/invoices');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request)
    {
        $business = Business::orderBy('created_at', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => "Empresas",
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

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Info user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showInfo(Request $request, $id)
    {
        $businessAll = Business::orderBy('created_at', 'DESC')->paginate($this->paginate);
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

        if (!$request->has('q') || empty($request->q)) {
            return redirect($redirect)
                        ->withErrors(['Se debe ingresar un dato para la busqueda'])
                        ->withInput();
        }

        $search = $request->q;
        $businessAll = Business::where('EmpresaAlias', 'LIKE', "%{$search}%")->paginate($this->paginate);

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
                'pagination' => $businessAll->appends(['q' => $search])->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'business' => [
                'all' => $businessAll,
            ],

            'search' => [
                'count' => $businessAll->count(),
                'query' => $search,
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

        $search = $request->q;
        $businessAll = Business::where('EmpresaAlias', 'LIKE', "%{$search}%")->paginate($this->paginate);
        $business = Business::find($id);

        $viewData = [
            'page' => [
                'title' => 'Empresas / Busqueda',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $businessAll->setPath("{$this->route}/search/info/{$id}")->appends(['q' => $search])->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'business' => [
                'all' => $businessAll,
                'one' => $business,
            ],

            'search' => [
                'count' => $businessAll->count(),
                'query' => $search,
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
        $redirect = "{$this->route}";
        $users = null;
        $search = '';
        $hasSearch = false;

        if (!$request->has('by') || empty($request->by)) {
            return redirect($redirect)
                        ->withErrors(['Se debe elegir un filtro'])
                        ->withInput();
        }

        if ($request->has('q')) {
            $hasSearch = true;
        }

        $filter = $request->by;

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

        if ($businessAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu filtro: '.$filter);
        }

        $viewData = [
            'page' => [
                'title' => "Empresas / Filtrar / {$filter}",
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $businessAll->appends(['by' => $filter])->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
            ],

            'business' => [
                'all' => $businessAll,
            ],

            'filter' => [
                'by' => $filter,
                'query' => $request->by,
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
        $redirect = "{$this->route}";
        $users = null;
        $search = '';
        $hasSearch = false;

        if (!$request->has('by') || empty($request->by)) {
            return redirect($redirect)
                        ->withErrors(['Se debe elegir un filtro'])
                        ->withInput();
        }

        if ($request->has('q')) {
            $hasSearch = true;
        }

        $filter = $request->by;

        $business = Business::onlyTrashed()
            ->where('tbDirEmpresaID', $id)
            ->first();

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

        if ($businessAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu filtro: '.$filter);
        }

        $viewData = [
            'page' => [
                'title' => "Empresas / Filtrar / {$filter}",
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $businessAll->appends(['by' => $filter])->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
            ],

            'business' => [
                'all' => $businessAll,
                'one' => $business,
            ],

            'filter' => [
                'by' => $filter,
                'query' => $request->by,
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
        $business = Business::orderBy('created_at', 'DESC')->paginate($this->paginate);

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
        $business->EmpresaComentarios = $request->input('cto34_comments');

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
     * @param Request requuest
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
        $business->EmpresaComentarios = $request->input('cto34_comments');

        if (!$business->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar la empresa, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Empresa actualizada correctamente.');
        
    }

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
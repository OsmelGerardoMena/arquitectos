<?php
namespace App\Http\Controllers\Panel\System;
use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\MyBusiness;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyBusinessController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.mybusiness.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/myBusiness');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request)
    {

        $myBusiness = MyBusiness::paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Mis empresas',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $myBusiness->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'mybusiness' => [
                'all' => $myBusiness,
                'one' => $myBusiness,
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
        $myBusinessAll = MyBusiness::paginate($this->paginate);
        $myBusiness = MyBusiness::find($id);

        $viewData = [
            'page' => [
                'title' => 'Mis Empresas',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $myBusinessAll->setPath("{$this->route}")->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'mybusiness' => [
                'all' => $myBusinessAll,
                'one' => $myBusiness,
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
                'title' => 'Clientes / Busqueda',
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
        $redirect = "{$this->route}";

        if (!$request->has('q') || empty($request->q)) {
            return redirect($redirect)
                        ->withErrors(['Debe ingresar un nombre de empresa'])
                        ->withInput();
        }

        $search = $request->q;
        $businessAll = Business::where('EmpresaAlias', 'LIKE', "%{$search}%")->paginate($this->paginate);
        $business = Business::find($id);

        $viewData = [
            'page' => [
                'title' => 'Clientes / Busqueda',
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
                'title' => 'Clientes / Filtrar',
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

        $business = Business::find($id);

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
                'title' => 'Clientes / Filtrar',
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
    public function showSave()
    {

        $viewData = [
            'page' => [
                'title' => 'Nueva empresa',
            ],

            'navigation' => [
                'base' => $this->route,
            ],

            'views' => [
                'shared' => $this->viewsPath.'shared',
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

            $myBusinessAll = Arancel::where('EmpresaAlias', 'LIKE', "%{$search}%")->paginate($this->paginate);

        } else if ($request->has('by')) {
            
            $filter = $request->by;
            $hasFilter = true;
            $route = "{$this->route}/filter?by={$filter}";
            $navigationFrom = "filter";

            switch ($filter) {
                case 'active':
                    $filter = "Activos";
                    $myBusinessAll = Arancel::where('RegistroCerrado', '=', 0)->paginate($this->paginate);
                    break;

                case 'inactive':
                    $filter = "Inactivos";
                    $myBusinessAll = Arancel::where('RegistroCerrado', '=', 1)->paginate($this->paginate);
                    break;

                case 'deleted':
                    $filter = "Eliminados";
                    $myBusinessAll = Arancel::onlyTrashed()->paginate($this->paginate);
                    break;
                
                default:
                    break;
            }

            $queryString = ($request->has('page')) ? "?page={$request->page}&by={$request->by}" : "?by={$request->by}";
        }
        $myBusinessAll = MyBusiness::paginate($this->paginate);
        $myBusiness = MyBusiness::find($id);

        $viewData = [
            'page' => [
                'title' => 'Mis empresas / Editar',
            ],

            'navigation' => [
                'base' => $this->route,
                'from' => $navigationFrom,
                'pagination' => $myBusinessAll->setPath($route)->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
                'filter' => $hasFilter,
                'query_string' => $queryString
            ],

            'search' => [
                'query' => $search,
            ],

            'mybusiness' => [
                'all' => $myBusinessAll,
                'one' => $myBusiness,
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
            'cto34_nickname' => 'required',
        ];
   
        $messages = [
            'cto34_nickname.required' => 'Alias de empresa requerido.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $mybusiness = new MyBusiness();
        $mybusiness->setConnection('mysql-writer');

       
        $mybusiness->miEmpresaAlias = $request->input('cto34_nickname');
        $mybusiness->TbDirEmrpresaID_miEmpresa = $request->input('cto34_id_dir');
        $mybusiness->miEmpresaFechaConstitucion = $request->input('cto34_constitution_date');
        $mybusiness->miEmpresaFacturaLugarDeExpedicion = $request->input('cto34_place');
        $mybusiness->miEmpresaRepresentanteLegalCargo = $request->input('cto34_cargo');
        $mybusiness->miEmpresaRepresentanteLegal2Cargo = $request->input('cto34_cargo2');
        $mybusiness->miEmpresaSlogan = $request->input('cto34_slogan');
        $mybusiness->miEmpresaLogotipo = $request->input('cto34_logo');
        $mybusiness->miEmpresaObjetoSocial = $request->input('cto34_object_social_1');
        $mybusiness->miEmpresaEscrituraConstitutivaNo = $request->input('cto34_legal_no');
        $mybusiness->miEmpresaEscrituraConstitutivaFecha = $request->input('cto34_legal_date');
        $mybusiness->miEmpresaEscrituraConstitutivaNotaria  = $request->input('cto34_legal_f');
        $mybusiness->miEmpresaEscrituraConstitutivaNotario = $request->input('cto34_legal_m');
        $mybusiness->miEmpresaObjetoSocial1 = $request->input('cto34_object_social_2');
        $mybusiness->miEmpresaObjetoSocial2 = $request->input('cto34_object_social_3');
        $mybusiness->miEmpresaSociosYpct = $request->input('cto34_partners');
        $mybusiness->miEmpresaActaConstitutivaLibro = $request->input('cto34_book');
        $mybusiness->miEmpresaRegistroPatronalIMSS = $request->input('cto34_imss');
        $mybusiness->miEmpresaEscrituraRppcNo = $request->input('cto34_no_rppc');
        $mybusiness->miEmpresaEscrituraRppcFecha = $request->input('cto34_date_rppc');
        $mybusiness->miEmpresaEscrituraRPPCFolio = $request->input('cto34_folio_rppc');
        $mybusiness->TbRegFiscalID_miEmpresa = $request->input('cto34_id_rf');
        $mybusiness->TbDirPersonaID_RepresentanteLegal1 = $request->input('cto34_id_representative_1');
        $mybusiness->TbDirPersonaID_RepresentanteLegal2 = $request->input('cto34_id_representative_2');
        $mybusiness->TbDirPersonaID_Accionista1 = $request->input('cto34_shareholder_1');
        $mybusiness->TbDirPersonaID_Accionista2 = $request->input('cto34_shareholder_2');
        $mybusiness->tbCTOAmbito_miEmpresa = $request->input('cto34_cto');

        if($request->input('cto34_status') == 0){
            $mybusiness->RegistroInactivo = $request->input('cto34_status');
        }else{
            $mybusiness->RegistroCerrado = $request->input('cto34_status');
        }
        



        if (!$mybusiness->save()) {
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
            'cto34_nickname' => 'required'
        ];
   
        $messages = [
            'cto34_nickname.required' => 'Empresa alias requerido.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }


        $mybusiness = MyBusiness::find($id)->setConnection('mysql-writer');

        $mybusiness->miEmpresaAlias = $request->input('cto34_nickname');
        $mybusiness->TbDirEmrpresaID_miEmpresa = $request->input('cto34_id_dir');
        $mybusiness->miEmpresaFechaConstitucion = $request->input('cto34_constitution_date');
        $mybusiness->miEmpresaFacturaLugarDeExpedicion = $request->input('cto34_place');
        $mybusiness->miEmpresaRepresentanteLegalCargo = $request->input('cto34_cargo');
        $mybusiness->miEmpresaRepresentanteLegal2Cargo = $request->input('cto34_cargo2');
        $mybusiness->miEmpresaSlogan = $request->input('cto34_slogan');
        $mybusiness->miEmpresaLogotipo = $request->input('cto34_logo');
        $mybusiness->miEmpresaObjetoSocial = $request->input('cto34_object_social_1');
        $mybusiness->miEmpresaEscrituraConstitutivaNo = $request->input('cto34_legal_no');
        $mybusiness->miEmpresaEscrituraConstitutivaFecha = $request->input('cto34_legal_date');
        $mybusiness->miEmpresaEscrituraConstitutivaNotaria  = $request->input('cto34_legal_f');
        $mybusiness->miEmpresaEscrituraConstitutivaNotario = $request->input('cto34_legal_m');
        $mybusiness->miEmpresaObjetoSocial1 = $request->input('cto34_object_social_2');
        $mybusiness->miEmpresaObjetoSocial2 = $request->input('cto34_object_social_3');
        $mybusiness->miEmpresaSociosYpct = $request->input('cto34_partners');
        $mybusiness->miEmpresaActaConstitutivaLibro = $request->input('cto34_book');
        $mybusiness->miEmpresaRegistroPatronalIMSS = $request->input('cto34_imss');
        $mybusiness->miEmpresaEscrituraRppcNo = $request->input('cto34_no_rppc');
        $mybusiness->miEmpresaEscrituraRppcFecha = $request->input('cto34_date_rppc');
        $mybusiness->miEmpresaEscrituraRPPCFolio = $request->input('cto34_folio_rppc');
        $mybusiness->TbRegFiscalID_miEmpresa = $request->input('cto34_id_rf');
        $mybusiness->TbDirPersonaID_RepresentanteLegal1 = $request->input('cto34_id_representative_1');
        $mybusiness->TbDirPersonaID_RepresentanteLegal2 = $request->input('cto34_id_representative_2');
        $mybusiness->TbDirPersonaID_Accionista1 = $request->input('cto34_shareholder_1');
        $mybusiness->TbDirPersonaID_Accionista2 = $request->input('cto34_shareholder_2');
        $mybusiness->tbCTOAmbito_miEmpresa = $request->input('cto34_cto');

        if($request->input('cto34_status') == 0){
            $mybusiness->RegistroInactivo = $request->input('cto34_status');
        }else{
            $mybusiness->RegistroCerrado = $request->input('cto34_status');
        }
        
     
        if (!$mybusiness->save()) {
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
            'cto34_id.required' => 'Id de cliente requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $mybusiness = MyBusiness::find($id)->setConnection('mysql-writer');

        if (!$mybusiness->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar la empresa, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Empresa <b>'.$mybusiness->miEmpresaAlias.'</b> eliminado correctamente.');
        
    }
}
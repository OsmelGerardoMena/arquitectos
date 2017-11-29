<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Illuminate\Support\Facades\Bus;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\BusinessWork;
use App\Models\Business;
use App\Models\Group;
use App\Models\ConstructionWork;
use App\Models\PersonBusinessWork;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends AppController
{
    private $route;
    private $childRoute = "business";
    private $viewsPath = 'panel.constructionwork.business.';
    private $paginate = 25;
    private $table = 'tbDirEmpresaObra';

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/constructionwork');
    }

    /**
     * Index
     * Página principal de la sección
     *
     * @param $request Request
     * @param $workId int id de la obra
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $workId, $id = null)
    {
        $work = ConstructionWork::find($workId);
        $business= BusinessWork::orderBy('tbDirEmpresaObraID', 'DESC')->where('tbObraID_DirEmpresaObra', '=', $workId)->paginate($this->paginate);
        $businessOne = $business[0];

        if ($id != null)
            $businessOne = BusinessWork::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Empresas",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $business->links(),
                    'prev' => $business->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $business->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $business->currentPage(),
                    'first' => $business->firstItem(),
                    'last' => $business->lastPage(),
                    'total' => $business->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'directory',
                    'child' => 'business'
                ],
            ],

            'business' => [
                'all' => $business,
                'one' => $businessOne
            ],

            'works' => [
                'one' => $work,
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Show Search
     * Muestra los registros o el registro por busqueda
     *
     * @param $request Request
     * @param $workId int id de la obra
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showSearch(Request $request, $workId, $id = null)
    {
        $work = ConstructionWork::find($workId);
        $business = null;
        $businessOne = null;
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;
        $search = "";

        if (!$request->has('filter')) {

            if (!$request->has('q') || empty($request->q)) {
                return redirect($redirect)
                    ->withErrors(['Se debe ingresar un dato para la busqueda'])
                    ->withInput();
            }
        }

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%' . $request->q . '%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', DirEmpresaObraAlcance)"), 'LIKE', "{$search}"
            ];
        }

        // Verificamos si existe el filtro
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

        $business = BusinessWork::orderBy('tbDirEmpresaObraID', 'DESC')
            ->where('tbObraID_DirEmpresaObra', '=', $workId)
            ->where($filters)
            ->orWhere(function ($query) use ($workId, $search) {
                $query->where('tbObraID_DirEmpresaObra', '=', $workId)
                    ->whereHas('business', function ($query) use ($search) {
                        $query->where('EmpresaAlias', 'LIKE', $search);
                    });
            })
            ->paginate($this->paginate);

        if ($hasDelete) {

            $business = BusinessWork::onlyTrashed()
                ->orderBy('tbDirEmpresaObraID', 'DESC')
                ->where('tbObraID_DirEmpresaObra', '=', $workId)
                ->where($filters)
                ->orWhere(function ($query) use ($workId, $search) {
                    $query->where('tbObraID_DirEmpresaObra', '=', $workId)
                        ->whereHas('business', function ($query) use ($search) {
                            $query->where('EmpresaAlias', 'LIKE', $search);
                        });
                })
                ->paginate($this->paginate);
        }

        if ($business->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $businessOne = $business[0];

        if ($id != null) {

            $businessOne = BusinessWork::find($id);
            if ($hasDelete)
                $businessOne = BusinessWork::withTrashed()->find($id);
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Empresas",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $business->links(),
                    'prev' => $business->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->previousPageUrl(),
                    'next' => $business->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->nextPageUrl(),
                    'current' => $business->currentPage(),
                    'first' => $business->firstItem(),
                    'last' => $business->lastPage(),
                    'total' => $business->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'directory',
                    'child' => 'business'
                ],
            ],

            'business' => [
                'all' => $business,
                'one' => $businessOne
            ],

            'works' => [
                'one' => $work,
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $business->count(),
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
    public function showSave(Request $request, $workId) {

        $work = ConstructionWork::find($workId);
        $business= BusinessWork::orderBy('tbDirEmpresaObraID', 'DESC')->where('tbObraID_DirEmpresaObra', '=', $workId)->paginate($this->paginate);
        $groups = Group::all();
        $businessCategories = Business::categories();
        $typePerson = works_type_business_typeperson();
        $sector = works_type_business_sector();

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Empresas",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/save',
                'pagination' => [
                    'links' => $business->links(),
                    'prev' => $business->setPath("{$this->route}/{$workId}/{$this->childRoute}/save")->previousPageUrl(),
                    'next' => $business->setPath("{$this->route}/{$workId}/{$this->childRoute}/save")->nextPageUrl(),
                    'current' => $business->currentPage(),
                    'first' => $business->firstItem(),
                    'last' => $business->lastPage(),
                    'total' => $business->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'directory',
                    'child' => 'business'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'business' => [
                'all' => $business,
                'categories' => $businessCategories,
                'groups' => $groups
            ],

            'typePerson' =>  $typePerson,
            'sector' => $sector

        ];


        return view($this->viewsPath.'save', $viewData);
    }

    /**
     * Update user front
     *
     * @param int id - user id
     * @return \Illuminate\Http\Response
     */
    public function showUpdate(Request $request, $workId, $id)
    {
        $work = ConstructionWork::find($workId);
        $business = BusinessWork::where('tbDirEmpresaObraID', '=', $id)->with('business')->first();
        $businessOne = $business[0];
        $filters = [];
        $groups = Group::all();
        $search = '';
        $businessCategories = Business::categories();

        if ($id != null)
            $businessOne = BusinessWork::find($id);

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%' . $request->q . '%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', DirEmpresaObraAlcance)"), 'LIKE', "{$search}"
            ];
        }

        // Verificamos si existe el filtro
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
                    break;

                default:
                    break;
            }
        }

        $business = BusinessWork::orderBy('tbDirEmpresaObraID', 'DESC')
            ->where('tbObraID_DirEmpresaObra', '=', $workId)
            ->where($filters)
            ->orWhere(function ($query) use ($workId, $search) {
                $query->where('tbObraID_DirEmpresaObra', '=', $workId)
                    ->whereHas('business', function ($query) use ($search) {
                        $query->where('EmpresaAlias', 'LIKE', $search);
                    });
            })
            ->paginate($this->paginate);


        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Empresas",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/update',
                'pagination' => [
                    'links' => $business->links(),
                    'prev' => $business->setPath("{$this->route}/{$workId}/{$this->childRoute}/update")->previousPageUrl(),
                    'next' => $business->setPath("{$this->route}/{$workId}/{$this->childRoute}/update")->nextPageUrl(),
                    'current' => $business->currentPage(),
                    'first' => $business->firstItem(),
                    'last' => $business->lastPage(),
                    'total' => $business->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'directory',
                    'child' => 'business'
                ],
            ],

            'business' => [
                'all' => $business,
                'one' => $businessOne,
                'categories' => $businessCategories,
                'groups' => $groups
            ],

            'works' => [
                'one' => $work,
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];
   
        return view($this->viewsPath.'update', $viewData);
    }

    /**
     * Do Save
     * Realiza la acción para guardar un registro
     *
     * @return \Illuminate\Http\Response
     */
    public function doSave(Request $request) {

        $workId = $request->input('cto34_work');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $redirectError = "{$this->route}/{$workId}/{$this->childRoute}/save";

        $rules = [
            'cto34_business' => 'required',
            'cto34_scope' => 'required',
            'cto34_work' => 'required'
        ];

        $messages = [
            'cto34_business.required' => 'Empresa requerida.',
            'cto34_scope.required' => 'Alcance requerido.',
            'cto34_work.required' => 'Id de obra requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $businesswork = new BusinessWork();
        $businesswork->setConnection('mysql-writer');

        $businesswork->tbDirEmpresaID_DirEmpresaObra = $request->input('cto34_business');
        $businesswork->tbObraID_DirEmpresaObra = $request->input('cto34_work');
        $businesswork->tbDirGrupoID_DirEmpresaObra = !empty($request->input('cto34_groups')) ? $request->input('cto34_groups') : 0;
        $businesswork->DirEmpresaObraAlcance = $request->input('cto34_scope');
        $businesswork->created_at = date("d-m-Y H:i");

        if(!empty($request->input('cto34_close'))){
            $businesswork->RegistroCerrado = 1;
            $businesswork->RegistroRol = auth_permissions_id(Auth::user()['role']);
        }

        if (!$businesswork->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede guardar la Empresa Obra, intenta nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Empresa Obra agregada correctamente.');
    }

    /**
     * Do Update
     * Realiza la acción de actualizar un registro
     *
     * @return \Illuminate\Http\Response
     */
    public function doUpdate(Request $request) {

        $id = $request->input('cto34_id');
        $workId = $request->input('cto34_work');
        $hasSearch = $request->input('_hasSearch');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info/{$id}{$request->input('_query')}";
        $redirectError = URL::previous();

        if ($hasSearch)
            $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/search/{$id}{$request->input('_query')}";

        $rules = [
            'cto34_business' => 'required',
            'cto34_scope' => 'required',
            'cto34_work' => 'required'
        ];

        $messages = [
            'cto34_business.required' => 'Empresa requerida.',
            'cto34_scope.required' => 'Alcance requerido.',
            'cto34_work.required' => 'Id de obra requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $businesswork = BusinessWork::find($id)->setConnection('mysql-writer');

        $businesswork->tbDirEmpresaID_DirEmpresaObra = $request->input('cto34_business');
        $businesswork->tbObraID_DirEmpresaObra = $request->input('cto34_work');
        $businesswork->tbDirGrupoID_DirEmpresaObra = !empty($request->input('cto34_groups')) ? $request->input('cto34_groups') : 0;
        $businesswork->DirEmpresaObraAlcance = $request->input('cto34_scope');

        if(!empty($request->input('cto34_close'))){
            $businesswork->RegistroCerrado = 1;
            $businesswork->RegistroRol = auth_permissions_id(Auth::user()['role']);
        }

        if (!$businesswork->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede guardar la Empresa Obra, intenta nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Empresa actualizada correctamente.');

    }

    /**
     * Do Delete
     * Realiza la acción de eliminar un registro
     *
     * @param $request Request
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function doDelete(Request $request, $workId) {

        $id = $request->input('cto34_id');
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";

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

        $business = BusinessWork::find($id)->setConnection('mysql-writer');

        if (!$business->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar la empresa, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Empresa eliminada correctamente.');
    }

    /**
     * Do Restore
     * Realiza la acción de restaurar un registro eliminado
     *
     * @param $request Request
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function doRestore(Request $request, $workId) {

        $id = $request->input('cto34_id');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $redirectError = URL::previous();

        $rules = [
            'cto34_id' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de edificio requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $business = BusinessWork::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$business->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar la empresa, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Empresa restaurada correctamente.');
    }
}
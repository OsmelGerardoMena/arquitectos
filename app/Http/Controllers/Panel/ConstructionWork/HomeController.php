<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Validator;
use URL;
use Carbon\Carbon;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\ConstructionWork;
use App\Models\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends AppController
{
    private $route;
    private $viewsPath = 'panel.constructionwork.home.';
    private $paginate = 25;

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
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {
        $works = ConstructionWork::orderBy('tbObraID', 'DESC')
            ->where('RegistroInactivo', '=', 0)
            ->paginate($this->paginate);
        $work = $works[0];

        if ($id != null)
            $work = ConstructionWork::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave}",
            ],

            'navigation' => [
                'base' => "{$this->route}",
                'section' => '/home',
                'pagination' => [
                    'links' => $works->links(),
                    'prev' => $works->setPath("{$this->route}/home")->previousPageUrl(),
                    'next' => $works->setPath("{$this->route}/home")->nextPageUrl(),
                    'current' => $works->currentPage(),
                    'first' => $works->firstItem(),
                    'last' => $works->lastPage(),
                    'total' => $works->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => '',
                    'child' => 'home'
                ],
            ],

            'works' => [
                'all' => $works,
                'one' => $work
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Info
     * Muesta solamente la información de una sola obra
     *
     * @param $request Request
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showInfo(Request $request, $id)
    {
        $work = ConstructionWork::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave}",
            ],

            'navigation' => [
                'base' => $this->route,
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'works',
                    'child' => 'home'
                ]
            ],

            'works' => [
                'one' => $work,
            ],
        ];

        return view($this->viewsPath.'info', $viewData);
    }

    /**
     * Search
     * Muestra los registros o el registro por busqueda
     *
     * @param $request Request
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showSearch(Request $request, $id = null)
    {

        $works = null;
        $work = null;
        $redirect = "{$this->route}/home";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;

        if (!$request->has('filter')) {
            if (!$request->has('q') || empty($request->q)) {
                return redirect($redirect)
                    ->withErrors(['Se debe ingresar un dato para la busqueda'])
                    ->withInput();
            }
        }

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', ObraAlias)"), 'LIKE', "{$search}"
            ];
        }

        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {

                case 'inactive':
                    $filters[] = [
                        'RegistroInactivo', '=', 1
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
        } else {

            $filters[] = [
                'RegistroInactivo', '=', 0
            ];
        }


        $works = ConstructionWork::orderBy('tbObraID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $works = ConstructionWork::onlyTrashed()
                ->orderBy('tbObraID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        $work = $works[0];

        if ($id != null) {

            $work = ConstructionWork::find($id);

            if ($hasDelete)
                $work = ConstructionWork::withTrashed()->find($id);
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave}",
            ],

            'navigation' => [
                'base' => "{$this->route}",
                'section' => '/search',
                'pagination' => [
                    'links' => $works->links(),
                    'prev' => $works->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $works->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $works->currentPage(),
                    'first' => $works->firstItem(),
                    'last' => $works->lastPage(),
                    'total' => $works->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => '',
                    'child' => 'home'
                ],
            ],

            'works' => [
                'all' => $works,
                'one' => $work
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $works->count(),
                'query' => $request->q,
            ]
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Show Save
     * Muestra la vista para guardar un registro
     *
     * @param $request Request
     * @return \Illuminate\Http\Response
     */
    public function showSave(Request $request)
    {
        $works = ConstructionWork::orderBy('tbObraID', 'DESC')
            ->where('RegistroInactivo', '=', 0)
            ->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => "Obra / Nuevo",
            ],

            'navigation' => [
                'base' => "{$this->route}",
                'section' => '/home',
                'pagination' => [
                    'links' => $works->links(),
                    'prev' => $works->setPath("{$this->route}/home")->previousPageUrl(),
                    'next' => $works->setPath("{$this->route}/home")->nextPageUrl(),
                    'current' => $works->currentPage(),
                    'first' => $works->firstItem(),
                    'last' => $works->lastPage(),
                    'total' => $works->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => '',
                    'child' => 'home'
                ],
            ],

            'works' => [
                'all' => $works,
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
        $hasSearch = false;
        $persons = null;
        $queryString = ($request->has('page')) ? "?page={$request->page}" : '';

        // Url has param 'q' from search
        if ($request->has('q')) {
            
            $search = $request->q;
            $hasSearch = true;
            $route = "{$this->route}/search?q={$search}";
            $navigationFrom = "search";
            $queryString = ($request->has('page')) ? "?page={$request->page}&q={$search}" : "?q={$search}";

            $persons = Person::where('PersonaNombreCompleto', 'LIKE', "%{$search}%")->paginate($this->paginate);

        } else {
            $persons = Person::paginate($this->paginate);
        }
        
        $person = Person::find($id);
        $roles = Role::all();

        $viewData = [
            'page' => [
                'title' => 'Personas / Editar',
            ],

            'navigation' => [
                'base' => $this->route,
                'from' => $navigationFrom,
                'pagination' => $persons->setPath($route)->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
                'query_string' => $queryString
            ],

            'search' => [
                'query' => $search,
            ],

            'persons' => [
                'all' => $persons,
                'one' => $person,
            ],

            'roles' => [
                'all' => $roles,
            ]
        ];

        return view($this->viewsPath.'update', $viewData);
    }

    /**
     * Insert work
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doSave(Request $request) {

        $redirect = "{$this->route}/save";


        $request->request->add(['alias' =>  $request->input('cto34_index').'_'.$request->input('cto34_code')]);

        $rules = [
            'cto34_index' => 'required',
            'cto34_code' => 'required',
            'alias' => 'required|unique:mysql-reader.TbObra,ObraAlias',
        ];
   
        $messages = [
            'cto34_index.required' => 'Indice requerido.',
            'cto34_code.required' => 'Clave requerida.',
            'alias.unique' => 'Ya existe un registro con ese nombre de alias.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $work = new ConstructionWork();
        $work->setConnection('mysql-writer');

        $oficialStartDate =  new Carbon($request->input('cto34_oficialStartDate'));
        $oficialEndDate = new Carbon($request->input('cto34_oficialEndDate'));
        $oficialDifference = $oficialStartDate->diffInDays($oficialEndDate);

        $realStartDate = new Carbon($request->input('cto34_realStartDate'));
        $realEndDate = new Carbon($request->input('cto34_realEndDate'));
        $realDifference = $realStartDate->diffInDays($realEndDate);

        $work->ObraAlias = $request->input('alias');
        $work->ObraIndice = $request->input('cto34_index');
        $work->ObraClave = $request->input('cto34_code');
        $work->tbClienteID_ObraPropietario = $request->input('cto34_owner');
        $work->ObraNombreCompleto = $request->input('cto34_fullName');
        $work->ObraDescripcion = $request->input('cto34_description');
        $work->ObraDescripcionCorta = substr($request->input('cto34_description'), 30);
        $work->ObraSucursalNombre = $request->input('cto34_branch');
        $work->tbDirDomicilioID_Obra = $request->input('cto34_address');
        $work->ObraFechaInicioOficial = $request->input('cto34_oficialStartDate');
        $work->ObraFechaTerminoOficial = $request->input('cto34_oficialEndDate');
        $work->ObraDuracionOficial = $oficialDifference;//$request->input('cto34_oficialDuration');
        $work->ObraFechaInicioReal = $request->input('cto34_realStartDate');
        $work->ObraFechaTerminoReal = $request->input('cto34_realEndDate');
        $work->ObraDuracionReal = $realDifference;//$request->input('cto34_realDuration');
        //$work->ObraFechaInauguracion = $request->input('cto34_openingDate');
        $work->ObraTipo = $request->input('cto34_type');
        //$work->TbGeneroID_Obra = $request->input('cto34_kind');
        $work->ObraSuperficieInterior = $request->input('cto34_innerSurface');
        $work->ObraSuperficieExterior = $request->input('cto34_outerSurface');
        $work->ObraSuperficieTotal = $request->input('cto34_innerSurface') + $request->input('cto34_outerSurface');
        $work->created_at = date('Y-m-d H:i');

        if (!$work->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar la obra, intente nuevamente.'])
                        ->withInput();
        }

        return redirect(url('panel/constructionwork/home/'.$work->tbObraID))->with('success', 'Obra agregada correctamente.');
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
            'cto34_id' => 'required',
            'cto34_name' => 'required',
        ];
   
        $messages = [
            'cto34_id.required' => 'Id de persona requerido.',
            'cto34_name.required' => 'Nombre requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $person = Person::find($id)->setConnection('mysql-writer');

        $person->PersonaGenero = $request->input('cto34_gender');
        $person->PersonaPrefijo = $request->input('cto34_personPrefix');
        $person->PersonaFechaNacimineto = $request->input('cto34_birthdate');
        $person->PersonaNombres = $request->input('cto34_name');
        $person->PersonaApellidoPaterno = $request->input('cto34_lastName');
        $person->PersonaApellidoMaterno = $request->input('cto34_lastName2');
        $person->PersonaIdentificacionTipo = $request->input('cto34_idType');
        $person->PersonaIdentificacionNumero = $request->input('cto34_idNumber');
        $person->PersonaAlias = $request->input('cto34_nameByLast');
        $person->PersonaNombreDirecto = $request->input('cto34_directName');
        $person->PersonaNombreCompleto = $request->input('cto34_fullName');
        $person->PersonaContactoEmergencia = $request->input('cto34_contactEmergency');
        $person->PersonaComentarios = $request->input('cto34_comments');

        if (!$person->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puedo actualizar la persona, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Persona actualizada correctamente.');
        
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
            'cto34_id.required' => 'Id de persona requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $person = Person::find($id)->setConnection('mysql-writer');

        if (!$person->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar la persona, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Persona <b>'.$person->PersonaNombreDirecto.'</b> eliminada correctamente.');
        
    }
}
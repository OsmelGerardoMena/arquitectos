<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Business;
use App\Models\ConstructionWork;
use App\Models\Estimate;
use App\Models\Contract;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstimateController extends AppController
{
    private $route;
    private $childRoute = "estimates";
    private $viewsPath = 'panel.constructionwork.estimate.';
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
     * @param $workId int id de la obra
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $workId, $id = null)
    {
        $work = ConstructionWork::find($workId);

        $estimates = Estimate::orderBy('tbEstimacionID', 'DESC')
                            ->whereHas(
                                'contract', function ($query) use ($workId) {
                                    $query->where('tbObraID_Contrato', '=', $workId);
                                }
                            )->paginate($this->paginate);

        $estimate = $estimates[0];

        if ($id != null)
            $estimate = Estimate::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Estimaciones",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $estimates->links(),
                    'prev' => $estimates->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $estimates->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $estimates->currentPage(),
                    'first' => $estimates->firstItem(),
                    'last' => $estimates->lastPage(),
                    'total' => $estimates->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'finances',
                    'child' => 'estimates'
                ],
            ],

            'estimates' => [
                'all' => $estimates,
                'one' => $estimate
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
     * Search
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
        $estimates = null;
        $estimate = null;
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $queries = (array) $request->query();
        $filters = [];
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
                DB::raw("CONCAT_WS(' ', EstimacionLabel)"), 'LIKE', $search
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

        $estimates = Estimate::orderBy('tbEstimacionID', 'DESC')
            ->whereHas('contract', function ($query) use ($workId) {
                $query->where('tbObraID_Contrato', '=', $workId);
            })
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete)
            $estimates = Estimate::onlyTrashed()
                ->orderBy('tbEstimacionID', 'DESC')
                ->whereHas('contract', function ($query) use ($workId) {
                    $query->where('tbObraID_Contrato', '=', $workId);
                })
                ->where($filters)
                ->paginate($this->paginate);

        if ($estimates->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $estimate = $estimates[0];

        if ($id != null) {

            $estimate = Estimate::find($id);

            if ($hasDelete)
                $estimate = Estimate::withTrashed()
                    ->find($id);

        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Estimaciones",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $estimates->links(),
                    'prev' => $estimates->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->previousPageUrl(),
                    'next' => $estimates->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->nextPageUrl(),
                    'current' => $estimates->currentPage(),
                    'first' => $estimates->firstItem(),
                    'last' => $estimates->lastPage(),
                    'total' => $estimates->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'finances',
                    'child' => 'estimates'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'estimates' => [
                'all' => $estimates,
                'one' => $estimate
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $estimates->count(),
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
    public function showSave($workId)
    {

        $work = ConstructionWork::find($workId);
        //$groups = Group::all();
        $estimates = Estimate::orderBy('tbEstimacionID', 'DESC')
            ->whereHas(
                'contract', function ($query) use ($workId) {
                $query->where('tbObraID_Contrato', '=', $workId);
            }
            )->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Estimaciones",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $estimates->links(),
                    'prev' => $estimates->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $estimates->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $estimates->currentPage(),
                    'first' => $estimates->firstItem(),
                    'last' => $estimates->lastPage(),
                    'total' => $estimates->count()
                ],
                'current' => [
                    'father' => 'finances',
                    'child' => 'estimates'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'estimates' => [
                'all' => $estimates
            ],

            /*'groups' => [
                'all' => $groups,
            ],*/
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

        $workId = $request->input('cto34_work');
        $redirect = $this->route.'/'.$workId.'/estimates/save';

        $rules = [
            'cto34_label' => 'required',
        ];

        $messages = [
            'cto34_label.required' => 'Etiqueta requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        try {

            $estimate = new Estimate();
            $estimate->setConnection('mysql-writer');

            $estimate->tbContratoID_Estimacion = $request->input('cto34_contract');
            $estimate->created_at = date('Y-m-d H:i');
            //$estimate->tbObraID_Catalogo = $workId;

            if (!$estimate->save()) {
                throw new \Exception("No se puede guardar el catálogo, intenta nuevamente");
            }

        } catch(\Exception $e) {

            return redirect($redirect)
                ->withErrors([$e->getMessage()])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Catálogo agregado correctamente.');
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
            'cto34_id.required' => 'Id de estimación requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $estimate = Estimate::find($id)->setConnection('mysql-writer');

        if (!$estimate->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar la estimación, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Estimación eliminada correctamente.');

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
            'cto34_id.required' => 'Id de estimación requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $estimate = Estimate::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$estimate->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar la estimación, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Estimación restaurada correctamente.');
    }
}
<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Models\ConstructionWork;
use App\Models\Binnacle;
use App\Models\User;

use Illuminate\Http\Request;

class BinnacleController extends AppController
{
    private $route;
    private $childRoute = "binnacles";
    private $viewsPath = 'panel.constructionwork.binnacle.';
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
        $binnacles = Binnacle::orderBy('tbBitacoraID', 'DESC')
            ->whereHas('contract', function ($query) use ($workId) {
                $query->where('tbObraID_Contrato', '=', $workId);
            })
            ->paginate($this->paginate);
        $binnacle = $binnacles[0];

        if ($id != null)
            $binnacle = Binnacle::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Bitácoras",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $binnacles->links(),
                    'prev' => $binnacles->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $binnacles->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $binnacles->currentPage(),
                    'first' => $binnacles->firstItem(),
                    'last' => $binnacles->lastPage(),
                    'total' => $binnacles->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'binnacles'
                ],
            ],

            'binnacles' => [
                'all' => $binnacles,
                'one' => $binnacle
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
        $binnacles = null;
        $binnacle = null;
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";
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
                DB::raw("CONCAT_WS(' ', BitacoraNumero, BitacoraNotaCompleta)"), 'LIKE', "{$search}"
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

        if ($request->has('type') && !empty($request->type)) {
            $filters[] = [
                'BitacoraNotaTipo', '=', $request->type
            ];
        }

        if ($request->has('group') && !empty($request->group)) {
            $filters[] = [
                'BitacoraGrupo', '=', $request->group
            ];
        }

        if ($request->has('destination') && !empty($request->destination)) {
            $filters[] = [
                'BitacoraDestino', '=', $request->destination
            ];
        }

        $binnacles = Binnacle::orderBy('tbBitacoraID', 'DESC')
            ->whereHas('contract', function ($query) use ($workId) {
                $query->where('tbObraID_Contrato', '=', $workId);
            })
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $binnacles = Binnacle::onlyTrashed()
                ->orderBy('tbBitacoraID', 'DESC')
                ->whereHas('contract', function ($query) use ($workId) {
                    $query->where('tbObraID_Contrato', '=', $workId);
                })
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($binnacles->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $binnacle = $binnacles[0];

        if ($id != null) {

            $binnacle = Binnacle::find($id);
            if ($hasDelete)
                $binnacle = Binnacle::withTrashed()->find($id);
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Bitácoras",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $binnacles->links(),
                    'prev' => $binnacles->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->previousPageUrl(),
                    'next' => $binnacles->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->nextPageUrl(),
                    'current' => $binnacles->currentPage(),
                    'first' => $binnacles->firstItem(),
                    'last' => $binnacles->lastPage(),
                    'total' => $binnacles->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'datas',
                    'child' => 'levels'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'binnacles' => [
                'all' => $binnacles,
                'one' => $binnacle,
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $binnacles->count(),
                'query' => $request->q,
            ]
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Show Save
     * Muestra la vista para guardar un registro
     *
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function showSave(Request $request, $workId)
    {

        $work = ConstructionWork::find($workId);
        $binnacles = Binnacle::orderBy('tbBitacoraID', 'DESC')
            ->whereHas('contract', function ($query) use ($workId) {
                $query->where('tbObraID_Contrato', '=', $workId);
            })
            ->paginate($this->paginate);
        $users = User::orderBy('tbCTOUsuarioID', 'DESC')
            ->whereHas('person', function($query) use ($workId) {
                $query->whereHas('personBusiness', function($query) use ($workId) {
                    $query->whereHas('personWork', function($query) use ($workId) {
                        $query->where('tbObraID_DirPersonaObra', '=', $workId);
                    });
                });
            })
            ->with('person')
            ->get();

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Bitácoras",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/save',
                'pagination' => [
                    'links' => $binnacles->links(),
                    'prev' => $binnacles->previousPageUrl(),
                    'next' => $binnacles->nextPageUrl(),
                    'current' => $binnacles->currentPage(),
                    'first' => $binnacles->firstItem(),
                    'last' => $binnacles->lastPage(),
                    'total' => $binnacles->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'binnacles'
                ],
            ],

            'binnacles' => [
                'all' => $binnacles,
            ],

            'works' => [
                'one' => $work,
            ],

            'users' => [
                'all' => $users,
            ],
        ];

        return view($this->viewsPath.'save', $viewData);
    }

    /**
     * Show Update
     * Muestra la vista para actualizar un registro
     *
     * @param $workId int Id de la obra
     * @param $id int Id del registro a actualizar
     * @return \Illuminate\Http\Response
     */
    public function showUpdate(Request $request, $workId, $id = null)
    {
        $work = ConstructionWork::find($workId);
        $binnacles = null;
        $binnacle = null;
        $filters = [];
        $queries = $request->query();

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', BitacoraNumero, BitacoraNotaCompleta)"), 'LIKE', "{$search}"
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

        if ($request->has('type') && !empty($request->type)) {
            $filters[] = [
                'BitacoraNotaTipo', '=', $request->type
            ];
        }

        if ($request->has('group') && !empty($request->group)) {
            $filters[] = [
                'BitacoraGrupo', '=', $request->group
            ];
        }

        if ($request->has('destination') && !empty($request->destination)) {
            $filters[] = [
                'BitacoraDestino', '=', $request->destination
            ];
        }

        $binnacles = Binnacle::orderBy('tbBitacoraID', 'DESC')
            ->whereHas('contract', function ($query) use ($workId) {
                $query->where('tbObraID_Contrato', '=', $workId);
            })
            ->where($filters)
            ->paginate($this->paginate);

        $users = User::orderBy('tbCTOUsuarioID', 'DESC')
            ->whereHas('person', function($query) use ($workId) {
                $query->whereHas('personBusiness', function($query) use ($workId) {
                    $query->whereHas('personWork', function($query) use ($workId) {
                        $query->where('tbObraID_DirPersonaObra', '=', $workId);
                    });
                });
            })
            ->with('person')
            ->get();

        $binnacle = $binnacles[0];

        if ($id != null)
            $binnacle = Binnacle::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Bitácoras",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/update',
                'pagination' => [
                    'links' => $binnacles->links(),
                    'prev' => $binnacles->setPath("{$this->route}/{$workId}/{$this->childRoute}/update")->previousPageUrl(),
                    'next' => $binnacles->setPath("{$this->route}/{$workId}/{$this->childRoute}/update")->nextPageUrl(),
                    'current' => $binnacles->currentPage(),
                    'first' => $binnacles->firstItem(),
                    'last' => $binnacles->lastPage(),
                    'total' => $binnacles->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'binnacles'
                ],
            ],

            'binnacles' => [
                'all' => $binnacles,
                'one' => $binnacle
            ],

            'works' => [
                'one' => $work,
            ],

            'users' => [
                'all' => $users,
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $binnacles->count(),
                'query' => $request->q,
            ]
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
            'cto34_contract' => 'required',
            'cto34_number' => 'required|unique:mysql-reader.tbBitacora,BitacoraNumero',
            'cto34_author' => 'required',
        ];

        $messages = [
            'cto34_contract.required' => 'Contrato requerido',
            'cto34_number.required' => 'Número requerido.',
            'cto34_number.unique' => 'Número duplicado.',
            'cto34_author.required' => 'Autor requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }


        $binnacle = new Binnacle();
        $binnacle->setConnection('mysql-writer');

        $binnacle->tbContratoID_Bitacora = $request->input('cto34_contract');
        $binnacle->BitacoraNumero = $request->input('cto34_number');
        $binnacle->BitacoraFecha = $request->input('cto34_noteDate');
        $binnacle->tbBitacoraID_Antecedente = !empty($request->input('cto34_binnacle')) ? $request->input('cto34_binnacle') : 0;
        $binnacle->BitacoraNotaTipo = $request->input('cto34_type');
        $binnacle->BitacoraGrupo = $request->input('cto34_group');
        $binnacle->BitacoraDestino = $request->input('cto34_destination');
        $binnacle->BitacoraDescripcion = $request->input('cto34_description');
        $binnacle->BitacoraUbicacion = $request->input('cto34_location');
        $binnacle->BitacoraCausas = $request->input('cto34_reasons');
        $binnacle->BitacoraSolucion = $request->input('cto34_solution');
        $binnacle->BitacoraPlazoDescripcion = $request->input('cto34_term');
        $binnacle->BitacoraFechaCompromiso = $request->input('cto34_compromiseDate');
        $binnacle->BitacoraFechaCumplimiento = $request->input('cto34_accomplishmentDate');
        $binnacle->BitacoraPrevencion = $request->input('cto34_prevention');
        $binnacle->BitacoraCosto = $request->input('cto34_cost');
        $binnacle->BitacoraSanciones = $request->input('cto34_sanctions');
        $binnacle->BitacoraAnexos = $request->input('cto34_annexed');
        $binnacle->tbDirPersonaEmpresaObraID_BitacoraAutor = $request->input('cto34_personAutor');
        $binnacle->tbCTOUsuarioID_BitacoraCaptura = \Auth::id();
        $binnacle->tbCTOUsuarioID_BitacoraAutoriza = $request->input('cto34_auth');
        $binnacle->created_at = date('Y-m-d H:i');
        $binnacle->BitacoraNotaCompleta = $request->input('cto34_group')
            .' '.$request->input('cto34_destination')
            .' '.$request->input('cto34_description')
            .' '.$request->input('cto34_location')
            .' '.$request->input('cto34_reasons')
            .' '.$request->input('cto34_solution')
            .' '.$request->input('cto34_term')
            .' '.$request->input('cto34_compromiseDate')
            .' '.$request->input('cto34_prevention')
            .' '.$request->input('cto34_cost')
            .' '.$request->input('cto34_sanctions')
            .' '.$request->input('cto34_annexed');

        if (!$binnacle->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede guardar la bitácora, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Bitácora agregada correctamente.');
    }

    /**
     * Do Update
     * Realiza la acción de actualizar un registro
     *
     * @return \Illuminate\Http\Response
     */
    public function doUpdate(Request $request) {

        $workId = $request->input('cto34_work');
        $id = $request->input('cto34_id');
        $hasSearch = $request->input('_hasSearch');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info/{$id}{$request->input('_query')}";
        $redirectError = URL::previous();

        if ($hasSearch)
            $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/search/{$id}{$request->input('_query')}";

        $rules = [
            'cto34_id' => 'required',
            'cto34_contract' => 'required',
            'cto34_number' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de bitácora requerido',
            'cto34_contract.required' => 'Contrato requerido',
            'cto34_number.required' => 'Número requerido.'
        ];


        if ($request->input('cto34_number') != $request->input('cto34_numberAux') ) {
            $request->request->add(['new_number' =>  $request->input('cto34_number')]);

            $rules['new_number'] = 'unique:mysql-reader.tbBitacora,BitacoraNumero';
            $messages['new_number.unique'] = 'Número duplicado.';

        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $binnacle = Binnacle::find($request->input('cto34_id'))->setConnection('mysql-writer');

        $binnacle->tbContratoID_Bitacora = $request->input('cto34_contract');
        $binnacle->BitacoraNumero = $request->input('cto34_number');
        $binnacle->BitacoraFecha = $request->input('cto34_noteDate');
        $binnacle->tbBitacoraID_Antecedente = !empty($request->input('cto34_binnacle')) ? $request->input('cto34_binnacle') : 0;
        $binnacle->BitacoraNotaTipo = $request->input('cto34_type');
        $binnacle->BitacoraGrupo = $request->input('cto34_group');
        $binnacle->BitacoraDestino = $request->input('cto34_destination');
        $binnacle->BitacoraDescripcion = $request->input('cto34_description');
        $binnacle->BitacoraUbicacion = $request->input('cto34_location');
        $binnacle->BitacoraCausas = $request->input('cto34_reasons');
        $binnacle->BitacoraSolucion = $request->input('cto34_solution');
        $binnacle->BitacoraPlazoDescripcion = $request->input('cto34_term');
        $binnacle->BitacoraFechaCompromiso = $request->input('cto34_compromiseDate');
        $binnacle->BitacoraFechaCumplimiento = $request->input('cto34_accomplishmentDate');
        $binnacle->BitacoraPrevencion = $request->input('cto34_prevention');
        $binnacle->BitacoraCosto = $request->input('cto34_cost');
        $binnacle->BitacoraSanciones = $request->input('cto34_sanctions');
        $binnacle->BitacoraAnexos = $request->input('cto34_annexed');
        $binnacle->tbDirPersonaEmpresaObraID_BitacoraAutor = $request->input('cto34_personAutor');
        $binnacle->tbDirPersonaEmpresaObraID_BitacoraDestinatario = $request->input('cto34_personDestination');
        $binnacle->tbCTOUsuarioID_BitacoraAutoriza = $request->input('cto34_auth');
        $binnacle->tbCTOUsuarioID_BitacoraModifica = \Auth::id();
        $binnacle->updated_at = date('Y-m-d H:i');
        $binnacle->BitacoraNotaCompleta = $request->input('cto34_group')
            .' '.$request->input('cto34_destination')
            .' '.$request->input('cto34_description')
            .' '.$request->input('cto34_location')
            .' '.$request->input('cto34_reasons')
            .' '.$request->input('cto34_solution')
            .' '.$request->input('cto34_term')
            .' '.$request->input('cto34_compromiseDate')
            .' '.$request->input('cto34_prevention')
            .' '.$request->input('cto34_cost')
            .' '.$request->input('cto34_sanctions')
            .' '.$request->input('cto34_annexed');

        if (!$binnacle->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede actualizar la bitácora, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Bitácora actualizada correctamente.');

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
            'cto34_id.required' => 'Id de la bitácora requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $binnacle = Binnacle::find($id)->setConnection('mysql-writer');

        if (!$binnacle->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar la bitácora, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Bitácora eliminada correctamente.');

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
            'cto34_id.required' => 'Id de local requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $binnacle = Binnacle::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$binnacle->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar la bitácora, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Bitácora restaurada correctamente.');


    }
}
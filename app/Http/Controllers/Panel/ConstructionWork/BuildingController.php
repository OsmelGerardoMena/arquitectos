<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Models\ConstructionWork;
use App\Models\Building;

use Illuminate\Http\Request;

class BuildingController extends AppController {

    private $route;
    private $childRoute = "buildings";
    private $viewsPath = 'panel.constructionwork.building.';
    private $paginate = 25;

    public function __construct() {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/constructionwork');
    }

    /**
     * Show Index
     * Página principal de la sección
     *
     * @param $request Request
     * @param $workId int id de la obra
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $workId, $id = null) {

        $work = ConstructionWork::find($workId);
        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')
            ->where('tbObraID_UbicaEdificio', '=', $workId)
            ->with(['levels' => function($query) {
                $query->with('locals');
            }])
            ->paginate($this->paginate);
        $building = $buildings[0];
        $totalAreaInt = 0;
        $totalAreaExt = 0;
        $totalLevels = 0;
        $surfaces = [
            'int' => 0,
            'ext' => 0
        ];
        $locals = [
            'total' => 0,
            'area' => [
                'int' => 0,
                'ext'=> 0
            ]
        ];

        if ($id != null) {

            $building = Building::with(['levels' => function($query) {
                    $query->with('locals');
                }])->find($id);

        }

        if (!empty($building->levels)) {


            foreach ($building->levels as $level) {

                $surfaces['int'] += $level->UbicaNivelSuperficie;
                $surfaces['ext'] += $level->UbicaNivelSuperficieExterior;

                if ($level->UbicaNivelSumaAreaEdificio == 1) {
                    $totalAreaInt += $level->UbicaNivelSuperficie;
                    $totalAreaExt += $level->UbicaNivelSuperficieExterior;
                }

            }

            foreach ($building->levels as $level) {

                if ($level->UbicaNivelSumaNivelEdificio == 1) {
                    if (!empty($level->locals)) {
                        foreach ($level->locals as $local) {

                            $locals['total'] += 1;

                            if ($local->UbicaLocalTipo == 'Interior') {
                                $locals['area']['int'] += $local->UbicaLocalArea;
                            } else {
                                $locals['area']['ext']+= $local->UbicaLocalArea;
                            }

                            /*if ($local->UbicaLocalSumaAreaNivel == 1) {

                                if ($local->UbicaLocalTipo == 'Interior') {
                                    $totalAreaInt += $local->UbicaLocalArea;
                                } else {
                                    $totalAreaExt += $local->UbicaLocalArea;
                                }
                            }*/
                        }
                    }
                }
            }

            foreach ($building->levels as $level) {

                if ($level->UbicaNivelSumaNivelEdificio == 1) {
                    $totalLevels += 1;
                }
            }
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Edificios",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $buildings->links(),
                    'prev' => $buildings->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $buildings->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $buildings->currentPage(),
                    'first' => $buildings->firstItem(),
                    'last' => $buildings->lastPage(),
                    'total' => $buildings->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'datas',
                    'child' => 'buildings'
                ],
            ],

            'buildings' => [
                'all' => $buildings,
                'one' => $building,
                'area' => [
                    'int' => $totalAreaInt,
                    'ext' => $totalAreaExt
                ],

                'levels' => [
                    'total' => isset($building->levels) ? $building->levels->count() : 0,
                    'surfaces' => $surfaces
                ],

                 'locals' => $locals
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
    public function showSearch(Request $request, $workId, $id = null) {

        $work = ConstructionWork::find($workId);
        $buildings = null;
        $building = null;
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;
        $totalAreaInt = 0;
        $totalAreaExt = 0;
        $totalLevels = 0;
        $locals = [
            'total' => 0,
            'area' => [
                'int' => 0,
                'ext'=> 0
            ]
        ];
        $surfaces = [
            'int' => 0,
            'ext' => 0
        ];

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
                DB::raw("CONCAT_WS(' ', UbicaEdificioAlias, UbicaEdificioDescripcion, UbicaEdificioAreaDesplante)"), 'LIKE', "{$search}"
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

        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')
            ->where('tbObraID_UbicaEdificio', '=', $workId)->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $buildings = Building::onlyTrashed()
                ->orderBy('tbUbicaEdificioID', 'DESC')
                ->where('tbObraID_UbicaEdificio', '=', $workId)
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($buildings->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $building = $buildings[0];

        if ($id != null) {

            $building = Building::find($id);
            if ($hasDelete)
                $building = Building::withTrashed()->find($id);
        }

        if (!empty($building->levels)) {


            foreach ($building->levels as $level) {

                $surfaces['int'] += $level->UbicaNivelSuperficie;
                $surfaces['ext'] += $level->UbicaNivelSuperficieExterior;
            }

            foreach ($building->levels as $level) {

                if ($level->UbicaNivelSumaNivelEdificio == 1) {
                    if (!empty($level->locals)) {
                        foreach ($level->locals as $local) {

                            $locals['total'] += 1;

                            if ($local->UbicaLocalTipo == 'Interior') {
                                $locals['area']['int'] += $local->UbicaLocalArea;
                            } else {
                                $locals['area']['ext']+= $local->UbicaLocalArea;
                            }

                            if ($local->UbicaLocalSumaAreaNivel == 1) {

                                if ($local->UbicaLocalTipo == 'Interior') {
                                    $totalAreaInt += $local->UbicaLocalArea;
                                } else {
                                    $totalAreaExt += $local->UbicaLocalArea;
                                }
                            }
                        }
                    }
                }
            }

            foreach ($building->levels as $level) {

                if ($level->UbicaNivelSumaNivelEdificio == 1) {
                    $totalLevels += 1;
                }
            }
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Edificios",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $buildings->links(),
                    'prev' => $buildings->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->previousPageUrl(),
                    'next' => $buildings->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->nextPageUrl(),
                    'current' => $buildings->currentPage(),
                    'first' => $buildings->firstItem(),
                    'last' => $buildings->lastPage(),
                    'total' => $buildings->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'datas',
                    'child' => 'buildings'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'buildings' => [
                'all' => $buildings,
                'one' => $building,
                'area' => [
                    'int' => $totalAreaInt,
                    'ext' => $totalAreaExt
                ],
                'levels' => [
                    'total' => isset($building->levels) ? $building->levels->count() : 0,
                    'surfaces' => $surfaces
                ],
                'locals' => $locals
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $buildings->count(),
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
    public function showSave(Request $request, $workId) {

        $work = ConstructionWork::find($workId);
        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')->where('tbObraID_UbicaEdificio', '=', $workId)->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Edificios",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/save',
                'pagination' => [
                    'links' => $buildings->links(),
                    'prev' => $buildings->previousPageUrl(),
                    'next' => $buildings->nextPageUrl(),
                    'current' => $buildings->currentPage(),
                    'first' => $buildings->firstItem(),
                    'last' => $buildings->lastPage(),
                    'total' => $buildings->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'datas',
                    'child' => 'buildings'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'buildings' => [
                'all' => $buildings,
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
    public function showUpdate(Request $request, $workId,  $id = null)
    {
        $work = ConstructionWork::find($workId);
        $building = null;
        $filters = [];
        $queries = $request->query();
        $totalAreaInt = 0;
        $totalAreaExt = 0;
        $totalLevels = 0;

        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {
                case 'active':
                    $filters[] = [
                        'RegistroCerrado', '=', 0
                    ];
                    break;

                case 'inactive':
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

        // Url has param 'q' from search
        if ($request->has('q')) {

            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            $filters[] = [
                DB::raw("CONCAT_WS(' ', UbicaEdificioAlias, UbicaEdificioDescripcion)"), 'LIKE', "{$search}"
            ];
        }

        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')
            ->where('tbObraID_UbicaEdificio', '=', $workId)
            ->where($filters)
            ->paginate($this->paginate);
        $building = $buildings[0];

        if ($id != null)
            $building = Building::find($id);

        if (!empty($building->levels)) {

            foreach ($building->levels as $level) {

                if ($level->UbicaNivelSumaNivelEdificio == 1) {
                    if (!empty($level->locals)) {
                        foreach ($level->locals as $local) {

                            if ($local->UbicaLocalSumaAreaNivel == 1) {

                                if ($local->UbicaLocalTipo == 'Interior') {
                                    $totalAreaInt += $local->UbicaLocalArea;
                                } else {
                                    $totalAreaExt += $local->UbicaLocalArea;
                                }
                            }
                        }
                    }
                }
            }

            foreach ($building->levels as $level) {

                if ($level->UbicaNivelSumaNivelEdificio == 1) {
                    $totalLevels += 1;
                }
            }
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Edificios",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/update',
                'pagination' => [
                    'links' => $buildings->links(),
                    'prev' => $buildings->setPath("{$this->route}/{$workId}/{$this->childRoute}/update")->previousPageUrl(),
                    'next' => $buildings->setPath("{$this->route}/{$workId}/{$this->childRoute}/update")->nextPageUrl(),
                    'current' => $buildings->currentPage(),
                    'first' => $buildings->firstItem(),
                    'last' => $buildings->lastPage(),
                    'total' => $buildings->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'datas',
                    'child' => 'buildings'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'buildings' => [
                'all' => $buildings,
                'one' => $building,
                'area' => [
                    'int' => $totalAreaInt,
                    'ext' => $totalAreaExt
                ],
                'levels' => $totalLevels
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $buildings->count(),
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

        $request->request->add(['alias' =>  $request->input('cto34_code').' - '.$request->input('cto34_name')]);

        $rules = [
            'cto34_code' => 'required',
            'cto34_name' => 'required',
            'alias' => 'required|unique:mysql-reader.tbUbicaEdificio,UbicaEdificioAlias',
        ];

        $messages = [
            'cto34_code.required' => 'Clave requerida.',
            'cto34_name.required' => 'Nombre requerido.',
            'alias.unique' => 'Ya existe un registro con ese nombre de alias.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $building = new Building();
        $building->setConnection('mysql-writer');

        $building->UbicaEdificioAlias = $request->input('cto34_code').' - '.$request->input('cto34_name');
        $building->UbicaEdificioClave = $request->input('cto34_code');
        $building->UbicaEdificioNombre = $request->input('cto34_name');
        $building->UbicaEdificioDescripcion = $request->input('cto34_description');
        $building->UbicaEdificioAreaDesplante = $request->input('cto34_shadyArea');
        $building->UbicaEdificioAreaTotal = $request->input('cto34_totalAreaInt');
        $building->UbicaEdificioAreaTotalExterior = $request->input('cto34_totalAreaExt');
        $building->UbicaEdificioNiveles = $request->input('cto34_totalLevels');
        $building->tbObraID_UbicaEdificio = $request->input('cto34_work');
        $building->created_at = date('Y-m-d H:i');

        if (!$building->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede guardar el edificio, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Edificio agregado correctamente.');
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
            'cto34_code' => 'required',
            'cto34_name' => 'required',
        ];

        $messages = [
            'cto34_code.required' => 'Clave requerida.',
            'cto34_name.required' => 'Nombre requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $building = Building::find($id)->setConnection('mysql-writer');

        $building->UbicaEdificioAlias = $request->input('cto34_code').' - '.$request->input('cto34_name');
        $building->UbicaEdificioClave = $request->input('cto34_code');
        $building->UbicaEdificioNombre = $request->input('cto34_name');
        $building->UbicaEdificioDescripcion = $request->input('cto34_description');
        $building->UbicaEdificioAreaDesplante = $request->input('cto34_shadyArea');
        $building->UbicaEdificioAreaTotal = $request->input('cto34_totalArea');
        $building->UbicaEdificioAreaTotalExterior = $request->input('cto34_totalAreaExt');
        $building->UbicaEdificioNiveles = $request->input('cto34_totalLevels');
        $building->tbObraID_UbicaEdificio = $request->input('cto34_work');
        $building->updated_at = date('Y-m-d H:i');

        if (!$building->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede actualizar el edificio, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Edificio actualizado correctamente.');
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
            'cto34_id.required' => 'Id de edificio requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $building = Building::find($id)->setConnection('mysql-writer');

        if (!$building->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el edificio, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Edificio <b>'.$building->UbicaEdificioAlias.'</b> eliminado correctamente.');


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

        $building = Building::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$building->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el edificio, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Edificio restaurado correctamente.');
    }
}
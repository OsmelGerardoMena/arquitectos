<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Illuminate\Support\Facades\Bus;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Business;
use App\Models\ConstructionWork;
use App\Models\Contract;
use App\Models\Currency;
use App\Models\Group;
use App\Models\Level;
use App\Models\Building;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends AppController
{
    private $route;
    private $childRoute = "levels";
    private $viewsPath = 'panel.constructionwork.level.';
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
    public function showIndex(Request $request, $workId, $id = null) {

        $work = ConstructionWork::find($workId);
        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')->where('tbObraID_UbicaEdificio', '=', $workId)->get();
        $levels = Level::orderBy('tbUbicaNivelID', 'DESC')
            ->whereHas(
                'building', function ($query) use ($workId) {
                $query->where('tbObraID_UbicaEdificio', '=', $workId);
            }
            )->with('locals')->paginate($this->paginate);
        $level = $levels[0];

        if ($id != null)
            $level = Level::find($id);

        $totalLevelArea = 0;

        if (isset($levels[0]->locals)) {
            foreach ($levels[0]->locals() as $local) {
                $totalLevelArea += $local->UbicaLocalArea;
            }
        }

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
                        $surfaces['int'] += $local->UbicaLocalArea;
                    } else {
                        $surfaces['ext'] += $local->UbicaLocalArea;
                    }
                }
            }
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Niveles",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $levels->links(),
                    'prev' => $levels->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $levels->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $levels->currentPage(),
                    'first' => $levels->firstItem(),
                    'last' => $levels->lastPage(),
                    'total' => $levels->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'datas',
                    'child' => 'levels'
                ],
            ],

            'buildings' => [
                'all' => $buildings,
                'one' => $level->building
            ],

            'levels' => [
                'all' => $levels,
                'one' => $level,
                'surfaces' => $surfaces,
                'total_area' => $totalLevelArea
            ],

            'locals' => $locals,

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
        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')->where('tbObraID_UbicaEdificio', '=', $workId)->get();
        $levels = null;
        $level = null;
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
                DB::raw("CONCAT_WS(' ', UbicaNivelAlias, UbicaNivelDescripcion, UbicaNivelSuperficie, UbicaNivelNPT)"), 'LIKE', "{$search}"
            ];
        }

        if ($request->has('building') && !empty($request->building)) {

            $filters[] = [
                'tbUbicaEdificioID_Nivel', '=', $request->building
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

        $levels = Level::orderBy('tbUbicaNivelID', 'DESC')
            ->whereHas('building', function ($query) use ($workId) {
                $query->where('tbObraID_UbicaEdificio', '=', $workId);
            })
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $levels = Level::onlyTrashed()
                ->orderBy('tbUbicaNivelID', 'DESC')
                ->whereHas( 'building', function ($query) use ($workId) {
                        $query->where('tbObraID_UbicaEdificio', '=', $workId);
                })
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($levels->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $level = $levels[0];

        if ($id != null) {

            $level = Level::find($id);

            if ($hasDelete)
                $level = Level::withTrashed()->find($id);

        }

        $totalLevelArea = 0;
        foreach ($levels[0]->locals() as $local) {
            $totalLevelArea += $local->UbicaLocalArea;
        }

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
                        $surfaces['int'] += $local->UbicaLocalArea;
                    } else {
                        $surfaces['ext'] += $local->UbicaLocalArea;
                    }
                }
            }
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Niveles",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $levels->links(),
                    'prev' => $levels->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->previousPageUrl(),
                    'next' => $levels->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->nextPageUrl(),
                    'current' => $levels->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->currentPage(),
                    'first' => $levels->firstItem(),
                    'last' => $levels->lastPage(),
                    'total' => $levels->count()
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

            'levels' => [
                'all' => $levels,
                'one' => $level,
                'surfaces' => $surfaces,
                'total_area' => $totalLevelArea
            ],

            'locals' => $locals,

            'buildings' => [
                'all' => $buildings,
                'one' => $level->building
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $levels->count(),
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
        $levels = Level::orderBy('tbUbicaNivelID', 'DESC')
            ->whereHas(
                'building', function ($query) use ($workId) {
                $query->where('tbObraID_UbicaEdificio', '=', $workId);
            }
            )->paginate($this->paginate);
        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')->where('tbObraID_UbicaEdificio', '=', $workId)->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Niveles",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'pagination' => [
                    'links' => $levels->links(),
                    'prev' => $levels->previousPageUrl(),
                    'next' => $levels->nextPageUrl(),
                    'current' => $levels->currentPage(),
                    'first' => $levels->firstItem(),
                    'last' => $levels->lastPage(),
                    'total' => $levels->count()
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

            'levels' => [
                'all' => $levels
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
    public function showUpdate(Request $request, $workId, $id = null)
    {
        $work = ConstructionWork::find($workId);
        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')->where('tbObraID_UbicaEdificio', '=', $workId)->get();
        $levels = null;
        $level = null;
        $queries = (array) $request->query();
        $filters = (array) [];

        if ($id != null)
            $level = Level::find($id);

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', UbicaNivelAlias, UbicaNivelDescripcion, UbicaNivelSuperficie, UbicaNivelNPT)"), 'LIKE', "{$search}"
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

                default:
                    break;
            }

        }

        $levels = Level::orderBy('tbUbicaNivelID', 'DESC')
            ->whereHas('building', function ($query) use ($workId) {
                $query->where('tbObraID_UbicaEdificio', '=', $workId);
            })
            ->where($filters)
            ->paginate($this->paginate);

        $level = $levels[0];
        if ($id != null)
            $level = Level::find($id);

        $totalLevelArea = 0;
        foreach ($level->locals as $local) {
            $totalLevelArea += $local->UbicaLocalArea;
        }

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
                        $surfaces['int'] += $local->UbicaLocalArea;
                    } else {
                        $surfaces['ext'] += $local->UbicaLocalArea;
                    }
                }
            }
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Niveles",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/update',
                'pagination' => [
                    'links' => $levels->links(),
                    'prev' => $levels->setPath("{$this->route}/{$workId}/{$this->childRoute}/update")->previousPageUrl(),
                    'next' => $levels->setPath("{$this->route}/{$workId}/{$this->childRoute}/update")->nextPageUrl(),
                    'current' => $levels->currentPage(),
                    'first' => $levels->firstItem(),
                    'last' => $levels->lastPage(),
                    'total' => $levels->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'datas',
                    'child' => 'levels'
                ],
            ],

            'levels' => [
                'all' => $levels,
                'one' => $level,
                'surfaces' => $surfaces,
                'total_area' => $totalLevelArea
            ],

            'locals' => $locals,

            'buildings' => [
                'all' => $buildings,
            ],

            'works' => [
                'one' => $work,
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
            'cto34_building' => 'required',
            'cto34_code' => 'required',
            'cto34_name' => 'required',
            'alias' => 'required|unique:mysql-reader.tbUbicaNivel,UbicaNivelAlias',
        ];

        $messages = [

            'cto34_building.required' => 'Edificio requerido.',
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

        $building = Building::find($request->input('cto34_building'));

        $level = new Level();
        $level->setConnection('mysql-writer');

        $level->UbicaNivelAlias = $request->input('cto34_code').' - '.$request->input('cto34_name');
        $level->UbicaNivelClave = $request->input('cto34_code');
        $level->UbicaNivelCodigo = $building->UbicaEdificioClave.'.'.$request->input('cto34_code');
        $level->UbicaNivelNombre = $request->input('cto34_name');
        $level->UbicaNivelConsecutivo = $request->input('cto34_consecutive');
        $level->UbicaNivelDescripcion = $request->input('cto34_description');
        $level->UbicaNivelSumaNivelEdificio = (!empty($request->input('cto34_sumLevel'))) ? 1 : 0;
        $level->UbicaNivelSumaAreaEdificio = (!empty($request->input('cto34_sumArea'))) ? 1 : 0;
        $level->UbicaNivelSuperficie = $request->input('cto34_surfaceLevel');
        $level->UbicaNivelSuperficieExterior = $request->input('cto34_surfaceLevelExt');
        $level->UbicaNivelNPT = $request->input('cto34_nptLevel');
        $level->tbUbicaEdificioID_Nivel = $request->input('cto34_building');
        $level->created_at = date('Y-m-d H:i');

        if (!$level->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede guardar el nivel, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Nivel agregado correctamente.');
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
        $request->request->add(['alias' =>  $request->input('cto34_code').' - '.$request->input('cto34_name')]);

        if ($hasSearch)
            $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/search/{$id}{$request->input('_query')}";

        $rules = [
            'cto34_building' => 'required',
            'cto34_code' => 'required',
            'cto34_name' => 'required'
        ];

        $messages = [

            'cto34_building.required' => 'Edificio requerido.',
            'cto34_code.required' => 'Clave requerida.',
            'cto34_name.required' => 'Nombre requerido.'
        ];

        if ($request->input('alias') != $request->input('cto34_alias')) {

            $rules['alias'] = 'required|unique:mysql-reader.tbUbicaNivel,UbicaNivelAlias';
            $messages['alias.unique'] = 'Ya existe un registro con ese nombre de alias.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $building = Building::find($request->input('cto34_building'));
        $level = Level::find($id)->setConnection('mysql-writer');

        $level->UbicaNivelAlias = $request->input('cto34_code').' - '.$request->input('cto34_name');
        $level->UbicaNivelClave = $request->input('cto34_code');
        $level->UbicaNivelCodigo = $building->UbicaEdificioClave.'.'.$request->input('cto34_code');
        $level->UbicaNivelNombre = $request->input('cto34_name');
        $level->UbicaNivelConsecutivo = $request->input('cto34_consecutive');
        $level->UbicaNivelDescripcion = $request->input('cto34_description');
        $level->UbicaNivelSumaNivelEdificio = (!empty($request->input('cto34_sumLevel'))) ? 1 : 0;
        $level->UbicaNivelSumaAreaEdificio = (!empty($request->input('cto34_sumArea'))) ? 1 : 0;
        $level->UbicaNivelSuperficie = $request->input('cto34_surfaceLevel');
        $level->UbicaNivelSuperficieExterior = $request->input('cto34_surfaceLevelExt');
        $level->UbicaNivelNPT = $request->input('cto34_nptLevel');
        $level->tbUbicaEdificioID_Nivel = $request->input('cto34_building');
        $level->updated_at = date('Y-m-d H:i');

        if (!$level->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede actualizar el nivel, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Nivel actualizado correctamente.');

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
            'cto34_id.required' => 'Id de nivel requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $level = Level::find($id)->setConnection('mysql-writer');

        if (!$level->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el nivel, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Nivel <b>'.$level->UbicaNivelAlias.'</b> eliminado correctamente.');

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
            'cto34_id.required' => 'Id de nivel requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $level = Level::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$level->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el nivel, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Nivel restaurado correctamente.');


    }
}
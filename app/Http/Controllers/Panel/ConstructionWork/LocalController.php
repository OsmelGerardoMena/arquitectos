<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Models\ConstructionWork;
use App\Models\Level;
use App\Models\Building;
use App\Models\Local;

use Illuminate\Http\Request;

class LocalController extends AppController
{
    private $route;
    private $childRoute = "locals";
    private $viewsPath = 'panel.constructionwork.local.';
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
        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')->where('tbObraID_UbicaEdificio', '=', $workId)->paginate($this->paginate);
        $locals = Local::orderBy('tbUbicaLocalID', 'DESC')
            ->whereHas(
                'level', function ($query) use ($workId) {

                    $query->whereHas(
                            'building', function ($query) use ($workId) {
                            $query->where('tbObraID_UbicaEdificio', '=', $workId);
                        }
                    );
                }
            )->paginate($this->paginate);
        $local = $locals[0];
        $levels = Level::orderBy('tbUbicaNivelID', 'DESC')
            ->whereHas(
                'building', function ($query) use ($workId) {
                $query->where('tbObraID_UbicaEdificio', '=', $workId);
            }
            )->with('locals')->paginate($this->paginate);

        if ($id != null)
            $local = Local::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Locales",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $locals->links(),
                    'prev' => $locals->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $locals->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $locals->currentPage(),
                    'first' => $locals->firstItem(),
                    'last' => $locals->lastPage(),
                    'total' => $locals->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'datas',
                    'child' => 'locals'
                ],
            ],

            'locals' => [
                'all' => $locals,
                'one' => $local
            ],

            'works' => [
                'one' => $work,
            ],

            'buildings' => [
                'all' => $buildings,
                'one' => $local->level->building
            ],

            'levels' => [
                'all' => $levels
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
        $locals = null;
        $local = null;
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;
        $buildingId = null;
        $levelId = null;

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
                DB::raw("CONCAT_WS(' ', UbicaLocalAlias, UbicaLocalArea, UbicaLocalTipo)"), 'LIKE', "{$search}"
            ];
        }

        if ($request->has('building') && !empty($request->building)) {
            $buildingId = $request->building;
        }

        if ($request->has('level') && !empty($request->level)) {
            $levelId = $request->level;
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
                'UbicaLocalTipo', '=', $request->type
            ];
        }

        $locals = Local::orderBy('tbUbicaLocalID', 'DESC')
            ->whereHas(
                'level', function ($query) use ($workId, $levelId, $buildingId) {

                $query->whereHas(
                    'building', function ($query) use ($workId, $buildingId) {
                    $query->where('tbObraID_UbicaEdificio', '=', $workId);
                    if ($buildingId != null)
                    $query->where('tbUbicaEdificioID', '=', $buildingId);

                });

                if ($levelId != null)
                    $query->where('tbUbicaNivelID', '=', $levelId);

            })
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $locals = Local::onlyTrashed()
                ->orderBy('tbUbicaLocalID', 'DESC')
                ->whereHas('level', function ($query) use ($workId, $levelId, $buildingId) {
                    $query->whereHas('building', function ($query) use ($workId, $buildingId) {
                        $query->where('tbObraID_UbicaEdificio', '=', $workId);
                        if ($buildingId != null)
                            $query->where('tbUbicaEdificioID', '=', $buildingId);
                    });

                    if ($levelId != null)
                        $query->where('tbUbicaNivelID', '=', $levelId);
                })
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($locals->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $local = $locals[0];

        if ($id != null) {

            $local = Local::find($id);
            if ($hasDelete)
                $local = Local::withTrashed()->find($id);
        }

        $levels = Level::orderBy('tbUbicaNivelID', 'DESC')
            ->whereHas(
                'building', function ($query) use ($workId) {
                $query->where('tbObraID_UbicaEdificio', '=', $workId);
            }
            )->with('locals')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Locales",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $locals->links(),
                    'prev' => $locals->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->appends($queries)->previousPageUrl(),
                    'next' => $locals->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->appends($queries)->nextPageUrl(),
                    'current' => $locals->currentPage(),
                    'first' => $locals->firstItem(),
                    'last' => $locals->lastPage(),
                    'total' => $locals->count()
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

            'locals' => [
                'all' => $locals,
                'one' => $local,
            ],

            'buildings' => [
                'all' => $buildings,
                'one' => $local->level->building
            ],

            'levels' => [
                'all' => $levels
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $locals->count(),
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
        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')->where('tbObraID_UbicaEdificio', '=', $workId)->get();
        $locals = Local::orderBy('tbUbicaLocalID', 'DESC')
            ->whereHas(
                'level', function ($query) use ($workId) {

                $query->whereHas(
                    'building', function ($query) use ($workId) {
                    $query->where('tbObraID_UbicaEdificio', '=', $workId);
                }
                );
            }
            )->paginate($this->paginate);
        $levels = Level::orderBy('tbUbicaNivelID', 'DESC')
            ->whereHas(
                'building', function ($query) use ($workId) {
                $query->where('tbObraID_UbicaEdificio', '=', $workId);
            }
            )->paginate($this->paginate);


        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Locales",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/save',
                'pagination' => [
                    'links' => $locals->links(),
                    'prev' => $locals->previousPageUrl(),
                    'next' => $locals->nextPageUrl(),
                    'current' => $locals->currentPage(),
                    'first' => $locals->firstItem(),
                    'last' => $locals->lastPage(),
                    'total' => $locals->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'datas',
                    'child' => 'locals'
                ],
            ],

            'locals' => [
                'all' => $locals,
            ],

            'levels' => [
                'all' => $levels,
            ],

            'buildings' => [
                'all' => $buildings,
            ],

            'works' => [
                'one' => $work,
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
        $buildings = Building::orderBy('tbUbicaEdificioID', 'DESC')->where('tbObraID_UbicaEdificio', '=', $workId)->paginate($this->paginate);
        $locals = null;
        $filters = [];
        $queries = $request->query();

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', UbicaLocalAlias, UbicaLocalArea, UbicaLocalTipo)"), 'LIKE', "{$search}"
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
                'UbicaLocalTipo', '=', $request->type
            ];
        }

        $locals = Local::orderBy('tbUbicaLocalID', 'DESC')
            ->whereHas(
                'level', function ($query) use ($workId) {
                    $query->whereHas('building', function ($query) use ($workId) {
                        $query->where('tbObraID_UbicaEdificio', '=', $workId);
                });
            })
            ->where($filters)
            ->paginate($this->paginate);

        $local = $locals[0];

        if ($id != null)
            $local = Local::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Locales",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/update',
                'pagination' => [
                    'links' => $locals->links(),
                    'prev' => $locals->setPath("{$this->route}/{$workId}/{$this->childRoute}/update")->appends($queries)->previousPageUrl(),
                    'next' => $locals->setPath("{$this->route}/{$workId}/{$this->childRoute}/update")->appends($queries)->nextPageUrl(),
                    'current' => $locals->currentPage(),
                    'first' => $locals->firstItem(),
                    'last' => $locals->lastPage(),
                    'total' => $locals->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'datas',
                    'child' => 'locals'
                ],
            ],

            'locals' => [
                'all' => $locals,
                'one' => $local
            ],

            'works' => [
                'one' => $work,
            ],

            'buildings' => [
                'all' => $buildings,
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $locals->count(),
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
            'cto34_level' => 'required',
            'cto34_name' => 'required'
        ];

        $messages = [
            'cto34_level.required' => 'Nivel requerido',
            'cto34_name.required' => 'Nombre requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $level = Level::find($request->input('cto34_level'));
        $levels = Local::where('tbUbicaNivelID_Local', '=', $request->input('cto34_level'))->get();
        $localNumber = $levels->count() + 1;

        $local = new Local();
        $local->setConnection('mysql-writer');

        $local->UbicaLocalNumero = $localNumber;
        $local->UbicaLocalClave = $level->UbicaNivelCodigo.'.'.str_pad($localNumber, 3, "0", STR_PAD_LEFT);
        $local->UbicaLocalAlias = $local->UbicaLocalClave.' - '.$request->input('cto34_name');
        $local->UbicaLocalNombre = $request->input('cto34_name');
        $local->UbicaLocalTipo = $request->input('cto34_type');
        $local->UbicaLocalArea = $request->input('cto34_area');
        $local->UbicaLocalSumaAreaNivel = (!empty($request->input('cto34_sumArea'))) ? 1 : 0;
        $local->tbUbicaNivelID_Local = $request->input('cto34_level');
        $local->created_at = date('Y-m-d H:i');

        if (!$local->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede guardar el local, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Local agregado correctamente.');
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
            'cto34_level' => 'required',
            'cto34_name' => 'required',
            'cto34_id' => 'required'
        ];

        $messages = [
            'cto34_level.required' => 'Nivel requerido',
            'cto34_name.required' => 'Nombre requerido.',
            'cto34_id.required' => 'Id de local requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $level = Level::find($request->input('cto34_level'));
        $levels = Local::where('tbUbicaNivelID_Local', '=', $request->input('cto34_level'))->get();
        $localNumber = $levels->count() + 1;

        $local = Local::find($request->input('cto34_id'))->setConnection('mysql-writer');

        //$local->UbicaLocalNumero = $localNumber;
        $local->UbicaLocalClave = $level->UbicaNivelCodigo.'.'.str_pad($localNumber, 3, "0", STR_PAD_LEFT);
        $local->UbicaLocalAlias = $local->UbicaLocalClave.' - '.$request->input('cto34_name');
        $local->UbicaLocalNombre = $request->input('cto34_name');
        $local->UbicaLocalTipo = $request->input('cto34_type');
        $local->UbicaLocalArea = $request->input('cto34_area');
        $local->UbicaLocalSumaAreaNivel = (!empty($request->input('cto34_sumArea'))) ? 1 : 0;
        $local->tbUbicaNivelID_Local = $request->input('cto34_level');
        $local->updated_at = date('Y-m-d H:i');

        if (!$local->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede actualizar el local, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Local actualizado correctamente.');

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
            'cto34_id.required' => 'Id de local requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $local = Local::find($id)->setConnection('mysql-writer');

        if (!$local->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el local, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Local <b>'.$local->UbicaLocalAlias.'</b> eliminado correctamente.');

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

        $local = Local::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$local->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el local, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Local restaurado correctamente.');
    }
}
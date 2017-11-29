<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Business;
use App\Models\ConstructionWork;
use App\Models\Catalog;
use App\Models\Group;
use App\Models\Unity;
use App\Models\Departure;
use App\Models\Level;
use App\Models\BusinessWork;

use Illuminate\Http\Request;

class CatalogController extends AppController
{
    private $route;
    private $childRoute = "catalogs";
    private $viewsPath = 'panel.constructionwork.catalog.';
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
        $catalogs = Catalog::orderBy('tbCatalogoID', 'DESC')
                            ->whereHas(
                                    'contract', function ($query) use ($workId) {
                                    $query->where('tbObraID_Contrato', '=', $workId);
                                }
                            )->paginate($this->paginate);
        $catalog = $catalogs[0];

        if ($id != null)
            $catalog = Catalog::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Catálogos",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $catalogs->links(),
                    'prev' => $catalogs->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $catalogs->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $catalogs->currentPage(),
                    'first' => $catalogs->firstItem(),
                    'last' => $catalogs->lastPage(),
                    'total' => $catalogs->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'finances',
                    'child' => 'catalogs'
                ],
            ],

            'catalogs' => [
                'all' => $catalogs,
                'one' => $catalog
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
        $catalogs = null;
        $catalog = null;
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
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
                DB::raw("CONCAT_WS(' ', CatalogoConceptoCodigo, CatalogoDescripcion, CatalogoImporte, CatalogoFolioExterno)"), 'LIKE', "{$search}"
            ];
        }

        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {

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

        $catalogs = Catalog::orderBy('tbCatalogoID', 'DESC')
            ->whereHas(
                'contract', function ($query) use ($workId) {
                    $query->where('tbObraID_Contrato', '=', $workId);
                }
            )
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $catalogs = Catalog::onlyTrashed()
                ->orderBy('tbCatalogoID', 'DESC')
                ->whereHas(
                    'contract', function ($query) use ($workId) {
                    $query->where('tbObraID_Contrato', '=', $workId);
                }
                )
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($catalogs->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $catalog = $catalogs[0];

        if ($id != null) {

            $catalog = Catalog::find($id);
            if ($hasDelete)
                $catalog = Catalog::withTrashed()->find($id);
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Catálogos",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $catalogs->links(),
                    'prev' => $catalogs->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->appends($queries)->previousPageUrl(),
                    'next' => $catalogs->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->appends($queries)->nextPageUrl(),
                    'current' => $catalogs->currentPage(),
                    'first' => $catalogs->firstItem(),
                    'last' => $catalogs->lastPage(),
                    'total' => $catalogs->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'finances',
                    'child' => 'catalogs'
                ],
            ],

            'catalogs' => [
                'all' => $catalogs,
                'one' => $catalog
            ],

            'works' => [
                'one' => $work,
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $catalogs->count(),
                'query' => $request->q,
            ],
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
        $catalogs = Catalog::orderBy('tbCatalogoID', 'DESC')
            ->whereHas(
                'contract', function ($query) use ($workId) {
                $query->where('tbObraID_Contrato', '=', $workId);
            }
            )->paginate($this->paginate);
        $groups = Group::all();
        $unities = Unity::all();
        $departures = Departure::where('tbObraID_Partida', '=', $workId)->get();
        $levels = Level::orderBy('tbUbicaNivelID', 'DESC')
            ->whereHas(
                'building', function ($query) use ($workId) {
                $query->where('tbObraID_UbicaEdificio', '=', $workId);
            }
            )->with('locals')->get();
        $business = BusinessWork::orderBy('tbDirEmpresaObraID', 'DESC')->where('tbObraID_DirEmpresaObra', '=', $workId)->get();

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Catálogos",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/save',
                'pagination' => [
                    'links' => $catalogs->links(),
                    'prev' => $catalogs->previousPageUrl(),
                    'next' => $catalogs->nextPageUrl(),
                    'current' => $catalogs->currentPage(),
                    'first' => $catalogs->firstItem(),
                    'last' => $catalogs->lastPage(),
                    'total' => $catalogs->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'finances',
                    'child' => 'catalogs'
                ],
            ],

            'catalogs' => [
                'all' => $catalogs,
            ],

            'works' => [
                'one' => $work,
            ],

            'groups' => [
                'all' => $groups,
            ],

            'unities' => [
                'all' => $unities,
            ],

            'departures' => [
                'all' => $departures,
            ],

            'levels' => [
                'all' => $levels,
            ],

            'business' => [
                'all' => $business
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
        $catalog = null;
        $catalogs = null;
        $groups = Group::all();
        $unities = Unity::all();
        $departures = Departure::where('tbObraID_Partida', '=', $workId)->get();
        $levels = Level::orderBy('tbUbicaNivelID', 'DESC')
            ->whereHas(
                'building', function ($query) use ($workId) {
                $query->where('tbObraID_UbicaEdificio', '=', $workId);
            }
            )->with('locals')->get();
        $filters = [];
        $queries = $request->query();
        $section = '/info';


        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', CatalogoConceptoCodigo, CatalogoDescripcion, CatalogoImporte, CatalogoFolioExterno)"), 'LIKE', "{$search}"
            ];


            $section = '/search';
        }

        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {

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

            $section = '/search';
        }

        $catalogs = Catalog::orderBy('tbCatalogoID', 'DESC')
            ->whereHas(
                'contract', function ($query) use ($workId) {
                $query->where('tbObraID_Contrato', '=', $workId);
            })
            ->where($filters)
            ->paginate($this->paginate);

        $catalog = $catalogs[0];

        if ($id != null)
            $catalog = Catalog::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Catálogos",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => $section,
                'pagination' => [
                    'links' => $catalogs->links(),
                    'prev' => $catalogs->setPath("{$this->route}/{$workId}/{$this->childRoute}{$section}")->appends($queries)->previousPageUrl(),
                    'next' => $catalogs->setPath("{$this->route}/{$workId}/{$this->childRoute}{$section}")->appends($queries)->nextPageUrl(),
                    'current' => $catalogs->currentPage(),
                    'first' => $catalogs->firstItem(),
                    'last' => $catalogs->lastPage(),
                    'total' => $catalogs->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'finances',
                    'child' => 'catalogs'
                ],
            ],

            'catalogs' => [
                'all' => $catalogs,
                'one' => $catalog
            ],

            'works' => [
                'one' => $work,
            ],

            'groups' => [
                'all' => $groups,
            ],

            'unities' => [
                'all' => $unities,
            ],

            'departures' => [
                'all' => $departures,
            ],

            'levels' => [
                'all' => $levels,
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $catalogs->count(),
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
        $redirect = $this->route.'/'.$workId.'/catalogs/save';

        $rules = [
            //'cto34_contractor' => 'required',
            'cto34_contract' => 'required',
        ];

        $messages = [
            //'cto34_contractor.required' => 'Contratista requerido.',
            'cto34_contract.required' => 'Contrato requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        try {

            $catalog = new Catalog();
            $catalog->setConnection('mysql-writer');

            //$catalog->tbDirEmpresaObraID_Contratista = $request->input('cto34_contractor');
            $catalog->tbContratoID_Catalogo = $request->input('cto34_contract');
            $catalog->CatalogoObraTipo = $request->input('cto34_workType');
            $catalog->CatalogoPresupuestoTipo = $request->input('cto34_bugetType');
            $catalog->tbPartidaID_Catalogo = $request->input('cto34_departure');
            $catalog->tbSubpartidaID_Catalogo = $request->input('cto34_subdeparture');
            $catalog->tbUbicaNivelID_Catalogo = (!empty($request->input('cto34_level'))) ? $request->input('cto34_level') : 0;
            $catalog->CatalogoConceptoCodigo = $request->input('cto34_code');
            $catalog->CatalogoDescripcion = $request->input('cto34_fullDescription');
            $catalog->CatalogoDescripcionCorta = $request->input('cto34_shortDescription');
            $catalog->tbUnidadesID_Catalogo = $request->input('cto34_unity');
            $catalog->CatalogoCantidad = $request->input('cto34_quantity');
            $catalog->CatalogoPrecioUnitario = $request->input('cto34_unitPrice');
            $catalog->CatalogoImporte = $request->input('cto34_amount');
            $catalog->CatalogoFolioExterno = $request->input('cto34_folioId');
            $catalog->CatalogoPresupuestoFecha = $request->input('cto34_bugetDate');
            $catalog->CatalogoConceptoStatus = $request->input('cto34_conceptStatus');
            $catalog->tbObraID_Catalogo = $workId;

            if (!$catalog->save()) {
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
    public function doUpdate(Request $request, $workId) {

        $id = $request->input('cto34_id');
        $hasSearch = $request->input('_hasSearch');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info/{$id}{$request->input('_query')}";
        $redirectError = URL::previous();

        if ($hasSearch)
            $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/search/{$id}{$request->input('_query')}";

        $rules = [
            //'cto34_contractor' => 'required',
            'cto34_contract' => 'required',
        ];

        $messages = [
            //'cto34_contractor.required' => 'Contratista requerido.',
            'cto34_contract.required' => 'Contrato requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $catalog = Catalog::find($id)->setConnection('mysql-writer');

        //$catalog->tbDirEmpresaObraID_Contratista = $request->input('cto34_contractor');
        $catalog->tbContratoID_Catalogo = $request->input('cto34_contract');
        $catalog->CatalogoObraTipo = $request->input('cto34_workType');
        $catalog->CatalogoPresupuestoTipo = $request->input('cto34_bugetType');
        $catalog->tbPartidaID_Catalogo = $request->input('cto34_departure');
        $catalog->tbSubpartidaID_Catalogo = $request->input('cto34_subdeparture');
        $catalog->tbUbicaNivelID_Catalogo = (!empty($request->input('cto34_level'))) ? $request->input('cto34_level') : 0;
        $catalog->CatalogoConceptoCodigo = $request->input('cto34_code');
        $catalog->CatalogoDescripcion = $request->input('cto34_fullDescription');
        $catalog->CatalogoDescripcionCorta = $request->input('cto34_shortDescription');
        $catalog->tbUnidadesID_Catalogo = $request->input('cto34_unity');
        $catalog->CatalogoCantidad = $request->input('cto34_quantity');
        $catalog->CatalogoPrecioUnitario = $request->input('cto34_unitPrice');
        $catalog->CatalogoImporte = $request->input('cto34_amount');
        $catalog->CatalogoFolioExterno = $request->input('cto34_folioId');
        $catalog->CatalogoPresupuestoFecha = $request->input('cto34_bugetDate');
        $catalog->CatalogoConceptoStatus = $request->input('cto34_conceptStatus');

        if (!$catalog->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puedo actualizar el catálogo, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Catálogo actualizado correctamente.');

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
            'cto34_id.required' => 'Id del oficio requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $catalog = Catalog::find($id)->setConnection('mysql-writer');

        if (!$catalog->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el catálogo, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Catálogo eliminado correctamente.');

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
            'cto34_id.required' => 'Id del oficio requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $catalog = Catalog::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$catalog->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el catálogo, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Catálogo restaurado correctamente.');
    }
}
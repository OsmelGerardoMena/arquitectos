<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Models\ConstructionWork;
use App\Models\Trade;
use App\Models\User;
use App\Models\Location;

use Illuminate\Http\Request;

class TradeController extends AppController
{
    private $route;
    private $childRoute = "trades";
    private $viewsPath = 'panel.constructionwork.trade.';
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
        $trades = Trade::orderBy('tbOficioID', 'DESC')
            ->where('tbObraID_Oficio', '=', $workId)
            ->paginate($this->paginate);
        $trade = $trades[0];

        if ($id != null)
            $trade = Trade::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Oficios",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $trades->links(),
                    'prev' => $trades->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $trades->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $trades->currentPage(),
                    'first' => $trades->firstItem(),
                    'last' => $trades->lastPage(),
                    'total' => $trades->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'trades'
                ],
            ],

            'trades' => [
                'all' => $trades,
                'one' => $trade
            ],

            'works' => [
                'one' => $work,
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];

        return view($this->viewsPath . 'index', $viewData);
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
        $trades = null;
        $trade = null;
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
                DB::raw("CONCAT_WS(' ', OficioFolio, OficioAsunto, OficioParrafo1)"), 'LIKE', "{$search}"
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

        $trades = Trade::orderBy('tbOficioID', 'DESC')
            ->where('tbObraID_Oficio', '=', $workId)
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $trades = Trade::onlyTrashed()
                ->orderBy('tbOficioID', 'DESC')
                ->where('tbObraID_Oficio', '=', $workId)
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($trades->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $trade = $trades[0];

        if ($id != null) {

            $trade = Trade::find($id);
            if ($hasDelete)
                $trade = Trade::withTrashed()->find($id);
        }

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Oficios",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $trades->links(),
                    'prev' => $trades->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->appends($queries)->previousPageUrl(),
                    'next' => $trades->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->appends($queries)->nextPageUrl(),
                    'current' => $trades->currentPage(),
                    'first' => $trades->firstItem(),
                    'last' => $trades->lastPage(),
                    'total' => $trades->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'trades'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'trades' => [
                'all' => $trades,
                'one' => $trade,
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $trades->count(),
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
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function showSave(Request $request, $workId)
    {

        $work = ConstructionWork::find($workId);
        $trades = Trade::orderBy('tbOficioID', 'DESC')
            ->where('tbObraID_Oficio', '=', $workId)
            ->paginate($this->paginate);
        $locations = Location::all();

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Oficios",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/save',
                'pagination' => [
                    'links' => $trades->links(),
                    'prev' => $trades->previousPageUrl(),
                    'next' => $trades->nextPageUrl(),
                    'current' => $trades->currentPage(),
                    'first' => $trades->firstItem(),
                    'last' => $trades->lastPage(),
                    'total' => $trades->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'trades'
                ],
            ],

            'trades' => [
                'all' => $trades,
            ],

            'works' => [
                'one' => $work,
            ],

            'locations' => [
                'all' => $locations,
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
        $trades = null;
        $trade = null;
        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', OficioFolio, OficioAsunto, OficioParrafo1)"), 'LIKE', "{$search}"
            ];

            $section = '/search';
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

            $section = '/search';

        }

        $trades = Trade::orderBy('tbOficioID', 'DESC')
            ->where('tbObraID_Oficio', '=', $workId)
            ->where($filters)
            ->paginate($this->paginate);

        $trade = $trades[0];
        $locations = Location::all();

        if ($id != null)
            $trade = Trade::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Oficios",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => $section,
                'pagination' => [
                    'links' => $trades->links(),
                    'prev' => $trades->setPath("{$this->route}/{$workId}/{$this->childRoute}{$section}")->appends($queries)->previousPageUrl(),
                    'next' => $trades->setPath("{$this->route}/{$workId}/{$this->childRoute}{$section}")->appends($queries)->nextPageUrl(),
                    'current' => $trades->currentPage(),
                    'first' => $trades->firstItem(),
                    'last' => $trades->lastPage(),
                    'total' => $trades->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'trades'
                ],
            ],

            'trades' => [
                'all' => $trades,
                'one' => $trade
            ],

            'works' => [
                'one' => $work,
            ],

            'locations' => [
                'all' => $locations
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $trades->count(),
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
        $hashtag = $request->input('_hashtag');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info{$hashtag}";
        $redirectError = "{$this->route}/{$workId}/{$this->childRoute}/save{$hashtag}";

        $rules = [
            'cto34_location' => 'required',
            'cto34_destinationPerson' => 'required'
        ];

        $messages = [
            'cto34_location.required' => 'Localidad requerida',
            'cto34_destinationPerson.required' => 'Persona destintaria requerida',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $work = ConstructionWork::find($workId);
        $trades = Trade::withTrash()->where('tbObraID_Oficio', '=', $workId)->get();
        $consecutive = str_pad($trades->count() + 1, 4, "0", STR_PAD_LEFT);
        $folio = "{$work->ObraClave}-Oficio-{$consecutive}";

        $trade = new Trade();
        $trade->setConnection('mysql-writer');

        $trade->OficioFolio = $folio;
        $trade->tbLocalidadID_OficioLocalidadExpedicion = $request->input('cto34_location');
        $trade->OficioFechaExpedicion = $request->input('cto34_expeditionDate');
        $trade->tbDirPersonaEmpresaObraID_OficioDestinatario = $request->input('cto34_destinationPerson');
        //$trade->tbDirEmpresaMiEmpresaID_OficioDestinatario = !empty($request->input('cto34_destinationBusiness')) ? $request->input('cto34_destinationBusiness') : 0;
        $trade->OficioAsunto = $request->input('cto34_subject');
        $trade->OficioCuerpo = $request->input('cto34_paragraphs1');
        //$trade->OficioParrafo2 = $request->input('cto34_paragraphs2');
        $trade->OficioSaludo = $request->input('cto34_welcome');
        $trade->tbDirPersonaEmpresaObraID_OficioRemitente = !empty($request->input('cto34_sender')) ? $request->input('cto34_sender') : 0;
        $trade->tbOficioAdjuntoID_Oficio = $request->input('cto34_attachments');
        $trade->OficioCCP = $request->input('cto34_copy');
        $trade->tbDirPersonaEmpresaObraID_OficioRecibidoPor = !empty($request->input('cto34_receiver')) ? $request->input('cto34_receiver') : 0;
        $trade->OficioRecibidoFecha = $request->input('cto34_receiverDate');
        //$trade->OficioSeguimiento = !empty($request->input('cto34_follow')) ? 1 : 0;
        //$trade->OficioAsuntoCerradoFecha = $request->input('cto34_subjectCloseDate');
        $trade->RegistroUsuario = \Auth::id();
        $trade->created_at = date('Y-m-d H:i');
        $trade->tbObraID_Oficio = $workId;

        if (!$trade->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede guardar el oficio, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Oficio agregado correctamente.');
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
            'cto34_location' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de oficio requerido',
            'cto34_location.required' => 'Localidad requerida',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $trade = Trade::find($request->input('cto34_id'))->setConnection('mysql-writer');

        $trade->tbLocalidadID_OficioLocalidadExpedicion = $request->input('cto34_location');
        $trade->OficioFechaExpedicion = $request->input('cto34_expeditionDate');
        $trade->tbDirPersonaEmpresaObraID_OficioDestinatario = $request->input('cto34_destinationPerson');
        //$trade->tbDirEmpresaMiEmpresaID_OficioDestinatario = !empty($request->input('cto34_destinationBusiness')) ? $request->input('cto34_destinationBusiness') : 0;
        $trade->OficioAsunto = $request->input('cto34_subject');
        $trade->OficioCuerpo = $request->input('cto34_paragraphs1');
        //$trade->OficioParrafo2 = $request->input('cto34_paragraphs2');
        $trade->OficioSaludo = $request->input('cto34_welcome');
        $trade->tbDirPersonaEmpresaObraID_OficioRemitente = !empty($request->input('cto34_sender')) ? $request->input('cto34_sender') : 0;
        $trade->tbOficioAdjuntoID_Oficio = $request->input('cto34_attachments');
        $trade->OficioCCP = $request->input('cto34_copy');
        $trade->tbDirPersonaEmpresaObraID_OficioRecibidoPor = !empty($request->input('cto34_receiver')) ? $request->input('cto34_receiver') : 0;
        $trade->OficioRecibidoFecha = $request->input('cto34_receiverDate');
        //$trade->OficioSeguimiento = !empty($request->input('cto34_follow')) ? 1 : 0;
        //$trade->OficioAsuntoCerradoFecha = $request->input('cto34_subjectCloseDate');
        $trade->RegistroUsuarioModifico = \Auth::id();
        $trade->updated_at = date('Y-m-d H:i');
        $trade->tbObraID_Oficio = $workId;

        if (!$trade->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede actualizar el oficio, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Oficio actualizado correctamente.');

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

        $trade = Trade::find($id)->setConnection('mysql-writer');

        if (!$trade->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el oficio, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Oficio eliminado correctamente.');

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

        $trade = Trade::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$trade->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el oficio, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Oficio restaurado correctamente.');
    }
}
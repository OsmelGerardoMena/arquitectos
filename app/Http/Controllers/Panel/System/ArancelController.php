<?php
namespace App\Http\Controllers\Panel\System;
use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Arancel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArancelController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.arancel.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/arancel');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {

        $arancelAll = Arancel::orderBy('TbArancelID', 'DESC')->paginate($this->paginate);
        $arancel = $arancelAll[0];

        if ($id != null)
            $arancel = Arancel::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Arancel',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $arancelAll->links(),
                    'prev' => $arancelAll->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $arancelAll->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $arancelAll->currentPage(),
                    'first' => $arancelAll->firstItem(),
                    'last' => $arancelAll->lastPage(),
                    'total' => $arancelAll->count()
                ],
            ],

            'arancel' => [
                'all' => $arancelAll,
                'one' => $arancel
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];

        return view($this->viewsPath.'index', $viewData);
    }


    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showSearch(Request $request, $id = null)
    {
        $arancelAll = null;
        $arancel = null;
        $redirect = "{$this->route}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;

        if (!$request->has('filter')) {
            if (!$request->has('q') || empty($request->q)) {
                return redirect($redirect)
                    ->withErrors(['Se debe ingresar un nombre válido'])
                    ->withInput();
            }
        }

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', ArancelMaximo,ArancelMedio,ArancelMinimo,ArancelNivel,ArancelSupervision,ArancelConstruccion,ArancelEstudioYProyecto,ArancelEscolaridad)"), 'LIKE', "{$search}"
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

        $arancelAll = Arancel::orderBy('TbArancelID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $arancelAll = Arancel::onlyTrashed()
                ->orderBy('TbArancelID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($arancelAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $arancel = $arancelAll[0];

        if ($id != null) {

            $arancel = Arancel::find($id);

            if ($hasDelete)
                $arancel = Arancel::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Arancel',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $arancelAll->links(),
                    'prev' => $arancelAll->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $arancelAll->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $arancelAll->currentPage(),
                    'first' => $arancelAll->firstItem(),
                    'last' => $arancelAll->lastPage(),
                    'total' => $arancelAll->count()
                ],
            ],

            'arancel' => [
                'all' => $arancelAll,
                'one' => $arancel
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $arancelAll->count(),
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
    public function showSave(Request $request)
    {

        $arancelAll = Arancel::orderBy('TbArancelID', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Arancel',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $arancelAll->links(),
                    'prev' => $arancelAll->setPath("{$this->route}/save")->previousPageUrl(),
                    'next' => $arancelAll->setPath("{$this->route}/save")->nextPageUrl(),
                    'current' => $arancelAll->currentPage(),
                    'first' => $arancelAll->firstItem(),
                    'last' => $arancelAll->lastPage(),
                    'total' => $arancelAll->count()
                ],
            ],

            'arancel' => [
                'all' => $arancelAll
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
    public function showUpdate(Request $request, $id = null)
    {

        $arancelAll = null;
        $arancel = null;

        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', ArancelMaximo,ArancelMedio,ArancelMinimo,ArancelNivel,ArancelSupervision,ArancelConstruccion,ArancelEstudioYProyecto,ArancelEscolaridad)"), 'LIKE', "{$search}"
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

                case 'deleted':
                    $hasDelete = true;
                    break;

                default:
                    break;
            }

            $section = '/search';
        }

        $arancelAll = Arancel::orderBy('TbArancelID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        $arancel = $arancelAll[0];

        if ($id != null)
            $arancel = Arancel::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Arancel',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $arancelAll->links(),
                    'prev' => $arancelAll->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $arancelAll->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $arancelAll->currentPage(),
                    'first' => $arancelAll->firstItem(),
                    'last' => $arancelAll->lastPage(),
                    'total' => $arancelAll->count()
                ],
            ],

            'arancel' => [
                'all' => $arancelAll,
                'one' => $arancel
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $arancelAll->count(),
                'query' => $request->q,
            ]
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
            'cto34_level' => 'required',
            'cto34_min' => 'required',
            'cto34_med' => 'required',
            'cto34_max' => 'required',
            'cto34_supervision' => 'required',
            'cto34_construction' => 'required',
            'cto34_study' => 'required',
            'cto34_scholarship' => 'required',
        ];
   
        $messages = [
            'cto34_level.required' => 'Nivel requerido.',
            'cto34_min.required' => 'Mínimo requerido.',
            'cto34_med.required' => 'Medio requerido.',
            'cto34_max.required' => 'Maximo requerido.',
            'cto34_supervision.required' => 'Supervisión requerida.',
            'cto34_construction.required' => 'Construcción requerida.',
            'cto34_study.required' => 'Estudio y proyecto requerido.',
            'cto34_scholarship.required' => 'Escolaridad requerida.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $arancel = new Arancel();
        $arancel->setConnection('mysql-writer');

       
        $arancel->ArancelNivel = $request->input('cto34_level');
        $arancel->ArancelMinimo = $request->input('cto34_min');
        $arancel->ArancelMedio = $request->input('cto34_med');
        $arancel->ArancelMaximo = $request->input('cto34_max');
        $arancel->ArancelSupervision = $request->input('cto34_supervision');
        $arancel->ArancelConstruccion = $request->input('cto34_construction');
        $arancel->ArancelEstudioYProyecto = $request->input('cto34_study');
        $arancel->ArancelEscolaridad = $request->input('cto34_scholarship');

        if( $request->input('cto34_closed') == 1 ){
            $arancel->RegistroCerrado = 1;
            $arancel->tbCTOUsuarioID_Rol = auth_permissions_id(Auth::user()['role']);
        }


        if (!$arancel->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar el arancel, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Arancel agregado correctamente.');
    }

    /**
     * Update person
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doUpdate(Request $request) {

        $id = $request->input('cto34_id');
        $hasSearch = $request->input('_hasSearch');
        $redirectSuccess = "{$this->route}/info/{$id}{$request->input('_query')}";
        $redirectError = URL::previous();

        if ($hasSearch)
            $redirectSuccess = "{$this->route}/search/{$id}{$request->input('_query')}";

        $rules = [
            'cto34_id' => 'required'
        ];
   
        $messages = [
            'cto34_id.required' => 'Id arancel requerido.',

        ];


        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }

        $arancel = Arancel::find($id)->setConnection('mysql-writer');
       
        $arancel->ArancelNivel = $request->input('cto34_level');
        $arancel->ArancelMinimo = $request->input('cto34_min');
        $arancel->ArancelMedio = $request->input('cto34_med');
        $arancel->ArancelMaximo = $request->input('cto34_max');
        $arancel->ArancelSupervision = $request->input('cto34_supervision');
        $arancel->ArancelConstruccion = $request->input('cto34_construction');
        $arancel->ArancelEstudioYProyecto = $request->input('cto34_study');
        $arancel->ArancelEscolaridad = $request->input('cto34_scholarship');
        $arancel->RegistroCerrado = $request->input('cto34_closed');


        if (!$arancel->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puedo actualizar el arancel, intente nuevamente'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Arancel actualizado correctamente');
        
    }

    /**
     * Delete person
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doDelete(Request $request) {

        $id = $request->input('cto34_id');
        $redirect = "{$this->route}/info";

        $rules = [
            'cto34_id' => 'required',
        ];
   
        $messages = [
            'cto34_id.required' => 'Id de el arancel requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $arancel = Arancel::find($id)->setConnection('mysql-writer');

        if (!$arancel->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar el arancel, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Arancel <b>'.$arancel->ArancelSupervision.'</b> eliminado correctamente.');
        
    }


    /**
     * Do Restore
     * Realiza la acción de restaurar un registro eliminado
     *
     * @param $request Request
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function doRestore(Request $request) {

        $id = $request->input('cto34_id');
        $redirectSuccess = "{$this->route}/info";
        $redirectError = URL::previous();

        $rules = [
            'cto34_id' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de el arancel requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $user = Arancel::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$user->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el arancel, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Arancel restaurado correctamente.');
    }
}
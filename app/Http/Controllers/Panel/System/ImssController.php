<?php
namespace App\Http\Controllers\Panel\System;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Imss;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImssController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.imss.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/imss');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {
        $imssAll = Imss::orderBy('tbIMSSID', 'DESC')->paginate($this->paginate);
        $imss = $imssAll[0];

        if ($id != null)
            $imss = Imss::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Imss',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $imssAll->links(),
                    'prev' => $imssAll->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $imssAll->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $imssAll->currentPage(),
                    'first' => $imssAll->firstItem(),
                    'last' => $imssAll->lastPage(),
                    'total' => $imssAll->count()
                ],
            ],

            'imss' => [
                'all' => $imssAll,
                'one' => $imss
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

        $imssAll = null;
        $imss = null;
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
                DB::raw("CONCAT_WS(' ', IMSSeguro,IMSSPrestaciones,IMSSCuotaTipo,IMSSCuotaPatron,IMSSCuotaTrabajador,IMSSBase)"), 'LIKE', "{$search}"
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

        $imssAll = Imss::orderBy('tbIMSSID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $imssAll = Imss::onlyTrashed()
                ->orderBy('tbIMSSID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($imssAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $imss = $imssAll[0];

        if ($id != null) {

            $imss = Imss::find($id);

            if ($hasDelete)
                $imss = Imss::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Imss',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $imssAll->links(),
                    'prev' => $imssAll->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $imssAll->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $imssAll->currentPage(),
                    'first' => $imssAll->firstItem(),
                    'last' => $imssAll->lastPage(),
                    'total' => $imssAll->count()
                ],
            ],

            'imss' => [
                'all' => $imssAll,
                'one' => $imss
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $imssAll->count(),
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


        $imssAll = Imss::orderBy('tbIMSSID', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Imss',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $imssAll->links(),
                    'prev' => $imssAll->setPath("{$this->route}/save")->previousPageUrl(),
                    'next' => $imssAll->setPath("{$this->route}/save")->nextPageUrl(),
                    'current' => $imssAll->currentPage(),
                    'first' => $imssAll->firstItem(),
                    'last' => $imssAll->lastPage(),
                    'total' => $imssAll->count()
                ],
            ],

            'imss' => [
                'all' => $imssAll
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
        $imssAll = null;
        $imss = null;

        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', IMSSeguro,IMSSPrestaciones,IMSSCuotaTipo,IMSSCuotaPatron,IMSSCuotaTrabajador,IMSSBase)"), 'LIKE', "{$search}"
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

        $imssAll = Imss::orderBy('tbIMSSID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        $imss = $imssAll[0];

        if ($id != null)
            $imss = Imss::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Imss',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $imssAll->links(),
                    'prev' => $imssAll->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $imssAll->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $imssAll->currentPage(),
                    'first' => $imssAll->firstItem(),
                    'last' => $imssAll->lastPage(),
                    'total' => $imssAll->count()
                ],
            ],

            'imss' => [
                'all' => $imssAll,
                'one' => $imss
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $imssAll->count(),
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
            'cto34_seguro' => 'required',
            'cto34_prestaciones' => 'required',
            'cto34_cuota_tipo' => 'required',
            'cto34_cuota_patron' => 'required',
            'cto34_cuota_trabajador' => 'required',
            'cto34_base' => 'required',
            ];
   
        $messages = [
            'cto34_seguro.required' => 'Seguro requerido.',
            'cto34_prestaciones.required' => 'Prestaciones requerido.',
            'cto34_cuota_tipo.required' => 'Cuota tipo requerido.',
            'cto34_cuota_patron.required' => 'Cuota trabajador requerido.',
            'cto34_cuota_trabajador.required' => 'Cuota patrón requerido.',
            'cto34_base.required' => 'Base requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $imss = new Imss();
        $imss->setConnection('mysql-writer');

       
        $imss->IMSSeguro = $request->input('cto34_seguro');
        $imss->IMSSPrestaciones = $request->input('cto34_prestaciones');
        $imss->IMSSCuotaTipo = $request->input('cto34_cuota_tipo');
        $imss->IMSSCuotaPatron = $request->input('cto34_cuota_patron');
        $imss->IMSSCuotaTrabajador = $request->input('cto34_cuota_trabajador');
        $imss->IMSSBase = $request->input('cto34_base');

        if( $request->input('cto34_status') == 1 ){
            $imss->RegistroCerrado = 1;
            $imss->tbCTOUsuarioID_Rol = auth_permissions_id(Auth::user()['role']);
        }


        if (!$imss->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar el registro de Imss, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Imss agregado correctamente.');
    }

    /**
     * Update person
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doUpdate(Request $request) 
    {


        $id = $request->input('cto34_id');
        $hasSearch = $request->input('_hasSearch');
        $redirectSuccess = "{$this->route}/info/{$id}{$request->input('_query')}";
        $redirectError = URL::previous();

        if ($hasSearch)
            $redirectSuccess = "{$this->route}/search/{$id}{$request->input('_query')}";

        $rules = [
            'cto34_id' => 'required',
            'cto34_seguro' => 'required',
            'cto34_prestaciones' => 'required',
            'cto34_cuota_tipo' => 'required',
            'cto34_cuota_patron' => 'required',
            'cto34_cuota_trabajador' => 'required',
            'cto34_base' => 'required',
        ];
   
        $messages = [
            'cto34_id.required' => 'Id imss requerido.',
            'cto34_seguro.required' => 'Numero de imss requerido.',
            'cto34_prestaciones.required' => 'Prestaciones requerido.',
            'cto34_cuota_tipo.required' => 'Cuota tipo requerido.',
            'cto34_cuota_patron.required' => 'Cuota patron requerido.',
            'cto34_cuota_trabajador.required' => 'Cuota trabajador requerido.',
            'cto34_base.required' => 'Base requerido.',

        ];


        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }

        $imss = Imss::find($id)->setConnection('mysql-writer');
       
        $imss->IMSSeguro = $request->input('cto34_seguro');
        $imss->IMSSPrestaciones = $request->input('cto34_prestaciones');
        $imss->IMSSCuotaTipo = $request->input('cto34_cuota_tipo');
        $imss->IMSSCuotaPatron = $request->input('cto34_cuota_patron');
        $imss->IMSSCuotaTrabajador = $request->input('cto34_cuota_trabajador');
        $imss->IMSSBase = $request->input('cto34_base');



        if (!$imss->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puedo actualizar el registro de imss, intente nuevamente'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Imss actualizado correctamente');
        
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
            'cto34_id.required' => 'Id imss requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $imss = Imss::find($id)->setConnection('mysql-writer');

        if (!$imss->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar el registro de imss, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Arancel <b>'.$imss->IMSSeguro.'</b> eliminado correctamente.');
        
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
            'cto34_id.required' => 'Id imss requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $imss = Imss::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$imss->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el registro de imss, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Registro de imss restaurado correctamente.');
    }
}
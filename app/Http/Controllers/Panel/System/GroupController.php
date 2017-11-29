<?php
namespace App\Http\Controllers\Panel\System;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Group;
use App\Models\Business;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.group.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/groups');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {

        $groupAll = Group::orderBy('tbDirGrupoID', 'DESC')->paginate($this->paginate);
        $group = $groupAll[0];

        if ($id != null)
            $group = Group::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Grupos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $groupAll->links(),
                    'prev' => $groupAll->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $groupAll->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $groupAll->currentPage(),
                    'first' => $groupAll->firstItem(),
                    'last' => $groupAll->lastPage(),
                    'total' => $groupAll->count()
                ],
            ],

            'groups' => [
                'all' => $groupAll,
                'one' => $group
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

        $groupAll = null;
        $group = null;
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
                DB::raw("CONCAT_WS(' ', DirGrupoNombre,DirGrupoDescripcion)"), 'LIKE', "{$search}"
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

                case 'inactives':
                    $filters[] = [
                        'RegistroInactivo', '=', 1
                    ];
                    break;

                case 'deleted':
                    $hasDelete = true;
                    break;

                default:
                    break;
            }

        }

        $groupAll = Group::orderBy('tbDirGrupoID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $groupAll = Group::onlyTrashed()
                ->orderBy('tbDirGrupoID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($groupAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $group = $groupAll[0];

        if ($id != null) {

            $group = Group::find($id);

            if ($hasDelete)
                $group = Group::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Grupos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $groupAll->links(),
                    'prev' => $groupAll->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $groupAll->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $groupAll->currentPage(),
                    'first' => $groupAll->firstItem(),
                    'last' => $groupAll->lastPage(),
                    'total' => $groupAll->count()
                ],
            ],

            'groups' => [
                'all' => $groupAll,
                'one' => $group
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $groupAll->count(),
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

        $groupAll = Group::orderBy('tbDirGrupoID', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Grupos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $groupAll->links(),
                    'prev' => $groupAll->setPath("{$this->route}/save")->previousPageUrl(),
                    'next' => $groupAll->setPath("{$this->route}/save")->nextPageUrl(),
                    'current' => $groupAll->currentPage(),
                    'first' => $groupAll->firstItem(),
                    'last' => $groupAll->lastPage(),
                    'total' => $groupAll->count()
                ],
            ],

            'groups' => [
                'all' => $groupAll
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
        $groupAll = null;
        $group = null;

        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', DirGrupoNombre,DirGrupoDescripcion)"), 'LIKE', "{$search}"
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

                case 'inactives':
                    $filters[] = [
                        'RegistroInactivo', '=', 1
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

        $groupAll = Group::orderBy('tbDirGrupoID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        $group = $groupAll[0];

        if ($id != null)
            $arancel = Group::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Grupos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $groupAll->links(),
                    'prev' => $groupAll->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $groupAll->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $groupAll->currentPage(),
                    'first' => $groupAll->firstItem(),
                    'last' => $groupAll->lastPage(),
                    'total' => $groupAll->count()
                ],
            ],

            'groups' => [
                'all' => $groupAll,
                'one' => $group
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $groupAll->count(),
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
            'cto34_name' => 'required',
            'cto34_description' => 'required',
        ];

        $messages = [
            'cto34_name.required' => 'Nombre requerido.',
            'cto34_description.required' => 'Descripción requerida.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $group = new Group();
        $group->setConnection('mysql-writer');

        $group->DirGrupoNombre = $request->input('cto34_name');
        $group->DirGrupoDescripcion = $request->input('cto34_description');

        if($request->input('cto34_status') == 1 ){
            $group->RegistroInactivo = 1;
        }

        if( $request->input('cto34_closed') == 1 ){
            $group->RegistroCerrado = 1;
            //$arancel->tbCTOUsuarioID_Rol = auth_permissions_id(Auth::user()['role']);
        }

        if (!$group->save()) {
            return redirect($redirect)
                ->withErrors(['No se puede guarda el grupo, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Grupo agregado correctamente.');
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
            'cto34_name' => 'required',
            'cto34_description' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de grupo requerido.',
            'cto34_name.required' => 'Nombre requerido.',
            'cto34_description.required' => 'Descripción requerida.',
        ];


        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }

       $group = Group::find($id)->setConnection('mysql-writer');

        $group->DirGrupoNombre = $request->input('cto34_name');
        $group->DirGrupoDescripcion = $request->input('cto34_description');

        if($request->input('cto34_status') == 1 ){
            $group->RegistroInactivo = 1;
        }else{
            $group->RegistroInactivo = 0;
        }

        if (!$group->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puedo actualizar el grupo, intente nuevamente'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Grupo actualizado correctamente');

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
            'cto34_id.required' => 'Id de el grupo requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $group = Group::find($id)->setConnection('mysql-writer');

        if (!$group->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar el grupo, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Grupo <b>'.$group->DirGrupoNombre.'</b> eliminado correctamente.');

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
            'cto34_id.required' => 'Id de el grupo requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $group = Group::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$group->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el grupo, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Grupo restaurado correctamente.');
    }

}
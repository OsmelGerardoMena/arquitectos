<?php

namespace App\Http\Controllers\Panel\System;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.user.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/users');
    }

    /**
     * Show Index
     * Página principal de la sección
     *
     * @param $request Request
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null) {

        $users = User::orderBy('tbCTOUsuarioID', 'DESC')->paginate($this->paginate);
        $user = $users[0];

        if ($id != null)
            $user = User::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Usuarios',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $users->links(),
                    'prev' => $users->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $users->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $users->currentPage(),
                    'first' => $users->firstItem(),
                    'last' => $users->lastPage(),
                    'total' => $users->count()
                ],
            ],

            'users' => [
                'all' => $users,
                'one' => $user
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
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showSearch(Request $request, $id = null)
    {
        $users = null;
        $user = null;
        $redirect = "{$this->route}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;

        if (!$request->has('filter')) {
            if (!$request->has('q') || empty($request->q)) {
                return redirect($redirect)
                    ->withErrors(['Se debe ingresar un nombre de usuario'])
                    ->withInput();
            }
        }

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', CTOUsuarioNombre)"), 'LIKE', "{$search}"
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

        $users = User::orderBy('tbCTOUsuarioID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $users = User::onlyTrashed()
                ->orderBy('tbCTOUsuarioID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($users->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $user = $users[0];

        if ($id != null) {

            $user = User::find($id);

            if ($hasDelete)
                $user = User::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Usuarios',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $users->links(),
                    'prev' => $users->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $users->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $users->currentPage(),
                    'first' => $users->firstItem(),
                    'last' => $users->lastPage(),
                    'total' => $users->count()
                ],
            ],

            'users' => [
                'all' => $users,
                'one' => $user
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $users->count(),
                'query' => $request->q,
            ]
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Show Save
     * Muestra la vista para guardar un registro
     *
     * @return \Illuminate\Http\Response
     */
    public function showSave(Request $request)
    {

        $users = User::orderBy('tbCTOUsuarioID', 'DESC')->paginate($this->paginate);
        $roles = Role::all();

        $viewData = [
            'page' => [
                'title' => 'Sistema / Usuarios',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/save',
                'pagination' => [
                    'links' => $users->links(),
                    'prev' => $users->setPath("{$this->route}/save")->previousPageUrl(),
                    'next' => $users->setPath("{$this->route}/save")->nextPageUrl(),
                    'current' => $users->currentPage(),
                    'first' => $users->firstItem(),
                    'last' => $users->lastPage(),
                    'total' => $users->count()
                ],
            ],

            'users' => [
                'all' => $users
            ],

            'roles' => [
                'all' => $roles
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];

        return view($this->viewsPath.'save', $viewData);
    }

    /**
     * Show Update
     * Muestra la vista para actualizar un registro
     *
     * @param $id int Id del registro a actualizar
     * @return \Illuminate\Http\Response
     */
    public function showUpdate(Request $request, $id = null)
    {
        $users = null;
        $user = null;
        $roles = Role::all();
        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', CTOUsuarioNombre)"), 'LIKE', "{$search}"
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

        $users = User::orderBy('tbCTOUsuarioID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        $user = $users[0];

        if ($id != null)
            $user = User::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Usuarios',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $users->links(),
                    'prev' => $users->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $users->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $users->currentPage(),
                    'first' => $users->firstItem(),
                    'last' => $users->lastPage(),
                    'total' => $users->count()
                ],
            ],

            'users' => [
                'all' => $users,
                'one' => $user
            ],

            'roles' => [
                'all' => $roles
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $users->count(),
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

        $hashtag = $request->input('_hashtag');
        $redirectSuccess = "{$this->route}/info{$hashtag}";
        $redirectError = "{$this->route}/save{$hashtag}";

        $rules = [
            'cto34_user' => 'required|unique:mysql-reader.tbCTOUsuario,CTOUsuarioNombre|max:12|min:6',
            'cto34_password' => 'required',
            'cto34_searchPerson' => 'required',
            'cto34_role' => 'not_in:0',
        ];
   
        $messages = [
            'cto34_user.required' => 'Usuario requerido',
            'cto34_user.unique' => 'Ya existe un usuario con ese nombre',
            'cto34_user.min' => 'Campo usuario debe tener mínimo 6 caracteres',
            'cto34_user.max' => 'Campo usuario solo permite máximo 12 caracteres',
            'cto34_searchPerson.required' => 'Persona requerida',
            'cto34_password.required' => 'Contraseña requerida',
            'cto34_role.not_in' => 'Seleccionar grupo',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = new User();
        $user->setConnection('mysql-writer');

        $user->CTOUsuarioNombre = $request->input('cto34_user');
        $user->CTOUsuarioContrasena = hash('SHA512', 'G$·ng042EWNVewkvnosv"NVi02o3{'.$request->input('cto34_password').'}NF3oinfEFNioenf03');
        $user->SecureToken = hash('SHA512', uniqid().time());
        $user->SecureKey = hash('SHA512', $request->input('cto34_user'));
        $user->tbDirPersonaID_CTOUsuario = $request->input('cto34_searchPerson');
        $user->tbCTOUsuarioGrupoID_CTOUsuario = $request->input('cto34_role');

        if (!$user->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puedo guardar el usuario, intente nuevamente'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Usuario agregado correctamente');
    }

    /**
     * Do Update
     * Realiza la acción de actualizar un registro
     *
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
            'cto34_user' => 'required|max:12|min:6',
            'cto34_searchPerson' => 'required',
            'cto34_role' => 'not_in:0',
        ];

        $messages = [
            'cto34_user.required' => 'Usuario requerido',
            'cto34_user.min' => 'Campo usuario debe tener mínimo 6 caracteres',
            'cto34_user.max' => 'Campo usuario solo permite máximo 12 caracteres',
            'cto34_searchPerson.required' => 'Persona requerida',
            'cto34_role.not_in' => 'Seleccionar grupo',
        ];

        if ($request->input('cto34_user') != $request->input('cto34_userOld')) {

            $rules = [
                'cto34_user' => 'unique:mysql-reader.tbCTOUsuario,CTOUsuarioNombre',
            ];

            $messages = [
                'cto34_user.unique' => 'Ya existe un usuario con ese nombre',
            ];

        }

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = User::find($id)->setConnection('mysql-writer');
        $user->tbDirPersonaID_CTOUsuario = $request->input('cto34_searchPerson');
        $user->tbCTOUsuarioGrupoID_CTOUsuario = $request->input('cto34_role');

        if (strlen($request->input('cto34_password')) > 0) {
            $user->CTOUsuarioContrasena = hash('SHA512', 'G$·ng042EWNVewkvnosv"NVi02o3{'.$request->input('cto34_password').'}NF3oinfEFNioenf03');
        }

        if (!$user->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puedo actualizar el usuario, intente nuevamente'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Usuario actualizado correctamente');
        
    }

    /**
     * Update user passwotd
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doUpdatePassword(Request $request) {

        $id = $request->input('cto34_id');
        $redirect = URL::previous();

        $rules = [
            'cto34_id' => 'required',
            'cto34_pass' => 'required|confirmed',
        ];
   
        $messages = [
            'cto34_id.required' => 'Id de usuario requerido',
            'cto34_pass.required' => 'Contraseña requerida',
            'cto34_pass.confirmed' => 'Las contraseñas no coinciden',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = User::find($id)->setConnection('mysql-writer');
        $user->CTOUsuarioContrasena = hash('SHA512', 'G$·ng042EWNVewkvnosv"NVi02o3{'.$request->input('cto34_pass').'}NF3oinfEFNioenf03');

        if (!$user->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puedo actualizar el usuario, intente nuevamente'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Contrasea de Usuario actualizado correctamente');
        
    }

    /**
     * Do Delete
     * Realiza la acción de eliminar un registro
     *
     * @param $request Request
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function doDelete(Request $request) {

        $id = $request->input('cto34_id');
        $redirect = "{$this->route}/info";

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

        $user = User::find($id)->setConnection('mysql-writer');

        if (!$user->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el usuario, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Usuario eliminado correctamente.');

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
            'cto34_id.required' => 'Id del oficio requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$user->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el usuario, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Usuario restaurado correctamente.');
    }
}
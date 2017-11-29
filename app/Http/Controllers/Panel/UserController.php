<?php

namespace App\Http\Controllers\Panel;

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
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request)
    {
        $users = User::paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Usuarios',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $users->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'users' => [
                'all' => $users,
            ],
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Info user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showInfo(Request $request, $id)
    {
        $users = User::paginate($this->paginate);
        $user = User::find($id);

        $viewData = [
            'page' => [
                'title' => 'Usuarios',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $users->setPath("{$this->route}")->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'users' => [
                'all' => $users,
                'one' => $user,
            ],
        ];

        return view($this->viewsPath.'info', $viewData);
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showSearch(Request $request)
    {
        $redirect = "{$this->route}";

        if (!$request->has('q') || empty($request->q)) {
            return redirect($redirect)
                        ->withErrors(['Se debe ingresar un nombre de usuario'])
                        ->withInput();
        }

        $search = $request->q;
        $users = User::where('CTOUsuarioNombre', 'LIKE', "%{$search}%")->paginate($this->paginate);

        if ($users->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$search.'</b>');
        }

        $viewData = [
            'page' => [
                'title' => 'Usuarios / Busqueda',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $users->appends(['q' => $search])->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'users' => [
                'all' => $users,
            ],

            'search' => [
                'count' => $users->count(),
                'query' => $search,
            ]
        ];

        return view($this->viewsPath.'search', $viewData);
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showSearchInfo(Request $request, $id)
    {
        $redirect = "{$this->route}";

        if (!$request->has('q') || empty($request->q)) {
            return redirect($redirect)
                        ->withErrors(['Debe ingresar un nombre de usuario'])
                        ->withInput();
        }

        $search = $request->q;
        $users = User::where('CTOUsuarioNombre', 'LIKE', "%{$search}%")->paginate($this->paginate);
        $user = User::find($id);

        $viewData = [
            'page' => [
                'title' => 'Usuarios / Busqueda',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $users->setPath("{$this->route}/search")->appends(['q' => $search])->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'users' => [
                'all' => $users,
                'one' => $user,
            ],

            'search' => [
                'count' => $users->count(),
                'query' => $search,
            ]
        ];

        return view($this->viewsPath.'search-info', $viewData);
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showFilter(Request $request)
    {
        $redirect = "{$this->route}";
        $users = null;

        if (!$request->has('by') || empty($request->by)) {
            return redirect($redirect)
                        ->withErrors(['Se debe elegir un filtro'])
                        ->withInput();
        }

        $filter = $request->by;

        switch ($filter) {
            case 'active':
                $filter = "Activos";
                $users = User::where('RegistroInactivo', '=', 1)->paginate($this->paginate);
                break;

            case 'inactive':
                $filter = "Inactivos";
                $users = User::where('RegistroInactivo', '=', 0)->paginate($this->paginate);
                break;

            case 'deleted':
                $filter = "Eliminados";
                $users = User::onlyTrashed()->paginate($this->paginate);
                break;
            
            default:
                break;
        }

        if ($users->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu filtro: '.$filter);
        }

        $viewData = [
            'page' => [
                'title' => 'Usuarios / Filtrar',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $users->appends(['by' => $filter])->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'users' => [
                'all' => $users,
            ],

            'filter' => [
                'by' => $filter,
                'query' => $request->by,
            ], 

            'search' => [
                'count' => $users->count(),
                'query' => $filter,
            ]
        ];

        return view($this->viewsPath.'filter', $viewData);
    }

    /**
     * Update user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showSave()
    {

        $roles = Role::all();

        $viewData = [
            'page' => [
                'title' => 'Nuevo usuario',
            ],

            'navigation' => [
                'base' => $this->route,
            ],

            'roles' => [
                'all' => $roles,
            ]
        ];

        return view($this->viewsPath.'save', $viewData);
    }

    /**
     * Update user front
     *
     * @param int id - user id
     * @return \Illuminate\Http\Response
     */
    public function showUpdate(Request $request, $id)
    {
        $route = "{$this->route}";
        $navigationFrom = "info";
        $search = '';
        $hasSearch = false;
        $users = null;
        $queryString = ($request->has('page')) ? "?page={$request->page}" : '';

        // Url has param 'q' from search
        if ($request->has('q')) {
            
            $search = $request->q;
            $hasSearch = true;
            $route = "{$this->route}/search?q={$search}";
            $navigationFrom = "search";
            $queryString = ($request->has('page')) ? "?page={$request->page}&q={$search}" : "?q={$search}";

            $users = User::where('CTOUsuarioNombre', 'LIKE', "%{$search}%")->paginate($this->paginate);

        } else {
            $users = User::paginate($this->paginate);
        }
        
        $user = User::find($id);
        $roles = Role::all();

        $viewData = [
            'page' => [
                'title' => 'Usuarios / Editar',
            ],

            'navigation' => [
                'base' => $this->route,
                'from' => $navigationFrom,
                'pagination' => $users->setPath($route)->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
                'query_string' => $queryString
            ],

            'search' => [
                'query' => $search,
            ],

            'users' => [
                'all' => $users,
                'one' => $user,
            ],

            'roles' => [
                'all' => $roles,
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
            'cto34_user' => 'required|unique:mysql-reader.tbCTOUsuario,CTOUsuarioNombre|max:12|min:6',
            'cto34_pass' => 'required|confirmed',
            'cto34_person' => 'required',
            'cto34_role' => 'not_in:0',
        ];
   
        $messages = [
            'cto34_user.required' => 'Usuario requerido',
            'cto34_user.unique' => 'Ya existe un usuario con ese nombre',
            'cto34_user.max' => 'Campo usuario debe tener mínimo 6 caracteres',
            'cto34_user.max' => 'Campo usuario solo permite máximo 12 caracteres',
            'cto34_person.required' => 'Persona requerida',
            'cto34_pass.required' => 'Contraseña requerida',
            'cto34_pass.confirmed' => 'Las contraseñas no coinciden',
            'cto34_role.not_in' => 'Seleccionar grupo',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = new User();
        $user->setConnection('mysql-writer');

        $user->CTOUsuarioNombre = $request->input('cto34_user');
        $user->CTOUsuarioContrasena = hash('SHA512', 'G$·ng042EWNVewkvnosv"NVi02o3{'.$request->input('cto34_pass').'}NF3oinfEFNioenf03');
        $user->SecureToken = hash('SHA512', uniqid().time());
        $user->SecureKey = hash('SHA512', $request->input('cto34_user'));
        $user->RegistroInactivo = $request->input('cto34_status');
        $user->tbDirPersonaID_CTOUsuario = $request->input('cto34_person');
        $user->tbCTOUsuarioGrupoID_CTOUsuario = $request->input('cto34_role');

        if (!$user->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puedo guardar el usuario, intente nuevamente'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Usuario agregado correctamente');
    }

    /**
     * Update user
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doUpdate(Request $request) {

        $id = $request->input('cto34_id');
        $page = $request->input('_page');
        $redirect = URL::previous();

        $rules = [
            'cto34_id' => 'required',
            'cto34_person' => 'required',
            'cto34_role' => 'not_in:0',
        ];
   
        $messages = [
            'cto34_id.required' => 'Id de usuario requerido',
            'cto34_person.required' => 'Persona requerida',
            'cto34_role.not_in' => 'Seleccionar grupo',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = User::find($id)->setConnection('mysql-writer');
        $user->RegistroInactivo = $request->input('cto34_status');
        $user->tbDirPersonaID_CTOUsuario = $request->input('cto34_person');
        $user->tbCTOUsuarioGrupoID_CTOUsuario = $request->input('cto34_role');

        if (!$user->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puedo actualizar el usuario, intente nuevamente'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Usuario actualizado correctamente');
        
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
     * Delete user
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doDelete(Request $request) {

        $id = $request->input('cto34_id');
        $redirect = "{$this->route}";

        $rules = [
            'cto34_id' => 'required',
        ];
   
        $messages = [
            'cto34_id.required' => 'Id de usuario requerido.',
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

        return redirect($redirect)->with('success', 'Usuario <b>'.$user->CTOUsuarioNombre.'</b> eliminado correctamente.');
        
    }
}
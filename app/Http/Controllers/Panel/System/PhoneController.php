<?php
namespace App\Http\Controllers\Panel\System;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Phone;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhoneController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.phone.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/phones');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {
        $phoneAll = Phone::orderBy('tbDirTelefonoID', 'DESC')->paginate($this->paginate);
        $phone = $phoneAll[0];

        if ($id != null)
            $phone = Phone::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Teléfonos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $phoneAll->links(),
                    'prev' => $phoneAll->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $phoneAll->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $phoneAll->currentPage(),
                    'first' => $phoneAll->firstItem(),
                    'last' => $phoneAll->lastPage(),
                    'total' => $phoneAll->count()
                ],
            ],

            'phones' => [
                'all' => $phoneAll,
                'one' => $phone
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
        $phoneAll = null;
        $phone = null;
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
                DB::raw("CONCAT_WS(' ', DirTelefonoCompleto)"), 'LIKE', "{$search}"
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

        $phoneAll = Phone::orderBy('tbDirTelefonoID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $phoneAll = Phone::onlyTrashed()
                ->orderBy('tbDirTelefonoID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($phoneAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $phone = $phoneAll[0];

        if ($id != null) {

            $phone = Phone::find($id);

            if ($hasDelete)
                $phone = Phone::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Teléfonos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $phoneAll->links(),
                    'prev' => $phoneAll->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $phoneAll->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $phoneAll->currentPage(),
                    'first' => $phoneAll->firstItem(),
                    'last' => $phoneAll->lastPage(),
                    'total' => $phoneAll->count()
                ],
            ],

            'phones' => [
                'all' => $phoneAll,
                'one' => $phone
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $phoneAll->count(),
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

        $phoneAll = Phone::orderBy('tbDirTelefonoID', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Teléfonos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $phoneAll->links(),
                    'prev' => $phoneAll->setPath("{$this->route}/save")->previousPageUrl(),
                    'next' => $phoneAll->setPath("{$this->route}/save")->nextPageUrl(),
                    'current' => $phoneAll->currentPage(),
                    'first' => $phoneAll->firstItem(),
                    'last' => $phoneAll->lastPage(),
                    'total' => $phoneAll->count()
                ],
            ],

            'phones' => [
                'all' => $phoneAll
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
        $phoneAll = null;
        $phone = null;

        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', DirTelefonoCompleto)"), 'LIKE', "{$search}"
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

        $phoneAll = Phone::orderBy('tbDirTelefonoID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        $phone = $phoneAll[0];

        if ($id != null)
            $phone = Phone::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Arancel',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $phoneAll->links(),
                    'prev' => $phoneAll->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $phoneAll->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $phoneAll->currentPage(),
                    'first' => $phoneAll->firstItem(),
                    'last' => $phoneAll->lastPage(),
                    'total' => $phoneAll->count()
                ],
            ],

            'phones' => [
                'all' => $phoneAll,
                'one' => $phone
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $phoneAll->count(),
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
            'cto34_asigned' => 'required',
            'cto34_business' => 'required',
            'cto34_label' => 'required',
            'cto34_phone_type' => 'required',
            'cto34_area_code' => 'required',
            'cto34_phone_number' => 'required',
            'cto34_ext' => 'required',
        ];
   
        $messages = [
            'cto34_asigned.required' => 'Persona requerida.',
            'cto34_business.required' => 'Empresa requerida.',
            'cto34_label.required' => 'Etiqueta requerida.',
            'cto34_phone_type.required' => 'Tipo de teléfono requerida.',
            'cto34_area_code.required' => 'Clave lada requerida.',
            'cto34_phone_number.required' => 'Número teléfonico requerido.',
            'cto34_ext.required' => 'Extensión requerida.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $phones = new Phone();
        $phones->setConnection('mysql-writer');

        $label = $request->input('cto34_label');
        $phone_type = $request->input('cto34_phone_type');
        $lada = $request->input('cto34_area_code')  != ''?$request->input('cto34_area_code'):'';
        $phone_numer = $request->input('cto34_phone_number');
        $ext = $request->input('cto34_ext')  != ''?'ext.'.$request->input('cto34_ext'):'';


        $phones->DirTelefonoCompleto = "$label: $phone_type ($lada) $phone_numer $ext";
        $phones->DirTelefonoCompletoSinEt = "$phone_type ($lada) $phone_numer $ext";
        $phones->tbDirPersonaID_DirTelefono = $request->input('cto34_asigned');
        $phones->tbDirEmpresaID_DirTelefono = $request->input('cto34_business');
        $phones->DirTelefonoEtiqueta = $request->input('cto34_label');
        $phones->DirTelefonoTipo = $request->input('cto34_phone_type');
        $phones->DirTelefonoLada = $request->input('cto34_area_code');
        $phones->DirTelefonoNumero = $request->input('cto34_phone_number');
        $phones->DirTelefonoExtension = $request->input('cto34_ext');
        $phones->DirTelefonoComentarios = '';
        $phones->created_at = date('Y-m-d H:i');

        // ROL when close form
        if( $request->input('cto34_closed')){
            $phones->RegistroCerrado = 1;
            $phones->RegistroUsuario = auth_permissions_id(Auth::user()['role']);
        }
        if( $request->input('cto34_inactive')){
            $phones->RegistroInactivo = 1;
        }else{
            $phones->RegistroInactivo = 0;
        }

        if (!$phones->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar el teléfono, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Teléfono agregado correctamente.');
    }

    /**
     * Update person
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doUpdate(Request $request) {

        $id = $request->input('cto34_id');
        $page = $request->input('_page');
        $redirect = URL::previous();

        $rules = [
            'cto34_business' => 'required',
        ];
   
        $messages = [
            'cto34_business.required' => 'Empresa requerida.',
        ];

        /*$rol = auth_permissions_id(Auth::user()['role']);
        $rol_close = Phone::find($id)->setConnection('mysql-writer');
        if ( $request->input('cto34_close') ==1 && !($rol <= $rol_close->tbCTOUsuarioID_Rol) ) {
            return redirect($redirect)
                        ->withErrors(['Este registro esta cerrado y no tienes los suficientes privilegios, intente nuevamente.'])
                        ->withInput();
        }*/

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $phones = Phone::find($id)->setConnection('mysql-writer');

        $label = $request->input('cto34_label');
        $phone_type = $request->input('cto34_phone_type');
        $lada = $request->input('cto34_area_code')  != ''?$request->input('cto34_area_code'):'';
        $phone_numer = $request->input('cto34_phone_number');
        $ext = $request->input('cto34_ext')  != ''?'ext.'.$request->input('cto34_ext'):'';


        $phones->DirTelefonoCompleto = "$label: $phone_type ($lada) $phone_numer $ext";
        $phones->DirTelefonoCompletoSinEt = "$phone_type ($lada) $phone_numer $ext";
        $phones->tbDirPersonaID_DirTelefono = $request->input('cto34_asigned');
        $phones->tbDirEmpresaID_DirTelefono = $request->input('cto34_business');
        $phones->DirTelefonoEtiqueta = $request->input('cto34_label');
        $phones->DirTelefonoTipo = $request->input('cto34_phone_type');
        $phones->DirTelefonoLada = $request->input('cto34_area_code');
        $phones->DirTelefonoNumero = $request->input('cto34_phone_number');
        $phones->DirTelefonoExtension = $request->input('cto34_ext');
        $phones->DirTelefonoComentarios = '';
        $phones->RegistroInactivo = $request->input('cto34_inactive') != null?1:0;
        $phones->updated_at = date('Y-m-d H:i');

        // ROL when close form
        if( $request->input('cto34_close') !=null  ){
            $phones->RegistroCerrado = 1;
            $phones->tbCTOUsuarioID_Rol = auth_permissions_id(Auth::user()['role']);
        }

        if (!$phones->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar el teléfono, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Teléfono actualizado correctamente.');
        
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
            'cto34_id.required' => 'Id de teléfono requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $phone = Phone::find($id)->setConnection('mysql-writer');

        if (!$phone->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar el teléfono, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Teléfono <b>'.$phone->DirTelefonoNumero.'</b> eliminada correctamente.');
        
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
            'cto34_id.required' => 'Id de teléfono requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $user = Phone::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$user->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el teléfono, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Teléfono restaurado correctamente.');
    }
}
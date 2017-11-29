<?php
namespace App\Http\Controllers\Panel\System;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Email;
use App\Models\MyBusiness;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.email.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/emails');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request,$id = null)
    {
        $emailAll = Email::orderBy('tbDirEmailID', 'DESC')->paginate($this->paginate);
        $email = $emailAll[0];

        if ($id != null)
            $email = Email::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Correos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $emailAll->links(),
                    'prev' => $emailAll->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $emailAll->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $emailAll->currentPage(),
                    'first' => $emailAll->firstItem(),
                    'last' => $emailAll->lastPage(),
                    'total' => $emailAll->count()
                ],
            ],

            'emails' => [
                'all' => $emailAll,
                'one' => $email
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

        $emailAll = null;
        $email = null;
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
                DB::raw("CONCAT_WS(' ', DirEmailCorreoE)"), 'LIKE', "{$search}"
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

        $emailAll = Email::orderBy('tbDirEmailID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $emailAll = Email::onlyTrashed()
                ->orderBy('tbDirEmailID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($emailAll->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $email = $emailAll[0];

        if ($id != null) {

            $email = Email::find($id);

            if ($hasDelete)
                $email = Email::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Correos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $emailAll->links(),
                    'prev' => $emailAll->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $emailAll->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $emailAll->currentPage(),
                    'first' => $emailAll->firstItem(),
                    'last' => $emailAll->lastPage(),
                    'total' => $emailAll->count()
                ],
            ],

            'emails' => [
                'all' => $emailAll,
                'one' => $email
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $emailAll->count(),
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

        $emailAll = Email::orderBy('tbDirEmailID', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Correos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $emailAll->links(),
                    'prev' => $emailAll->setPath("{$this->route}/save")->previousPageUrl(),
                    'next' => $emailAll->setPath("{$this->route}/save")->nextPageUrl(),
                    'current' => $emailAll->currentPage(),
                    'first' => $emailAll->firstItem(),
                    'last' => $emailAll->lastPage(),
                    'total' => $emailAll->count()
                ],
            ],

            'emails' => [
                'all' => $emailAll
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

        $emailAll = null;
        $email = null;

        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', DirEmailCorreoE)"), 'LIKE', "{$search}"
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

        $emailAll = Email::orderBy('tbDirEmailID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);

        $email = $emailAll[0];

        if ($id != null)
            $email = Email::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Correos',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $emailAll->links(),
                    'prev' => $emailAll->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $emailAll->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $emailAll->currentPage(),
                    'first' => $emailAll->firstItem(),
                    'last' => $emailAll->lastPage(),
                    'total' => $emailAll->count()
                ],
            ],

            'emails' => [
                'all' => $emailAll,
                'one' => $email
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $emailAll->count(),
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
            'cto34_email' => 'required',
        ];
   
        $messages = [
            'cto34_email.required' => 'Email requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $emails = new Email();
        $emails->setConnection('mysql-writer');

        $emails->DirEmailCorreoE = $request->input('cto34_email');

        // $emails->tbMiEmpresaID_CorreoCta = $request->input('cto34_business');
        // $emails->tbDirPersonaID_CorreoCta = $request->input('cto34_asigned');
        // $emails->CorreoUsuario = $request->input('cto34_user');
        // $emails->CorreoDominio = $request->input('cto34_domain');
        // $emails->CorreoElectronico = $request->input('cto34_email');
        // $emails->CorreoFechaDeApertura = $request->input('cto34_date_begin');
        // $emails->CorreoBajaFecha = $request->input('cto34_date_down');
        // $emails->CorreoBajaRazon = $request->input('cto34_reason_down');
        // $emails->CorreoComentario = '';
        // $emails->CorreoCosto = $request->input('cto34_coust');
        // $emails->RegistroInactivo = $request->input('cto34_inactive') == null?0:1;

        // ROL when close form
        if( $request->input('cto34_closed') ){

            $emails->RegistroCerrado = 1;
            $emails->RegistroUsuario = auth_permissions_id(Auth::user()['role']);
        }


        if($request->input('cto34_status') ){
            $emails->RegistroInactivo = 1;
        }

        if (!$emails->save()) {
            return redirect($redirect)
                        ->withErrors(['No se puede guardar el correo, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Correo agregado correctamente.');
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
            'cto34_id' => 'required',
            'cto34_email' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de correo requerido.',
            'cto34_email.required' => 'Email requerido.',
        ];


        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }

       $emails = Email::find($id)->setConnection('mysql-writer');

            $emails->DirEmailCorreoE = $request->input('cto34_email');

        if( $request->input('cto34_closed') ){

            $emails->RegistroCerrado = 1;
            $emails->RegistroUsuario = auth_permissions_id(Auth::user()['role']);
        }


        if($request->input('cto34_status') ){
            $emails->RegistroInactivo = 1;
        }

        if (!$emails->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puedo actualizar el correo, intente nuevamente'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Correo actualizado correctamente');
        
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
            'cto34_id.required' => 'Id de el correo requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirect)
                        ->withErrors($validator)
                        ->withInput();
        }

        $email = Email::find($id)->setConnection('mysql-writer');

        if (!$email->delete()) {
            return redirect($redirect)
                        ->withErrors(['No se puede eliminar el correo, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirect)->with('success', 'Correo <b>'.$email->DirEmailCorreoE.'</b> eliminado correctamente.');
        
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
            'cto34_id.required' => 'Id de el correo requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $email = Email::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$email->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el correo, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Correo restaurado correctamente.');
    }

}
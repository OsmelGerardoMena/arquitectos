<?php
namespace App\Http\Controllers\Panel\System;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Person;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.person.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/persons');
    }

    /**
     * Index user front
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {
        $persons = Person::orderBy('tbDirPersonaID', 'DESC')
            ->paginate($this->paginate);
        $person = $persons[0];

        if ($id != null)
            $person = Person::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Personas',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $persons->links(),
                    'prev' => $persons->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $persons->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $persons->currentPage(),
                    'first' => $persons->firstItem(),
                    'last' => $persons->lastPage(),
                    'total' => $persons->count()
                ],
            ],

            'persons' => [
                'all' => $persons,
                'one' => $person,
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
        $persons = null;
        $person = null;
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
                DB::raw("CONCAT_WS(' ', PersonaNombreCompleto, PersonaPrefijo, PersonaSaludo)"), 'LIKE', "{$search}"
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

        $persons = Person::orderBy('tbDirPersonaID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);
        
        if ($hasDelete)
            $persons = Person::onlyTrashed()
                ->orderBy('tbDirPersonaID', 'DESC')
                ->where($filters)
                ->paginate($this->paginate);
        

        if ($persons->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }
        
        $person = $persons[0];
        
        if ($id != null) {
        
            $person = Person::find($id);
            
            if ($hasDelete)
                $person = Person::withTrashed()
                    ->find($id);
        
        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Personas',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $persons->links(),
                    'prev' => $persons->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $persons->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $persons->currentPage(),
                    'first' => $persons->firstItem(),
                    'last' => $persons->lastPage(),
                    'total' => $persons->count()
                ],
            ],

            'persons' => [
                'all' => $persons,
                'one' => $person
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $persons->count(),
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

        $persons = Person::orderBy('tbDirPersonaID', 'DESC')
            ->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Personas',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $persons->links(),
                    'prev' => $persons->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $persons->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $persons->currentPage(),
                    'first' => $persons->firstItem(),
                    'last' => $persons->lastPage(),
                    'total' => $persons->count()
                ],
            ],

            'persons' => [
                'all' => $persons,
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
    public function showUpdate(Request $request, $id) {

        $persons = null;
        $person = null;
        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', PersonaNombreCompleto, PersonaPrefijo, PersonaSaludo)"), 'LIKE', "{$search}"
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

        $persons = Person::orderBy('tbDirPersonaID', 'DESC')
            ->where($filters)
            ->paginate($this->paginate);
        $person = $persons[0];

        if ($id != null)
            $person = Person::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Personas',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $persons->links(),
                    'prev' => $persons->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $persons->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $persons->currentPage(),
                    'first' => $persons->firstItem(),
                    'last' => $persons->lastPage(),
                    'total' => $persons->count()
                ],
            ],

            'persons' => [
                'all' => $persons,
                'one' => $person
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $persons->count(),
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
            'cto34_name' => 'required|unique:mysql-reader.tbDirPersona,PersonaNombreDirecto',
            'cto34_lastName' => 'required',
            'cto34_lastName2' => 'required',
        ];
   
        $messages = [
            'cto34_name.required' => 'Nombre requerido.',
            'cto34_name.unique' => 'Ya existe una persona con ese nombre.',
            'cto34_lastName.required' => 'Apellido paterno requerido.',
            'cto34_lastName2.required' => 'Apellido materno requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }

        $person = new Person();
        $person->setConnection('mysql-writer');

        $person->PersonaGenero = $request->input('cto34_gender');
        $person->PersonaPrefijo = $request->input('cto34_personPrefix');
        $person->PersonaSaludo = $request->input('cto34_greeting');
        $person->PersonaFechaNacimiento = $request->input('cto34_birthdate');
        $person->PersonaNombres = $request->input('cto34_name');
        $person->PersonaApellidoPaterno = $request->input('cto34_lastName');
        $person->PersonaApellidoMaterno = $request->input('cto34_lastName2');
        $person->PersonaIdentificacionTipo = $request->input('cto34_idType');
        $person->PersonaIdentificacionNumero = $request->input('cto34_idNumber');
        $person->PersonaFechaAlta = $request->input('cto34_createdDate');
        $person->PersonaFechaBaja = $request->input('cto34_downDate');
        $person->PersonaAlias = $request->input('cto34_lastName').' '.$request->input('cto34_lastName2').', '.$request->input('cto34_name');
        $person->PersonaNombreDirecto = $request->input('cto34_name').' '.$request->input('cto34_lastName').' '.$request->input('cto34_lastName2');
        $person->PersonaNombreCompleto = $request->input('cto34_personPrefix').' '.$request->input('cto34_name').' '.$request->input('cto34_lastName').' '.$request->input('cto34_lastName2');
        $person->PersonaContactoEmergencia = $request->input('cto34_contactEmergency');
        $person->created_at = date('Y-m-d H:i');

        if (!$person->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puede guardar la persona, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Persona agregada correctamente.');
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
            'cto34_name' => 'required',
            'cto34_lastName' => 'required',
            'cto34_lastName2' => 'required',
        ];

        $messages = [
            'cto34_name.required' => 'Nombre requerido.',
            'cto34_lastName.required' => 'Apellido paterno requerido.',
            'cto34_lastName2.required' => 'Apellido materno requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $person = Person::find($id)->setConnection('mysql-writer');

        $person->PersonaGenero = $request->input('cto34_gender');
        $person->PersonaPrefijo = $request->input('cto34_personPrefix');
        $person->PersonaSaludo = $request->input('cto34_greeting');
        $person->PersonaFechaNacimiento = $request->input('cto34_birthdate');
        $person->PersonaNombres = $request->input('cto34_name');
        $person->PersonaApellidoPaterno = $request->input('cto34_lastName');
        $person->PersonaApellidoMaterno = $request->input('cto34_lastName2');
        $person->PersonaIdentificacionTipo = $request->input('cto34_idType');
        $person->PersonaIdentificacionNumero = $request->input('cto34_idNumber');
        $person->PersonaFechaAlta = $request->input('cto34_createdDate');
        $person->PersonaFechaBaja = $request->input('cto34_downDate');
        $person->PersonaAlias = $request->input('cto34_lastName').' '.$request->input('cto34_lastName2').', '.$request->input('cto34_name');
        $person->PersonaNombreDirecto = $request->input('cto34_name').' '.$request->input('cto34_lastName').' '.$request->input('cto34_lastName2');
        $person->PersonaNombreCompleto = $request->input('cto34_personPrefix').' '.$request->input('cto34_name').' '.$request->input('cto34_lastName').' '.$request->input('cto34_lastName2');
        $person->PersonaContactoEmergencia = $request->input('cto34_contactEmergency');

        if (!$person->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puedo actualizar la persona, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Persona actualizada correctamente.');
        
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
            'cto34_id.required' => 'Id del cliente requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $person = Person::find($id)->setConnection('mysql-writer');

        if (!$person->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar la persona, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Persona eliminada correctamente.');

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
            'cto34_id.required' => 'Id de persona requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $person = Person::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$person->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar la persona, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Persona restaurada correctamente.');
    }
}
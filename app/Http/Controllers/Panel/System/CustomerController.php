<?php
namespace App\Http\Controllers\Panel\System;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends AppController
{
    private $route;
    private $viewsPath = 'panel.system.customer.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/system/customers');
    }

    /**
     * Show Index
     * Página principal de la sección
     *
     * @param $request Request
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $id = null)
    {

        $customers = Customer::orderBy('TbClientesID', 'DESC')
            ->paginate($this->paginate);
        $customer = $customers[0];
        
        if ($id != null)
            $customer = Customer::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Clientes',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $customers->links(),
                    'prev' => $customers->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $customers->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $customers->currentPage(),
                    'first' => $customers->firstItem(),
                    'last' => $customers->lastPage(),
                    'total' => $customers->count()
                ],
            ],

            'customers' => [
                'all' => $customers,
                'one' => $customer,
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
        $customers = null;
        $customer = null;
        $redirect = "{$this->route}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;
        $search = '';


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
                DB::raw("CONCAT_WS(' ', ClienteDependencia, ClienteRepresentanteCargo, ClienteProveedorNumero)"), 'LIKE', "{$search}"
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

        $customers = Customer::orderBy('TbClientesID', 'DESC')
            ->where($filters)
            ->orWhereHas('business', function($query) use ($search) {
                $query->where('EmpresaRazonSocial', 'Like', $search);
            })
            ->paginate($this->paginate);
        
        if ($hasDelete) {
            
            $customers = Customer::onlyTrashed()
                ->orderBy('TbClientesID', 'DESC')
                ->where($filters)
                ->orWhereHas('business', function($query) use ($search) {
                    $query->where('EmpresaRazonSocial', 'Like', $search);
                })
                ->paginate($this->paginate);
        }

        if ($customers->count() == 0) {
            return redirect($redirect)
                        ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $customer = $customers[0];
        
        if ($id != null) {
            
            $customer = Customer::find($id);
            
            if ($hasDelete)
                $customer = Customer::withTrashed()->find($id);
            
        }

        $viewData = [
            'page' => [
                'title' => 'Sistema / Clientes',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/search',
                'pagination' => [
                    'links' => $customers->links(),
                    'prev' => $customers->setPath("{$this->route}/search")->appends($queries)->previousPageUrl(),
                    'next' => $customers->setPath("{$this->route}/search")->appends($queries)->nextPageUrl(),
                    'current' => $customers->currentPage(),
                    'first' => $customers->firstItem(),
                    'last' => $customers->lastPage(),
                    'total' => $customers->count()
                ],
            ],

            'customers' => [
                'all' => $customers,
                'one' => $customer
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $customers->count(),
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

        $customers = Customer::orderBy('TbClientesID', 'DESC')
            ->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Clientes',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => '/info',
                'pagination' => [
                    'links' => $customers->links(),
                    'prev' => $customers->setPath("{$this->route}/info")->previousPageUrl(),
                    'next' => $customers->setPath("{$this->route}/info")->nextPageUrl(),
                    'current' => $customers->currentPage(),
                    'first' => $customers->firstItem(),
                    'last' => $customers->lastPage(),
                    'total' => $customers->count()
                ],
            ],

            'customers' => [
                'all' => $customers,
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
        $customers = null;
        $customer = null;
        $filters = [];
        $queries = $request->query();
        $section = '/info';

        // Url has param 'q' from search
        if ($request->has('q')) {

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

        $customers = Customer::orderBy('TbClientesID', 'DESC')
            ->paginate($this->paginate);
        $customer = $customers[0];

        if ($id != null)
            $customer = Customer::find($id);

        $viewData = [
            'page' => [
                'title' => 'Sistema / Clientes',
            ],

            'navigation' => [
                'base' => $this->route,
                'section' => $section,
                'pagination' => [
                    'links' => $customers->links(),
                    'prev' => $customers->setPath("{$this->route}{$section}")->previousPageUrl(),
                    'next' => $customers->setPath("{$this->route}{$section}")->nextPageUrl(),
                    'current' => $customers->currentPage(),
                    'first' => $customers->firstItem(),
                    'last' => $customers->lastPage(),
                    'total' => $customers->count()
                ],
            ],

            'customers' => [
                'all' => $customers,
                'one' => $customer
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $customers->count(),
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

            'cto34_business' => 'required',
            'cto34_dependency' => 'required',
            'cto34_searchPerson' => 'required'
        ];
   
        $messages = [
            'cto34_business.required' => 'Empresa requerida.',
            'cto34_dependency.required' => 'Dependencia requerida.',
            'cto34_sector.required' => 'Sector requerido.',
            'cto34_searchPerson.required' => 'Representante requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            return redirect($redirectError)
                        ->withErrors($validator)
                        ->withInput();
        }

        $customer = new Customer();
        $customer->setConnection('mysql-writer');

        $customer->TbDirEmpresaID_Clientes = $request->input('cto34_business');
        $customer->TbmiEmpresaID_Clientes = 0;
        $customer->tbDirPersonaEmpresaID_BitacoraDestinatario = $request->input('cto34_searchPerson');
        $customer->ClienteDependencia = $request->input('cto34_dependency');
        $customer->ClienteRepresentanteCargo = $request->input('cto34_job');
        $customer->ClienteSector = $request->input('cto34_sector');
        $customer->ClienteFormaDePago = $request->input('cto34_methodPay');
        $customer->ClienteCuentaDePago = $request->input('cto34_account');
        $customer->ClienteProveedorNumero = $request->input('cto34_supplierNumber');
        $customer->created_at = date("d-m-Y H:i");


        if (!$customer->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puede guardar el cliente, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Cliente agregado correctamente.');
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

            'cto34_business' => 'required',
            'cto34_dependency' => 'required',
            'cto34_searchPerson' => 'required'
        ];

        $messages = [
            'cto34_business.required' => 'Empresa requerida.',
            'cto34_dependency.required' => 'Dependencia requerida.',
            'cto34_sector.required' => 'Sector requerido.',
            'cto34_searchPerson.required' => 'Representante requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $customer = Customer::find($id)->setConnection('mysql-writer');

        $customer->TbDirEmpresaID_Clientes = $request->input('cto34_business');
        $customer->tbDirPersonaEmpresaID_BitacoraDestinatario = $request->input('cto34_searchPerson');
        $customer->ClienteDependencia = $request->input('cto34_dependency');
        $customer->ClienteRepresentanteCargo = $request->input('cto34_job');
        $customer->ClienteSector = $request->input('cto34_sector');
        $customer->ClienteFormaDePago = $request->input('cto34_methodPay');
        $customer->ClienteCuentaDePago = $request->input('cto34_account');
        $customer->ClienteProveedorNumero = $request->input('cto34_supplierNumber');
        $customer->updated_at = date("d-m-Y H:i");
     
        if (!$customer->save()) {
            return redirect($redirectError)
                        ->withErrors(['No se puede actualizar el cliente, intente nuevamente.'])
                        ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Cliente actualizado correctamente.');
        
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

        $customer = Customer::find($id)->setConnection('mysql-writer');

        if (!$customer->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el cliente, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Cliente eliminado correctamente.');

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
            'cto34_id.required' => 'Id del cliente requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $customer = Customer::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$customer->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el cliente, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Cliente restaurado correctamente.');
    }
}
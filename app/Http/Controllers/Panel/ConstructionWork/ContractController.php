<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Illuminate\Support\Facades\Bus;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Business;
use App\Models\BusinessWork;
use App\Models\ConstructionWork;
use App\Models\Contract;
use App\Models\Currency;
use App\Models\Group;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends AppController
{
    private $route;
    private $childRoute = "contracts";
    private $viewsPath = 'panel.constructionwork.contract.';
    private $paginate = 25;

    public function __construct() {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url("panel/constructionwork");
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
    public function showIndex(Request $request, $workId, $id = null) {

        $work = ConstructionWork::find($workId);
        $contracts = Contract::orderBy('tbContratoID', 'DESC')
            ->where('tbObraID_Contrato', '=', $workId)
            ->paginate($this->paginate);
        $currencies = Currency::all();
        $typePerson = works_type_business_typeperson();
        $sector = works_type_business_sector();
        $groups = Group::all();
        $businessCategories = Business::categories();

        $contract = $contracts[0];

        if ($id != null)
            $contract = Contract::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Contratos",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $contracts->links(),
                    'prev' => $contracts->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $contracts->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $contracts->currentPage(),
                    'first' => $contracts->firstItem(),
                    'last' => $contracts->lastPage(),
                    'total' => $contracts->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'contracts'
                ],
            ],

            'contracts' => [
                'all' => $contracts,
                'one' => $contract
            ],

            'works' => [
                'one' => $work,
            ],

            'currencies' => [
                'all' => $currencies,
            ],

            'business' => [
                'categories' => $businessCategories,
                'groups' => $groups
            ],

            'typePerson' =>  $typePerson,

            'sector' => $sector,

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
     * @param $workId int id de la obra
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showSearch(Request $request, $workId, $id = null)
    {
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;

        $work = ConstructionWork::find($workId);
        $contracts = null;
        $contract = null;
        $currencies = Currency::all();
        $businessCategories = Business::categories();
        $groups = Group::all();
        $typePerson = works_type_business_typeperson();
        $sector = works_type_business_sector();

        if (!$request->has('filter')) {

            // Redireccionamos con error si el parametro de busqueda no existe o esta vacio
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
                DB::raw("CONCAT_WS(' ', ContratoAlias, ContratoNumero, ContratoObjeto, ContratoImporteContratado)"), 'LIKE', "{$search}"
            ];
        }


        if (!empty($request->status)) {

            switch ($request->status) {

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

        if (!empty($request->aggremmentType)) {

            $filters[] = [
                'ContratoTipo', '=', $request->aggremmentType
            ];
        }

        if (!empty($request->assignmentType)) {

            $filters[] = [
                'ContratoAsignacionTipo', '=', $request->assignmentType
            ];
        }

        if (!empty($request->modality)) {

            $filters[] = [
                'ContratoModalidad', '=', $request->modality
            ];
        }

        $contracts = Contract::orderBy('tbContratoID', 'DESC')
            ->where('tbObraID_Contrato', '=', $workId)
            ->where($filters)
            ->paginate($this->paginate);

        if ($hasDelete) {

            $contracts = Contract::onlyTrashed()->where('tbObraID_Contrato', '=', $workId)
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($contracts->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }


        $contract = $contracts[0];

        if ($id != null) {

            $contract = Contract::find($id);
            if ($hasDelete)
                $contract = Contract::withTrashed()->find($id);

        }

        $viewData = [
            'page' => [
                'title' => 'Contratos / Busqueda',
            ],

            'navigation' => [
                'base' =>  "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $contracts->links(),
                    'prev' => $contracts->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->appends($queries)->previousPageUrl(),
                    'next' => $contracts->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->appends($queries)->nextPageUrl(),
                    'current' => $contracts->currentPage(),
                    'first' => $contracts->firstItem(),
                    'last' => $contracts->lastPage(),
                    'total' => $contracts->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'contracts'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'contracts' => [
                'all' => $contracts,
                'one' => $contract
            ],

            'currencies' => [
                'all' => $currencies,
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $contracts->count(),
                'query' => $request->q,
            ],

            'business' => [
                'categories' => $businessCategories,
                'groups' => $groups
            ],

            'typePerson' =>  $typePerson,

            'sector' => $sector
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Show Save
     * Muestra la vista para guardar un registro
     *
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function showSave(Request $request, $workId)
    {

        $work = ConstructionWork::find($workId);
        $contracts = Contract::orderBy('tbContratoID', 'DESC')
            ->where('tbObraID_Contrato', '=', $workId)
            ->paginate($this->paginate);
        $groups = Group::all();
        $businessCategories = Business::categories();
        $contractsTypes = Contract::types();
        $contractsTypesAllocation = Contract::typesAllocation();
        $currencies = Currency::all();

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Contratos",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'pagination' => [
                    'links' => $contracts->links(),
                    'prev' => $contracts->previousPageUrl(),
                    'next' => $contracts->nextPageUrl(),
                    'current' => $contracts->currentPage(),
                    'first' => $contracts->firstItem(),
                    'last' => $contracts->lastPage(),
                    'total' => $contracts->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'contracts'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'contracts' => [
                'all' => $contracts,
                'types' => $contractsTypes,
                'typesAllocation' => $contractsTypesAllocation,
            ],

            'business' => [
                'categories' => $businessCategories,
            ],

            'groups' => [
                'all' => $groups,
            ],

            'currencies' => [
                'all' => $currencies,
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
    public function showUpdate(Request $request, $workId, $id)
    {

        $work = ConstructionWork::find($workId);
        $contract = null;
        $contracts = null;
        $currencies = Currency::all();
        $groups = Group::all();
        $businessCategories = Business::categories();
        $redirect = URL::previous();
        $filters = [];
        $queries = $request->query();
        $section = '/info';

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', ContratoAlias, ContratoNumero, ContratoObjeto, ContratoImporteContratado)"), 'LIKE', "{$search}"
            ];

            $section = '/search';
        }

        if (!empty($request->status)) {

            switch ($request->status) {

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

        if (!empty($request->aggremmentType)) {

            $filters[] = [
                'ContratoTipo', '=', $request->aggremmentType
            ];

            $section = '/search';
        }

        if (!empty($request->assignmentType)) {

            $filters[] = [
                'ContratoAsignacionTipo', '=', $request->assignmentType
            ];

            $section = '/search';
        }

        if (!empty($request->modality)) {

            $filters[] = [
                'ContratoModalidad', '=', $request->modality
            ];

            $section = '/search';
        }


        $contracts = Contract::orderBy('tbContratoID', 'DESC')
            ->where('tbObraID_Contrato', '=', $workId)
            ->where($filters)
            ->paginate($this->paginate);
        $contract = $contracts[0];

        if ($id != null)
            $contract = Contract::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Contratos",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => $section,
                'pagination' => [
                    'links' => $contracts->links(),
                    'prev' => $contracts->setPath("{$this->route}/{$workId}/{$this->childRoute}{$section}")->appends($queries)->previousPageUrl(),
                    'next' => $contracts->setPath("{$this->route}/{$workId}/{$this->childRoute}{$section}")->appends($queries)->nextPageUrl(),
                    'current' => $contracts->currentPage(),
                    'first' => $contracts->firstItem(),
                    'last' => $contracts->lastPage(),
                    'total' => $contracts->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'legal',
                    'child' => 'contracts'
                ],
            ],

            'contracts' => [
                'all' => $contracts,
                'one' => $contract,
            ],

            'works' => [
                'one' => $work,
            ],

            'groups' => [
                'all' => $groups,
            ],

            'currencies' => [
                'all' => $currencies,
            ],

            'business' => [
                'categories' => $businessCategories,
            ],

            'filter' => [
                'queries' => $queries,
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $contracts->count(),
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
            'cto34_directCustomer' => 'required',
            'cto34_contractCustomer' => 'required',
            'cto34_shortScope' => 'required',
            'cto34_contractNumber' => 'required',
            'cto34_contractor' => 'required'
        ];

        $messages = [
            'cto34_directCustomer.required' => 'Cliente directo requerido.',
            'cto34_contractCustomer.required' => 'Cliente contratante requerido.',
            'cto34_shortScope.required' => 'Alcance corto requerido.',
            'cto34_contractNumber.required' => 'Número requerido.',
            'cto34_contractor.required' => 'Contratista requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $contract = new Contract();
        $contract->setConnection('mysql-writer');

        $contract->ContratoAlias = $request->input('cto34_directCustomerName').'/'.$request->input('cto34_contractorName').'/'.$request->input('cto34_shortScope').'/'.$request->input('cto34_contractNumber');
        //$contract->TbMiEmpresaID_Contrato = 0;
        $contract->tbObraID_Contrato = $request->input('cto34_work');
        $contract->ContratoTipo = $request->input('cto34_typeAgreement');
        //$contract->tbContratosID_ContratoOriginal = (!empty($request->input('cto34_originalContract'))) ? $request->input('cto34_originalContract') : 0;
        $contract->ContratoNumero = $request->input('cto34_contractNumber');
        $contract->ContratoObjeto = $request->input('cto34_contractObjects');
        $contract->ContratoAlcanceCorto = $request->input('cto34_shortScope');
        $contract->ContratoAsignacionTipo = $request->input('cto34_typeAssignment');
        $contract->ContratoImporteContratado = $request->input('cto34_amountWithoutTax');
        //$contract->ContratoImporteEnCatalogo = $request->input('cto34_addendumAmount');
        $contract->tbMonedaID_Contrato = $request->input('cto34_currency');
        $contract->ContratoTipoDeCambio = 0;
        $contract->ContratoFechaFirma = $request->input('cto34_signatureDate');
        $contract->ContratoFechaInicioContrato = $request->input('cto34_contractStartDate');
        $contract->ContratoFechaTerminoContrato = $request->input('cto34_contractEndDate');
        $contract->ContratoDuracionContratada = $request->input('cto34_contractDuration');
        $contract->ContratoFechaInicioReal = $request->input('cto34_contractActualStartDate');
        $contract->ContratoFechaTerminoReal = $request->input('cto34_contractActualEndDate');
        $contract->ContratoDuracionReal = $request->input('cto34_contractActualDuration');
        $contract->TbClientesID_ClienteFinal = 0;
        $contract->TbClientesID_ClienteDirecto = $request->input('cto34_directCustomer');
        //$contract->TbDirEmpresaObraID_Contratista = $request->input('cto34_contractor');
        $contract->ContratoAnticipoPCT = $request->input('cto34_advancePayment');
        $contract->ContratoAnticipoMonto = $request->input('cto34_advancePaymentAmount');
        $contract->ContratoFondoGarantiaPCT = $request->input('cto34_guaranteeFund');
        $contract->ContratoOtrasRetencionesConcepto = $request->input('cto34_otherRetentionsConcept');
        $contract->ContratoOtrasRetencionesPCT = $request->input('cto34_otherRetentions');
        $contract->ContratoFianzaAnticipoPCT = $request->input('cto34_depositBond');
        $contract->ContratoFianzaGarantiaPCT = $request->input('cto34_guaranteeBond');
        $contract->ContratoFianzaViciosOcultosPCT = $request->input('cto34_hiddenViceBond');
        $contract->ContratoOtrasFianzas = $request->input('cto34_otherBondsGuarantees');
        $contract->ContratoPenalizaciones = 0;
        $contract->tbDirEmpresaObraID_ClienteContratante = $request->input('cto34_contractCustomer');
        $contract->tbDirEmpresaObraID_ClienteDirecto = $request->input('cto34_directCustomer');
        //$contract->tbDirEmpresaObraID_Contratista = $request->input('cto34_contractor');
        $contract->tbDirPersonaEmpresaObraID_FirmaCliente = $request->input('cto34_customerSignature');
        $contract->tbDirPersonaEmpresaObraID_ClienteRepresentante = $request->input('cto34_customerRepresentative');
        $contract->tbDirPersonaEmpresaObraID_FirmaContratista = $request->input('cto34_contractorSignature');
        $contract->tbDirPersonaEmpresaObraID_ContratistaResponsableObra = $request->input('cto34_workManager');
        $contract->tbDirEmpresaObraID_ContratoSupervisora = $request->input('cto34_supervisingCompany');
        $contract->tbDirEmpresaDomicilioID_ContratoContratista = $request->input('cto34_contractorAddress');
        $contract->tbDirEmpresaDomicilioID_ContratoCliente = $request->input('cto34_customerAddress');
        $contract->ContratoModalidad = $request->input('cto34_modality');
        $contract->ContratoCreadoPor = Auth::id();
        $contract->created_at = date("d-m-Y H:i");


        if (!$contract->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede guardar el contrato, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Contrato agregado correctamente.');
    }

    /**
     * Update person
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doUpdate(Request $request, $workId) {

        $id = $request->input('cto34_id');
        $hasSearch = $request->input('_hasSearch');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info/{$id}{$request->input('_query')}";
        $redirectError = URL::previous();

        if ($hasSearch)
            $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/search/{$id}{$request->input('_query')}";

        $rules = [
            'cto34_directCustomer' => 'required',
            'cto34_contractCustomer' => 'required',
            'cto34_shortScope' => 'required',
            'cto34_contractNumber' => 'required',
            'cto34_contractor' => 'required'
        ];

        $messages = [
            'cto34_directCustomer.required' => 'Cliente directo requerido.',
            'cto34_contractCustomer.required' => 'Cliente contratante requerido.',
            'cto34_shortScope.required' => 'Alcance corto requerido.',
            'cto34_contractNumber.required' => 'Número requerido.',
            'cto34_contractor.required' => 'Contratista requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $contract = Contract::find($id)->setConnection('mysql-writer');

        $contract->ContratoAlias = $request->input('cto34_directCustomerName').'/'.$request->input('cto34_contractorName').'/'.$request->input('cto34_shortScope').'/'.$request->input('cto34_contractNumber');
        //$contract->TbMiEmpresaID_Contrato = 0;
        $contract->tbObraID_Contrato = $request->input('cto34_work');
        $contract->ContratoTipo = $request->input('cto34_typeAgreement');
        //$contract->tbContratosID_ContratoOriginal = (!empty($request->input('cto34_originalContract'))) ? $request->input('cto34_originalContract') : 0;
        $contract->ContratoNumero = $request->input('cto34_contractNumber');
        $contract->ContratoObjeto = $request->input('cto34_contractObjects');
        $contract->ContratoAlcanceCorto = $request->input('cto34_shortScope');
        $contract->ContratoAsignacionTipo = $request->input('cto34_typeAssignment');
        $contract->ContratoImporteContratado = $request->input('cto34_amountWithoutTax');
        //$contract->ContratoImporteEnCatalogo = $request->input('cto34_addendumAmount');
        $contract->tbMonedaID_Contrato = $request->input('cto34_currency');
        $contract->ContratoTipoDeCambio = 0;
        $contract->ContratoFechaFirma = $request->input('cto34_signatureDate');
        $contract->ContratoFechaInicioContrato = $request->input('cto34_contractStartDate');
        $contract->ContratoFechaTerminoContrato = $request->input('cto34_contractEndDate');
        $contract->ContratoDuracionContratada = $request->input('cto34_contractDuration');
        $contract->ContratoFechaInicioReal = $request->input('cto34_contractActualStartDate');
        $contract->ContratoFechaTerminoReal = $request->input('cto34_contractActualEndDate');
        $contract->ContratoDuracionReal = $request->input('cto34_contractActualDuration');
        $contract->TbClientesID_ClienteFinal = 0;
        $contract->TbClientesID_ClienteDirecto = $request->input('cto34_directCustomer');
        //$contract->TbDirEmpresaObraID_Contratista = $request->input('cto34_contractor');
        $contract->ContratoAnticipoPCT = $request->input('cto34_advancePayment');
        $contract->ContratoAnticipoMonto = $request->input('cto34_advancePaymentAmount');
        $contract->ContratoFondoGarantiaPCT = $request->input('cto34_guaranteeFund');
        $contract->ContratoOtrasRetencionesConcepto = $request->input('cto34_otherRetentionsConcept');
        $contract->ContratoOtrasRetencionesPCT = $request->input('cto34_otherRetentions');
        $contract->ContratoFianzaAnticipoPCT = $request->input('cto34_depositBond');
        $contract->ContratoFianzaGarantiaPCT = $request->input('cto34_guaranteeBond');
        $contract->ContratoFianzaViciosOcultosPCT = $request->input('cto34_hiddenViceBond');
        $contract->ContratoOtrasFianzas = $request->input('cto34_otherBondsGuarantees');
        $contract->ContratoPenalizaciones = 0;
        $contract->tbDirEmpresaObraID_ClienteContratante = $request->input('cto34_contractCustomer');
        $contract->tbDirEmpresaObraID_ClienteDirecto = $request->input('cto34_directCustomer');
        //$contract->tbDirEmpresaObraID_Contratista = $request->input('cto34_contractor');
        $contract->tbDirPersonaEmpresaObraID_FirmaCliente = $request->input('cto34_customerSignature');
        $contract->tbDirPersonaEmpresaObraID_ClienteRepresentante = $request->input('cto34_customerRepresentative');
        $contract->tbDirPersonaEmpresaObraID_FirmaContratista = $request->input('cto34_contractorSignature');
        $contract->tbDirPersonaEmpresaObraID_ContratistaResponsableObra = $request->input('cto34_workManager');
        $contract->tbDirEmpresaObraID_ContratoSupervisora = $request->input('cto34_supervisingCompany');
        $contract->tbDirEmpresaDomicilioID_ContratoContratista = $request->input('cto34_contractorAddress');
        $contract->tbDirEmpresaDomicilioID_ContratoCliente = $request->input('cto34_customerAddress');
        $contract->ContratoModalidad = $request->input('cto34_modality');
        $contract->ContratoModificadoPor = Auth::id();
        $contract->updated_at = date("d-m-Y H:i");

        if (!$contract->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede actualizar el contrato, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Contrato actualizado correctamente.');

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

        $contract = Contract::find($id)->setConnection('mysql-writer');

        if (!$contract->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el contrato, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Contrato eliminado correctamente.');

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

        $contract = Contract::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$contract->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el contrato, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Contrato restaurado correctamente.');
    }
}
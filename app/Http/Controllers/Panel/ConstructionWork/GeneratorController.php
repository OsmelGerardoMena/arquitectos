<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Business;
use App\Models\ConstructionWork;
use App\Models\Estimate;
use App\Models\Generator;
use App\Models\Unity;
use App\Models\Catalog;
use App\Models\Departure;
use App\Models\Level;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneratorController extends AppController
{
    private $route;
    private $childRoute = "generators";
    private $viewsPath = 'panel.constructionwork.generator.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/constructionwork');
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
    public function showIndex(Request $request, $workId, $id = null)
    {
        $work = ConstructionWork::find($workId);
        $catalogs = Catalog::orderBy('tbCatalogoID', 'DESC')->where('tbObraID_Catalogo', '=', $workId)->paginate($this->paginate);
        $generators = Generator::orderBy('tbGeneradorID', 'DESC')
                                ->whereHas(
                                    'catalog', function ($query) use ($workId) {
                                    $query->whereHas(
                                        'contract', function ($query) use ($workId) {
                                            $query->where('tbObraID_Contrato', '=', $workId);
                                        }
                                    );
                                })
                                ->paginate($this->paginate);
        $catalog = null;
        $generatorsByCatalog = null;
        $generatorsByFolioAndId = null;
        $unities = Unity::all();

        $quantityPresentedPrevious = 0;
        $quantityPresentedAmount = 0;
        $quantityPresentedTotal = 0;
        $quantityRevisedPrevious = 0;
        $quantityRevisedAmount = 0;
        $quantityRevisedTotal = 0;
        $quantityAuthPrevious = 0;
        $quantityAuthAmount = 0;
        $quantityAuthTotal = 0;

        $generator = $generators[0];

        if ($id != null)
            $generator = Generator::find($id);

        if ($generators->count() > 0) {

            $catalog = Catalog::find($generator->tbCatalogoID_Generador);
            $generatorsByCatalog = Generator::where('tbCatalogoID_Generador', '=', $generator->tbCatalogoID_Generador)->get();
            $generatorsByFolioAndId = Generator::where('tbCatalogoID_Generador', '=', $generator->tbCatalogoID_Generador)
                ->where('GeneradorFolio', '<', $generator->GeneradorFolio)
                ->get();
            //$groups = Group::all();

            foreach ($generatorsByFolioAndId as $row) {
                $quantityPresentedPrevious +=  $row->GeneradorCantidadPresentada;
                $quantityRevisedPrevious +=  $row->GeneradorCantidadRevisada;
                $quantityAuthPrevious +=  $row->GeneradorCantidadAutorizada;
            }

            foreach ($generatorsByCatalog as $row) {

                $quantityPresentedTotal +=  $row->GeneradorCantidadPresentada;
                $quantityPresentedAmount += $row->catalog->CatalogoImporte * $row->GeneradorCantidadPresentada;

                $quantityRevisedTotal +=  $row->GeneradorCantidadRevisada;
                $quantityRevisedAmount += $row->catalog->CatalogoImporte * $row->GeneradorCantidadRevisada;

                $quantityAuthTotal += $row->GeneradorCantidadAutorizada;
                $quantityAuthAmount += $row->catalog->CatalogoImporte * $row->GeneradorCantidadAutorizada;
            }
        }



        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Generadores",
            ],

            'works' => [
                'one' => $work,
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $generators->links(),
                    'prev' => $generators->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $generators->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $generators->currentPage(),
                    'first' => $generators->firstItem(),
                    'last' => $generators->lastPage(),
                    'total' => $generators->count()
                ],

                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'finances',
                    'child' => 'generators'
                ],
            ],

            'generators' => [
                'all' => $generators,
                'one' => $generator,
                'quantityPresentedPrevious' => $quantityPresentedPrevious,
                'quantityPresentedTotal' => $quantityPresentedTotal,
                'quantityPresentedAmount' => $quantityPresentedAmount,
                'quantityRevisedPrevious' => $quantityRevisedPrevious,
                'quantityRevisedTotal' => $quantityRevisedTotal,
                'quantityRevisedAmount' => $quantityRevisedAmount,
                'quantityAuthPrevious' => $quantityAuthPrevious,
                'quantityAuthTotal' => $quantityAuthTotal,
                'quantityAuthAmount' => $quantityAuthAmount
            ],

            'catalogs' => [
                'one' => $catalog,
                'all' => $catalogs
            ],
        ];

        return view($this->viewsPath.'index', $viewData);
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
                ->withErrors(['Se debe ingresar un dato para la busqueda'])
                ->withInput();
        }

        $search = $request->q;
        $persons = Person::where('PersonaNombreCompleto', 'LIKE', "%{$search}%")->paginate($this->paginate);

        if ($persons->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$search.'</b>');
        }

        $viewData = [
            'page' => [
                'title' => 'Personas / Busqueda',
            ],

            'navigation' => [
                'base' => $this->route,
                'pagination' => $persons->appends(['q' => $search])->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
            ],

            'persons' => [
                'all' => $persons,
            ],

            'search' => [
                'count' => $persons->count(),
                'query' => $search,
            ]
        ];

        return view($this->viewsPath.'search', $viewData);
    }

    /**
     * Show Save
     * Muestra la vista para guardar un registro
     *
     * @param $request Request
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function showSave(Request $request, $workId)
    {

        $work = ConstructionWork::find($workId);
        $generators = Generator::orderBy('tbGeneradorID', 'DESC')
            ->whereHas(
                'catalog', function ($query) use ($workId) {
                $query->whereHas(
                    'contract', function ($query) use ($workId) {
                    $query->where('tbObraID_Contrato', '=', $workId);
                }
                );
            })
            ->paginate($this->paginate);
        //$groups = Group::all();
        $unities = Unity::all();
        $departures = Departure::where('tbObraID_Partida', '=', $workId)->get();
        $levels = Level::orderBy('tbUbicaNivelID', 'DESC')->paginate($this->paginate);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Generadores",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}/info",
                'section' => '/info',
                'pagination' => [
                    'links' => $generators->links(),
                    'prev' => $generators->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $generators->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $generators->currentPage(),
                    'first' => $generators->firstItem(),
                    'last' => $generators->lastPage(),
                    'total' => $generators->count()
                ],
                'current' => [
                    'father' => 'finances',
                    'child' => 'generators'
                ],
            ],

            'works' => [
                'one' => $work,
            ],

            'unities' => [
                'all' => $unities,
            ],

            'generators' => [
                'all' => $generators,
            ],

            'departures' => [
                'all' => $departures,
            ],

            'levels' => [
                'all' => $levels,
            ],

            /*'groups' => [
                'all' => $groups,
            ],*/
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
        $persons = null;
        $queryString = ($request->has('page')) ? "?page={$request->page}" : '';

        // Url has param 'q' from search
        if ($request->has('q')) {

            $search = $request->q;
            $hasSearch = true;
            $route = "{$this->route}/search?q={$search}";
            $navigationFrom = "search";
            $queryString = ($request->has('page')) ? "?page={$request->page}&q={$search}" : "?q={$search}";

            $persons = Person::where('PersonaNombreCompleto', 'LIKE', "%{$search}%")->paginate($this->paginate);

        } else {
            $persons = Person::paginate($this->paginate);
        }

        $person = Person::find($id);
        $roles = Role::all();

        $viewData = [
            'page' => [
                'title' => 'Personas / Editar',
            ],

            'navigation' => [
                'base' => $this->route,
                'from' => $navigationFrom,
                'pagination' => $persons->setPath($route)->links(),
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
                'query_string' => $queryString
            ],

            'search' => [
                'query' => $search,
            ],

            'persons' => [
                'all' => $persons,
                'one' => $person,
            ],

            'roles' => [
                'all' => $roles,
            ]
        ];

        return view($this->viewsPath.'update', $viewData);
    }

    /**
     * Insert work
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doSave(Request $request) {

        $workId = $request->input('cto34_work');
        $redirect = $this->route.'/'.$workId.'/generators/save#capture';

        $rules = [
            'cto34_reciver' => 'required',
            'cto34_catalog' => 'required',
        ];

        $messages = [
            'cto34_reciver.required' => 'Recibido por requerido.',
            'cto34_catalog.required' => 'Catálogo requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        try {

            $generator = new Generator();
            $generator->setConnection('mysql-writer');
            $generator->GeneradorRecibidoFecha = $request->input('cto34_signatureDate');
            $generator->tbDirPersonaEmpresaObraID_GeneradorRecibe = $request->input('cto34_reciver');
            $generator->tbPartidaID_Generador = 0;//$request->input('cto34_departure');
            $generator->tbSubPartidaID_Generador = $request->input('cto34_subdeparture');
            $generator->GeneradorObraTipo = $request->input('cto34_workType');
            $generator->tbUbicaNivelID_Generador = 0;//$request->input('cto34_conceptLocation');
            $generator->tbUnidadID_Generador = $request->input('cto34_unity');
            $generator->GeneradorCantidadPresentada = $request->input('cto34_presentedQuantity');
            $generator->tbCatalogoID_Generador = $request->input('cto34_catalog');
            $generator->RegistroUsuario = \Auth::id();
            $generator->created_at = date('Y-m-d H:i');

            if (!$generator->save()) {
                throw new \Exception("No se puede guardar el generador, intenta nuevamente");
            }

        } catch(\Exception $e) {

            return redirect($redirect)
                ->withErrors([$e->getMessage()])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Generador agregado correctamente.');
    }

    /**
     * Insert work
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doSaveRevision(Request $request) {

        $workId = $request->input('cto34_work');
        $generatorId = $request->input('cto34_generator');
        $redirect = $this->route.'/'.$workId.'/generators/info/'.$generatorId.'#revision';

        $rules = [
            'cto34_revisedQuantity' => 'required',
            'cto34_personReviser' => 'required',
        ];

        $messages = [
            'cto34_revisedQuantity.required' => 'Cantidad revisada requerida.',
            'cto34_personReviser.required' => 'Revisó requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        try {

            $generator = Generator::find($generatorId);
            $generator->setConnection('mysql-writer');
            $generator->GeneradorCantidadRevisada = $request->input('cto34_revisedQuantity');
            $generator->GeneradorDiferenciaMotivos = $request->input('cto34_reasonDifference');
            $generator->GeneradorRevisoFecha = $request->input('cto34_reviserDate');
            $generator->tbDirPersonaEmpresaObraID_GeneradorRevisa = $request->input('cto34_personReviser');
            $generator->updated_at = date('Y-m-d H:i');

            if (!$generator->save()) {
                throw new \Exception("No se puede actualizar el generador, intenta nuevamente");
            }

        } catch(\Exception $e) {

            return redirect($redirect)
                ->withErrors([$e->getMessage()])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Generador actualizado correctamente.');
    }

    /**
     * Insert work
     *
     * @param Request requuest
     * @return \Illuminate\Http\Response
     */
    public function doSaveAuthorization(Request $request) {

        $workId = $request->input('cto34_work');
        $generatorId = $request->input('cto34_generator');
        $redirect = $this->route.'/'.$workId.'/generators/info/'.$generatorId.'#authorization';

        $rules = [
            'cto34_authorizedQuantity' => 'required',
            'cto34_personAuthorizer' => 'required',
        ];

        $messages = [
            'cto34_authorizedQuantity.required' => 'Cantidad autorizada requerida.',
            'cto34_personAuthorizer.required' => 'Autorizó requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        try {

            $generator = Generator::find($generatorId);
            $generator->setConnection('mysql-writer');
            $generator->GeneradorCantidadAutorizada = $request->input('cto34_authorizedQuantity');
            //$generator->GeneradorDiferenciaMotivos = $request->input('cto34_reasonDifference');
            $generator->GeneradorAutorizaFecha = $request->input('cto34_reviserDate');
            $generator->tbDirPersonaEmpresaObraID_GeneradorAutoriza = $request->input('cto34_personAuthorizer');

            if (!$generator->save()) {
                throw new \Exception("No se puede actualizar el generador, intenta nuevamente");
            }

        } catch(\Exception $e) {

            return redirect($redirect)
                ->withErrors([$e->getMessage()])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Generador actualizado correctamente.');
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
            'cto34_id' => 'required',
            'cto34_name' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de persona requerido.',
            'cto34_name.required' => 'Nombre requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $person = Person::find($id)->setConnection('mysql-writer');

        $person->PersonaGenero = $request->input('cto34_gender');
        $person->PersonaPrefijo = $request->input('cto34_personPrefix');
        $person->PersonaFechaNacimineto = $request->input('cto34_birthdate');
        $person->PersonaNombres = $request->input('cto34_name');
        $person->PersonaApellidoPaterno = $request->input('cto34_lastName');
        $person->PersonaApellidoMaterno = $request->input('cto34_lastName2');
        $person->PersonaIdentificacionTipo = $request->input('cto34_idType');
        $person->PersonaIdentificacionNumero = $request->input('cto34_idNumber');
        $person->PersonaAlias = $request->input('cto34_nameByLast');
        $person->PersonaNombreDirecto = $request->input('cto34_directName');
        $person->PersonaNombreCompleto = $request->input('cto34_fullName');
        $person->PersonaContactoEmergencia = $request->input('cto34_contactEmergency');
        $person->PersonaComentarios = $request->input('cto34_comments');

        if (!$person->save()) {
            return redirect($redirect)
                ->withErrors(['No se puedo actualizar la persona, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Persona actualizada correctamente.');

    }

    /**
     * Delete person
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
            'cto34_id.required' => 'Id de persona requerido.',
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

        return redirect($redirect)->with('success', 'Persona <b>'.$person->PersonaNombreDirecto.'</b> eliminada correctamente.');

    }
}
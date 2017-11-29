<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Building;
use App\Models\Contract;
use App\Models\Estimate;
use App\Models\Person;
use App\Models\Business;
use App\Models\BusinessWork;
use App\Models\PersonWork;
use App\Models\PersonBusinessWork;
use App\Models\PersonBusiness;
use App\Models\PersonMyBusiness;
use App\Models\BusinessMyBusiness;
use App\Models\Catalog;
use App\Models\Subdeparture;
use App\Models\User;
use App\Models\Level;
use App\Models\Local;
use App\Models\ContractDeliverable;
use App\Models\Customer;
use App\Models\BusinessAddress;

use Illuminate\Http\Request;

class SearchController extends AppController
{
    public function __construct(Request $request)
    {
        parent::__construct();
        $this->middleware('auth');

        if(!$request->ajax()){
            return "Method no allowed";
        }
    }

    /**
     * Do person serach
     * Busca en la tabla de persona por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doPersonSearch(Request $request)
    {
        $search = $request->input('q');

        $persons = Person::where('PersonaNombreDirecto', 'LIKE', "%{$search}%")->get();

        $json = [
            'persons' => $persons,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doBusinessSearch(Request $request)
    {
        $search = $request->input('q');

        $business = Business::where('EmpresaRazonSocial', 'LIKE', "%{$search}%")->get();

        $json = [
            'business' => $business,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doBusinessWorkSearchByID(Request $request, $id)
    {

        $businesswork = BusinessWork::where('tbDirEmpresaObraID', '=', $id)->with('businessOne')->first();

        $json = [
            'businessWork' => $businesswork,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doBusinessWorkSearch(Request $request)
    {
        $search = $request->input('q');
        $workId = $request->input('workId');

        //$businessWork = new BusinessWork();
        //$business = $businessWork->business()->where('EmpresaRazonSocial', 'LIKE', "%{$search}%")->get();
        /*$business = Business::whereHas('work', function($query) use ($search) {
                $query->where('EmpresaRazonSocial', 'LIKE', "%{$search}%");
        })->get();*/
        $businesswork = BusinessWork::where('tbObraID_DirEmpresaObra', '=', $workId)->with([
            'business' => function($query) use ($search) {
                $query->where('EmpresaRazonSocial', 'LIKE', "%{$search}%");
            },
        ])->get();

        $json = [
            'businessAll' => $businesswork,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doBusinessWorkByIDSearch(Request $request)
    {
        $id = $request->input('id');

        $businesswork = BusinessWork::where('tbDirEmpresaObraID', '=', $id)
            ->with(['business', 'group'])
            ->first();

        $json = [
            'businessAll' => $businesswork,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doBusinessAddressWorkSearch(Request $request)
    {
        $id = $request->input('id');

        $businesswork = BusinessWork::where('tbDirEmpresaObraID', '=', $id)->with(
            ['businessOne' => function($query) {
                $query->with(['addresses' => function($query) {
                    $query->with('address');
                }]);
            }]
        )->first();

        $json = [
            'businessWork' => $businesswork,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doPersonWorkSearch(Request $request)
    {
        $search = $request->input('q');
        $workId = $request->input('workId');

        //$businessWork = new BusinessWork();
        //$business = $businessWork->business()->where('EmpresaRazonSocial', 'LIKE', "%{$search}%")->get();
        /*$business = Business::whereHas('work', function($query) use ($search) {
                $query->where('EmpresaRazonSocial', 'LIKE', "%{$search}%");
        })->get();*/
        $works = PersonBusinessWork::where('tbObraID_DirPersonaObra', '=', $workId)->with([
            'personsBusiness' => function($query) use ($search) {
                $query->with(['person' => function($query) use($search) {
                    $query->where('PersonaNombreDirecto', 'LIKE', "%{$search}%");
                }]);
            },
        ])->get();

        $json = [
            'personWorks' => $works,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doPersonWorkByIDSearch(Request $request)
    {
        $id = $request->input('id');

        $works = PersonWork::where('tbDirPersonaEmpresaObraID', '=', $id)->with(['persons'])->first();

        $json = [
            'personWork' => $works,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doPersonWorkSearchByBusinessId(Request $request)
    {
        $id = $request->input('id');

        $works = PersonBusiness::where('tbDirEmpresaID_DirPersonaEmpresa', '=', $id)->with(['person'])->get();

        $json = [
            'personWork' => $works,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doPersonMyBusinessSearch(Request $request)
    {
        $search = $request->input('q');

        $persons = PersonMyBusiness::with(['person' => function($query) use($search) {
                $query->where('PersonaNombreCompleto', 'LIKE', '%'.$search.'%');
            }])
            ->with('business')
            ->get();

        $json = [
            'persons' => $persons,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doPersonBusinessSearch(Request $request)
    {
        $search = $request->input('q');

        $persons = Person::where('PersonaNombreCompleto', 'LIKE', '%'.$search.'%')->has('personBusiness')->with('personBusiness')->get();

        $json = [
            'persons' => $persons,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doBusinessMyBusinessSearch(Request $request)
    {
        $search = $request->input('q');

        $business = BusinessMyBusiness::with(['business' => function($query) use($search) {
            $query->where('EmpresaRazonSocial', 'LIKE', '%'.$search.'%');
        }])
            ->with('myBusiness')
            ->get();

        $json = [
            'businessAll' => $business,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doContractWorkSearch(Request $request)
    {
        $search = $request->input('q');
        $workId = $request->input('workId');

        //$businessWork = new BusinessWork();
        //$business = $businessWork->business()->where('EmpresaRazonSocial', 'LIKE', "%{$search}%")->get();
        /*$business = Business::whereHas('work', function($query) use ($search) {
                $query->where('EmpresaRazonSocial', 'LIKE', "%{$search}%");
        })->get();*/
        $works = Contract::where('tbObraID_Contrato', '=', $workId)
                            ->where('ContratoAlias', 'LIKE', "%{$search}%")
                            ->with(
                                ['contractor' => function($query)
                                    {
                                        $query->with('business');
                                    }
                                ]
                            )
                            ->get();

        $json = [
            'contracts' => $works,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doCatalogSearch(Request $request)
    {
        $search = $request->input('q');
        $workId = $request->input('workId');

        //$businessWork = new BusinessWork();
        //$business = $businessWork->business()->where('EmpresaRazonSocial', 'LIKE', "%{$search}%")->get();
        /*$business = Business::whereHas('work', function($query) use ($search) {
                $query->where('EmpresaRazonSocial', 'LIKE', "%{$search}%");
        })->get();*/
        $catalogs = Catalog::where('CatalogoConceptoCodigo', 'LIKE', "%{$search}%")
                            ->whereHas('contract', function($query) use($workId) {
                                $query->where('tbObraID_Contrato', '=', $workId);
                            })
                            ->with(['contractor', 'unity', 'contract', 'departure', 'subdeparture'])
                            ->get();

        $json = [
            'catalogs' => $catalogs,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doSubdepartureSearch(Request $request)
    {
        $id = $request->input('id');

        $subdepartures = Subdeparture::where('tbPartidaID_Subpartida', '=', $id)->get();

        $json = [
            'subdepartures' => $subdepartures,
        ];

        return response()->json($json);
    }


    /**
     * Do User Search CTOUsuario por nombre
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */

    public function doUserSearch(Request $request)
    {
        $q = $request->input('q');

        $users = User::where('CTOUsuarioNombre','LIKE', "%{$q}%")->get();

        $json = [
            'users' => $users,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doGeneratorsSearch(Request $request)
    {
        $workId = $request->input('workId');
        $contractId = $request->input('contractId');

        $estimates = Estimate::where('tbContratoID_Estimacion', '=', $contractId)->with('catalogs')->get();
        $catalogs = Catalog::where('tbContratoID_Catalogo', '=', $contractId)->with('generators')->get();

        $json = [
            'catalogs' => $catalogs,
            'estimates' => $estimates
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doLevelsSearch(Request $request)
    {

        $id = $request->input('id');

        $level = Level::where('tbUbicaEdificioID_Nivel', '=', $id)->get();

        $json = [
            'levels' => $level,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doBuildingByIDSearch(Request $request)
    {
        $id = $request->input('id');

        $building = Building::where('tbUbicaEdificioID', '=', $id)->first();

        $totalArea = 0;
        $totalAreaInt = 0;
        $totalAreaExt = 0;
        $totalLevels = 0;
        $surfaces = [
            'int' => 0,
            'ext' => 0
        ];
        $locals = [
            'total' => 0,
            'area' => [
                'int' => 0,
                'ext'=> 0
            ]
        ];

        if ($id != null) {

            $building = Building::with(['levels' => function($query) {
                $query->with('locals');
            }])->find($id);

        }

        if (!empty($building->levels)) {


            foreach ($building->levels as $level) {

                $surfaces['int'] += $level->UbicaNivelSuperficie;
                $surfaces['ext'] += $level->UbicaNivelSuperficieExterior;
            }

            foreach ($building->levels as $level) {

                if ($level->UbicaNivelSumaNivelEdificio == 1) {
                    if (!empty($level->locals)) {
                        foreach ($level->locals as $local) {

                            $locals['total'] += 1;

                            if ($local->UbicaLocalTipo == 'Interior') {
                                $locals['area']['int'] += $local->UbicaLocalArea;
                            } else {
                                $locals['area']['ext']+= $local->UbicaLocalArea;
                            }

                            if ($local->UbicaLocalSumaAreaNivel == 1) {

                                if ($local->UbicaLocalTipo == 'Interior') {
                                    $totalAreaInt += $local->UbicaLocalArea;
                                } else {
                                    $totalAreaExt += $local->UbicaLocalArea;
                                }
                            }
                        }
                    }
                }
            }

            foreach ($building->levels as $level) {

                if ($level->UbicaNivelSumaNivelEdificio == 1) {
                    $totalLevels += 1;
                }
            }
        }

        if (!empty($building->levels)) {
            foreach ($building->levels as $level) {

                $totalLocalArea = 0;

                foreach ($level->locals as $local) {

                    $totalLocalArea += $local->UbicaLocalArea;

                }

                $totalArea += $totalLocalArea;
            }
        }

        $json = [
            'building' => $building,
            'total_area' => $totalArea,
            'total_levels' => $building->levels->count(),
            'area' => [
                'int' => $locals['area']['int'],
                'ext' => $locals['area']['ext']
            ]
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doLevelsByIDSearch(Request $request)
    {
        $id = $request->input('id');

        $level = Level::where('tbUbicaNivelID', '=', $id)->with(['building', 'locals'])->first();

        $area = [
            'int' => 0,
            'ext' => 0
        ];

        if (!empty($level->locals)) {
            foreach ($level->locals as $local) {

                if ($local->UbicaLocalSumaAreaNivel == 1) {

                    if ($local->UbicaLocalTipo == 'Interior') {
                        $area['int'] += $local->UbicaLocalArea;
                    } else {
                        $area['ext'] += $local->UbicaLocalArea;
                    }
                }
            }
        }


        $json = [
            'level' => $level,
            'total_locals' => $level->locals()->count(),
            'area' => [
                'int' => $area['int'],
                'ext' => $area['ext']
            ]
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doLocalByIDSearch(Request $request)
    {
        $id = $request->input('id');
        $local = Local::where('tbUbicaLocalID', '=', $id)
            ->with(['level' => function($query) {
                $query->with('building');
            }])
            ->first();

        $json = [
            'local' => $local,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Work Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doSearchContractDeliverableByID(Request $request)
    {
        $id = $request->input('id');
        $deliverable = ContractDeliverable::find($id);

        $json = [
            'deliverable' => $deliverable,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doCustomerSearch(Request $request)
    {
        $search = $request->input('q');

        $customers = Customer::with(['business' => function($query) use ($search) {
                $query->where('EmpresaAlias', 'LIKE', "%{$search}%");
            }]) 
            ->get();

        $json = [
            'customers' => $customers,
        ];

        return response()->json($json);
    }

    /**
     * Do Bussines Search
     * Busca en la tabla de empresas por nombre
     *
     * @return \Illuminate\Http\Response
     */
    public function doBusinessAddressSearch(Request $request)
    {
        $id = $request->input('id');

        $addresses = BusinessAddress::where('tbDirEmpresaID_EmpresaDomicilio', '=', $id)->with('address')->get();

        $json = [
            'addresses' => $addresses,
        ];

        return response()->json($json);
    }
}
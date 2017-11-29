<?php

namespace App\Http\Controllers\Ajax;

use Validator;
use DB;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\BusinessWork;
use App\Models\PersonWork;
use App\Models\Business;
use App\Models\Person;
use App\Models\Departure;
use App\Models\Subdeparture;
use App\Models\Level;
use App\Models\Local;
use App\Models\Building;
use App\Models\ContractDeliverable;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Crea una nueva persona
     *
     * @return \Illuminate\Http\Response
     */
    public function doPersonSave(Request $request)
    {

        $requestFrom = $request->input('_from');

        if(!$request->ajax()){
            return response()->json(['message' => 'Methos not allowed']);
        }

    	$responseJson = [
    		'status' => true,
    		'code' => 200,
    		'message' => 'Persona agregada correctamente.',
    		'data' => [],
    	];
    	

        $rules = [
            'cto34_alias' => 'required',
            'cto34_directName' => 'required|unique:mysql-reader.tbDirPersona,PersonaNombreDirecto',
        ];
   
        $messages = [
            'cto34_alias.required' => 'Alias requerido.',
            'cto34_directName.unique' => 'Ya existe una persona con ese nombre.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            
         	$errors = $validator->errors();

            $responseJson = [
	    		'status' => false,
	    		'code' => 203,
	    		'message' => $errors->first(),
	    		'data' => [],
	    	];

        } else {

	        $person = new Person();
	        $person->setConnection('mysql-writer');

	        $person->PersonaGenero = $request->input('cto34_gender');
	        $person->PersonaPrefijo = $request->input('cto34_personPrefix');
	        $person->PersonaFechaNacimineto = $request->input('cto34_birthdate');
	        $person->PersonaNombres = $request->input('cto34_name');
	        $person->PersonaApellidoPaterno = $request->input('cto34_lastName');
	        $person->PersonaApellidoMaterno = $request->input('cto34_lastName2');
	        $person->PersonaIdentificacionTipo = $request->input('cto34_idType');
	        $person->PersonaIdentificacionNumero = $request->input('cto34_idNumber');
	        $person->PersonaFechaAlta = $request->input('cto34_registrationDate');
	        $person->PersonaAlias = $request->input('cto34_nameByLast');
	        $person->PersonaNombreDirecto = $request->input('cto34_directName');
	        $person->PersonaNombreCompleto = $request->input('cto34_fullName');
	        $person->PersonaContactoEmergencia = $request->input('cto34_contactEmergency');
	        $person->PersonaComentarios = $request->input('cto34_comments');
            $person->created_at = date('Y-m-d H:i');
	        if ($person->save()) {
	        	$responseJson['data'] = [
		        	'persons' => [
		        		'id' => $person->tbDirPersonasID,
		        		'name' => $request->input('cto34_directName'),
		        	],
		        ];
	            
	        } else {

	        	$responseJson = [
		    		'status' => false,
		    		'code' => 500,
		    		'message' => 'No se puede guardar la persona, intente nuevamente.',
		    		'data' => [],
		    	];
	        }

    	}

        return response()->json($responseJson);
    }

    /**
     * Crea una nueva empresa
     *
     * @return \Illuminate\Http\Response
     */
    public function doBusinessSave(Request $request)
    {

        if(!$request->ajax()){
            return response()->json(['message' => 'Methos not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Empresa agregada correctamente.',
            'data' => [],
        ];


        $rules = [
            'cto34_contractGroup' => 'required',
            'cto34_legalName' => 'required|unique:mysql-reader.tbDirPersona,PersonaNombreDirecto',

        ];

        $messages = [
            'cto34_contractGroup.required' => 'Grupo requerido.',
            'cto34_legalName.required' => 'Nombre requerido.',
            'cto34_legalName.unique' => 'Ya existe una empresa con ese nombre.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => $errors->first(),
                'data' => [],
            ];

        } else {

            $business = new Business();
            $business->setConnection('mysql-writer');

            $business->EmpresaAlias = $request->input('cto34_alias');
            $business->EmpresaRazonSocial = $request->input('cto34_legalName');
            $business->EmpresaNombreComercial = $request->input('cto34_commercialName');
            $business->EmpresaDependencia = $request->input('cto34_dependency');
            $business->EmpresaEspecialidad = $request->input('cto34_especiality');
            $business->EmpresaTipoPersona = $request->input('cto34_type');
            $business->EmpresaSlogan = $request->input('cto34_slogan');
            $business->EmpresaPaginaWeb = $request->input('cto34_website');
            $business->EmpresaRFC = $request->input('cto34_legalId');
            $business->EmpresaIMSSNumero = $request->input('cto34_imssNum');
            $business->EmpresaINFONAVITNumero = $request->input('cto34_infonavitNum');
            $business->EmpresaSector = $request->input('cto34_sector');
            $business->EmpresaComentarios = $request->input('cto34_comments');
            $business->created_at = date("d-m-Y H:i");

            if ($business->save()) {

                $responseJson['data'] = [
                    'business' => [
                        'id' => $business->tbDirEmpresaID,
                        'name' => $request->input('cto34_legalName'),
                    ],
                ];

            } else {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede guardar la empresa, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);
    }

    /**
     * Crea una nueva empresa para obra
     *
     * @return \Illuminate\Http\Response
     */
    public function doBusinessWorkSave(Request $request)
    {

        if(!$request->ajax()){
            return response()->json(['message' => 'Methos not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Empresa agregada correctamente.',
            'data' => [],
        ];

        $rules = [
            '_from' => 'required',

        ];

        $messages = [
            '_from.required' => 'Campo _from requerido.',
            
        ];

        if ($request->input('_from') == 'search') {

            $rules += [
                'cto34_search' => 'required',

            ];

            $messages += [
                'cto34_search.required' => 'Empresa requerida.',
            ];
        }

        if ($request->input('_from') == 'save') {

            $rules += [
                'cto34_legalName' => 'required',

            ];

            $messages += [
                'cto34_legalName.required' => 'Nombre de empresa requerido',
            ];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => $errors->first(),
                'data' => [],
            ];

        } else {


            if ($request->input('_from') == 'search') {

                $business = new BusinessWork();
                $business->setConnection('mysql-writer');
                $business->tbDirEmpresaID_DirEmpresaObra = $request->input('cto34_search');
                $business->tbObraID_DirEmpresaObra = $request->input('cto34_work');
                $business->tbDirGrupoID_DirEmpresaObra = $request->input('cto34_group');
                $business->DirEmpresaObraAlcance = $request->input('cto34_scope');
                $business->created_at = date("d-m-Y H:i");

                if ($business->save()) {

                    $responseJson['data'] = [
                        'business' => [
                            'id' => $business->tbDirEmpresaObraID,
                            'name' => $request->input('cto34_businessName'),
                        ],
                    ];

                } else {

                    $responseJson = [
                        'status' => false,
                        'code' => 500,
                        'message' => 'No se puede guardar la empresa, intente nuevamente.',
                        'data' => [],
                    ];
                }

            }

            if ($request->input('_from') == 'save') {

                DB::beginTransaction();

                $business = new Business();
                $business->setConnection('mysql-writer');

                $business->EmpresaAlias = $request->input('cto34_alias');
                $business->EmpresaRazonSocial = $request->input('cto34_legalName');
                $business->EmpresaNombreComercial = $request->input('cto34_commercialName');
                $business->EmpresaDependencia = $request->input('cto34_dependency');
                $business->EmpresaEspecialidad = $request->input('cto34_especiality');
                $business->EmpresaTipoPersona = $request->input('cto34_type');
                $business->EmpresaSlogan = $request->input('cto34_slogan');
                $business->EmpresaPaginaWeb = $request->input('cto34_website');
                $business->EmpresaRFC = $request->input('cto34_legalId');
                $business->EmpresaIMSSNumero = $request->input('cto34_imssNum');
                $business->EmpresaINFONAVITNumero = $request->input('cto34_infonavitNum');
                $business->EmpresaSector = $request->input('cto34_sector');
                $business->EmpresaComentarios = $request->input('cto34_comments');
                $business->updated_at = date("d-m-Y H:i");

                if ($business->save()) {

                    $businessWork = new BusinessWork();
                    $businessWork->setConnection('mysql-writer');
                    $businessWork->tbDirEmpresaID_DirEmpresaObra = $business->tbDirEmpresaID;
                    $businessWork->tbObraID_DirEmpresaObra = $request->input('cto34_work');
                    $businessWork->tbDirGrupoID_DirEmpresaObra = $request->input('cto34_group');
                    $businessWork->DirEmpresaObraAlcance = $request->input('cto34_scope');
                    $businessWork->created_at = date("d-m-Y H:i");

                    if ($businessWork->save()) {

                        $responseJson['data'] = [
                            'business' => [
                                'id' => $businessWork->tbDirEmpresaObraID,
                                'name' => $request->input('cto34_legalName'),
                            ],
                        ];

                        DB::commit();

                    } else {

                        DB::rollBack();

                        $responseJson = [
                            'status' => false,
                            'code' => 500,
                            'message' => 'No se puede guardar la empresa, intente nuevamente.',
                            'data' => [],
                        ];
                    }

                } else {

                    DB::rollBack();

                    $responseJson = [
                        'status' => false,
                        'code' => 500,
                        'message' => 'No se puede guardar la empresa, intente nuevamente.',
                        'data' => [],
                    ];
                }
            }
        }

        return response()->json($responseJson);
    }

    /**
     * Crea una nueva empresa para obra
     *
     * @return \Illuminate\Http\Response
     */
    public function doPersonWorkSave(Request $request)
    {

        if(!$request->ajax()){
            return response()->json(['message' => 'Methos not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Empresa agregada correctamente.',
            'data' => [],
        ];

        $rules = [
            'cto34_searchBusiness' => 'required',
            'cto34_department' => 'required',
            'cto34_job' => 'required',
            'cto34_category' => 'required',
        ];

        $messages = [
            'cto34_searchBusiness.required' => 'Empresa requerida',
            'cto34_department.required' => 'Departamento requerido',
            'cto34_job.required' => 'Puesto en obra requerido',
            'cto34_category.required' => 'Categoría requerida',
        ];

        if ($request->input('_from') == 'search') {

            $rules += [
                'cto34_searchPerson' => 'required',

            ];

            $messages += [
                'cto34_searchPerson.required' => 'Persona requerida.',
            ];
        }

        if ($request->input('_from') == 'save') {

            $rules += [
                'cto34_directName' => 'required|unique:mysql-reader.tbDirPersona,PersonaNombreDirecto',
            ];

            $messages += [
                'cto34_directName.required' => 'Nombre requerido.',
                'cto34_directName.unique' => 'Ya existe una persona con ese nombre.',
            ];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => $errors->first(),
                'data' => [],
            ];

        } else {


            if ($request->input('_from') == 'search') {

                $person = new PersonWork();
                $person->setConnection('mysql-writer');
                $person->tbDirPersonaEmpresaID_DirPersonaEmpresaObra = $request->input('cto34_searchPerson');
                $person->tbObraID_DirPersonaObra = $request->input('cto34_work');
                $person->tbDirEmpresaID_DirPersonaEmpresaObra = $request->input('cto34_searchBusiness');
                $person->DirPersonaObraEmpresaDepartamento = $request->input('cto34_department');
                $person->DirPersonaObraEmpresaCategoria = $request->input('cto34_category');
                $person->DirPersonaEmpresaObraCargo = $request->input('cto34_job');

                if ($person->save()) {

                    $responseJson['data'] = [
                        'persons' => [
                            'id' => $person->tbDirPersonaEmpresaObraID,
                            'name' => $request->input('cto34_searchPersonName'),
                        ],
                    ];

                } else {

                    $responseJson = [
                        'status' => false,
                        'code' => 500,
                        'message' => 'No se puede guardar la empresa, intente nuevamente.',
                        'data' => [],
                    ];
                }

            }

            if ($request->input('_from') == 'save') {

                DB::beginTransaction();

                $person = new Person();
                $person->setConnection('mysql-writer');

                $person->PersonaGenero = $request->input('cto34_gender');
                $person->PersonaPrefijo = $request->input('cto34_personPrefix');
                $person->PersonaFechaNacimineto = $request->input('cto34_birthdate');
                $person->PersonaNombres = $request->input('cto34_name');
                $person->PersonaApellidoPaterno = $request->input('cto34_lastName');
                $person->PersonaApellidoMaterno = $request->input('cto34_lastName2');
                $person->PersonaIdentificacionTipo = $request->input('cto34_idType');
                $person->PersonaIdentificacionNumero = $request->input('cto34_idNumber');
                $person->PersonaFechaAlta = $request->input('cto34_registrationDate');
                $person->PersonaAlias = $request->input('cto34_nameByLast');
                $person->PersonaNombreDirecto = $request->input('cto34_directName');
                $person->PersonaNombreCompleto = $request->input('cto34_fullName');
                $person->PersonaContactoEmergencia = $request->input('cto34_contactEmergency');
                $person->PersonaComentarios = $request->input('cto34_comments');
                $person->created_at = date('Y-m-d H:i');

                if ($person->save()) {

                    $personWork = new PersonWork();
                    $personWork->setConnection('mysql-writer');
                    $personWork->tbDirPersonaEmpresaID_DirPersonaEmpresaObra = $person->tbDirPersonasID;
                    $personWork->tbObraID_DirPersonaObra = $request->input('cto34_work');
                    $personWork->tbDirEmpresaID_DirPersonaEmpresaObra = $request->input('cto34_searchBusiness');
                    $personWork->DirPersonaObraEmpresaDepartamento = $request->input('cto34_department');
                    $personWork->DirPersonaObraEmpresaCategoria = $request->input('cto34_category');
                    $personWork->DirPersonaEmpresaObraCargo = $request->input('cto34_job');

                    if ($personWork->save()) {

                        $responseJson['data'] = [
                            'persons' => [
                                'id' => $personWork->tbDirPersonaEmpresaObraID,
                                'name' => $request->input('cto34_directName'),
                            ],
                        ];

                        DB::commit();

                    } else {

                        DB::rollBack();

                        $responseJson = [
                            'status' => false,
                            'code' => 500,
                            'message' => 'No se puede guardar la persona, intente nuevamente.',
                            'data' => [],
                        ];
                    }

                } else {

                    DB::rollBack();

                    $responseJson = [
                        'status' => false,
                        'code' => 500,
                        'message' => 'No se puede guardar la persona, intente nuevamente.',
                        'data' => [],
                    ];
                }
            }
        }

        return response()->json($responseJson);
    }

    public function doDepartureWorkSave(Request $request) {

        if(!$request->ajax()){
            return response()->json(['message' => 'Methos not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Partida agregada correctamente.',
            'data' => [],
        ];


        $rules = [
            'cto34_code' => 'required',
            'cto34_name' => 'required',

        ];

        $messages = [
            'cto34_code.required' => 'Clave requerida.',
            'cto34_name.required' => 'Nombre requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => $errors->first(),
                'data' => [],
            ];

        } else {

            $departure = new Departure();
            $departure->setConnection('mysql-writer');

            $departure->PartidaNombre = $request->input('cto34_name');
            $departure->PartidaClave = $request->input('cto34_code');
            $departure->PartidaLabel = "{$request->input('cto34_code')} - {$request->input('cto34_name')}";
            //$departure->tbObraID_Partida = $request->input('cto34_work');
            $departure->created_at = date('Y-m-d H:i');
            if ($departure->save()) {

                $responseJson['data'] = [
                    'business' => [
                        'id' => $departure->tbPartidaID,
                        'name' => $request->input('cto34_name'),
                    ],
                ];

            } else {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede guardar la partida, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);
    }

    public function doSubdepartureWorkSave(Request $request) {

        if(!$request->ajax()){
            return response()->json(['message' => 'Methos not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Partida agregada correctamente.',
            'data' => [],
        ];


        $rules = [
            'cto34_subdeparture_departure' => 'required',
            'cto34_code' => 'required',
            'cto34_name' => 'required'

        ];

        $messages = [
            'cto34_subdeparture_departure' => 'Partida requerida',
            'cto34_code.required' => 'Clave requerida.',
            'cto34_name.required' => 'Nombre requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => $errors->first(),
                'data' => [],
            ];

        } else {

            $subDeparture = new Subdeparture();
            $subDeparture->setConnection('mysql-writer');

            $subDeparture->SubpartidaNombre = $request->input('cto34_name');
            $subDeparture->SubpartidaClave = $request->input('cto34_code');
            $subDeparture->SubpartidaLabel = "{$request->input('cto34_code')} - {$request->input('cto34_name')}";
            $subDeparture->tbPartidaID_Subpartida = $request->input('cto34_subdeparture_departure');
            $subDeparture->created_at = date('Y-m-d H:i');

            if ($subDeparture->save()) {

                $responseJson['data'] = [
                    'business' => [
                        'id' => $subDeparture->tbSubPartidaID,
                        'name' => $request->input('cto34_name'),
                    ],
                ];

            } else {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede guardar la partida, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);
    }

    public function doLevelSave(Request $request) {

        if(!$request->ajax()){
            return response()->json(['message' => 'Methos not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Nivel agregado correctamente.',
            'data' => [],
        ];


        $rules = [
            'cto34_building' => 'required',
            'cto34_consecutive' => 'required',
            'cto34_code' => 'required',
            'cto34_name' => 'required'
        ];

        $messages = [

            'cto34_building.required' => 'Edificio requerido.',
            'cto34_consecutive.required' => 'Consecutivo requerido.',
            'cto34_code.required' => 'Clave requerida.',
            'cto34_name.required' => 'Nombre requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => 'Error de validación',
                'data' => [],
                'errors' => [
                    'all' => $errors,
                    'first' => $errors->first()
                ]
            ];

        } else {

            $level = new Level();
            $level->setConnection('mysql-writer');

            $level->UbicaNivelAlias = $request->input('cto34_code').' - '.$request->input('cto34_name');
            $level->UbicaNivelClave = $request->input('cto34_code');
            $level->UbicaNivelNombre = $request->input('cto34_name');
            $level->UbicaNivelConsecutivo = $request->input('cto34_consecutive');
            $level->UbicaNivelDescripcion = $request->input('cto34_description');
            $level->UbicaNivelSumaNivelEdificio = (!empty($request->input('cto34_sumLevel'))) ? 1 : 0;
            $level->UbicaNivelSumaAreaEdificio = (!empty($request->input('cto34_sumArea'))) ? 1 : 0;
            $level->UbicaNivelSuperficie = $request->input('cto34_surfaceLevelInt');
            $level->UbicaNivelSuperficieExterior = $request->input('cto34_surfaceLevelExt');
            $level->UbicaNivelNPT = $request->input('cto34_nptLevel');
            $level->tbUbicaEdificioID_Nivel = $request->input('cto34_building');
            $level->created_at = date('Y-m-d H:i');

            if ($level->save()) {

                $responseJson['data'] = [
                    'level' => [
                        'id' => $level->tbUbicaNivelID,
                        'name' => $request->input('cto34_code').' - '.$request->input('cto34_name'),
                    ],
                    'base' => $request->input('_base')
                ];


            } else {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede guardar el nivel, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);
    }

    public function doLevelUpdate(Request $request) {

        if(!$request->ajax()){
            return response()->json(['message' => 'Methos not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Nivel actualizado correctamente.',
            'data' => [],
        ];


        $rules = [
            'cto34_building' => 'required',
            'cto34_consecutive' => 'required',
            'cto34_code' => 'required',
            'cto34_name' => 'required'
        ];

        $messages = [

            'cto34_building.required' => 'Edificio requerido.',
            'cto34_consecutive.required' => 'Consecutivo requerido.',
            'cto34_code.required' => 'Clave requerida.',
            'cto34_name.required' => 'Nombre requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => 'Error de validación',
                'data' => [],
                'errors' => [
                    'all' => $errors,
                    'first' => $errors->first()
                ]
            ];

        } else {

            $level = Level::find($request->input('cto34_id'))->setConnection('mysql-writer'); //new Level();
                //$level->setConnection('mysql-writer');

            $level->UbicaNivelAlias = $request->input('cto34_code').' - '.$request->input('cto34_name');
            $level->UbicaNivelClave = $request->input('cto34_code');
            $level->UbicaNivelNombre = $request->input('cto34_name');
            $level->UbicaNivelConsecutivo = $request->input('cto34_consecutive');
            $level->UbicaNivelDescripcion = $request->input('cto34_description');
            $level->UbicaNivelSumaNivelEdificio = (!empty($request->input('cto34_sumLevel'))) ? 1 : 0;
            $level->UbicaNivelSumaAreaEdificio = (!empty($request->input('cto34_sumArea'))) ? 1 : 0;
            $level->UbicaNivelSuperficie = $request->input('cto34_surfaceLevel');
            $level->UbicaNivelSuperficieExterior = $request->input('cto34_surfaceLevelExt');
            $level->UbicaNivelNPT = $request->input('cto34_nptLevel');
            $level->tbUbicaEdificioID_Nivel = $request->input('cto34_building');
            $level->updated_at = date('Y-m-d H:i');

            if ($level->save()) {

                $responseJson['data'] = [
                    'level' => [
                        'id' => $level->tbUbicaNivelID,
                        'name' => $request->input('cto34_code').' - '.$request->input('cto34_name'),
                    ],
                    'base' => $request->input('_base')
                ];


            } else {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede actualizar el nivel, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);

    }

    public function doLocalSave(Request $request) {

        if(!$request->ajax()){
            return response()->json(['message' => 'Method not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Local agregado correctamente.',
            'data' => [],
        ];


        $rules = [
            'cto34_level' => 'required',
            'cto34_code' => 'required',
            'cto34_name' => 'required'
        ];

        $messages = [

            'cto34_level.required' => 'Nivel requerido.',
            'cto34_code.required' => 'Clave requerida.',
            'cto34_name.required' => 'Nombre requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => 'Error de validación',
                'data' => [],
                'errors' => [
                    'all' => $errors,
                    'first' => $errors->first()
                ]
            ];

        } else {

            $local = new Local();
            $local->setConnection('mysql-writer');

            $local->UbicaLocalAlias = $request->input('cto34_code').' - '.$request->input('cto34_name');
            $local->UbicaLocalNumero = 1;//$request->input('cto34_number');
            $local->UbicaLocalClave = $request->input('cto34_code');
            $local->UbicaLocalNombre = $request->input('cto34_name');
            $local->UbicaLocalTipo = $request->input('cto34_type');
            $local->UbicaLocalArea = $request->input('cto34_area');
            $local->UbicaLocalSumaAreaNivel = (!empty($request->input('cto34_sumArea'))) ? 1 : 0;
            $local->tbUbicaNivelID_Local = $request->input('cto34_level');
            $local->created_at = date('Y-m-d H:i');

            if ($local->save()) {

                $responseJson['data'] = [
                    'level' => [
                        'id' => $local->tbUbicaLocalID,
                        'name' => $request->input('cto34_code').' - '.$request->input('cto34_name'),
                    ],
                    'base' => $request->input('_base')
                ];


            } else {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede guardar el local, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);
    }

    public function doLocalUpdate(Request $request) {

        if(!$request->ajax()){
            return response()->json(['message' => 'Method not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Local actualizado correctamente.',
            'data' => [],
        ];


        $rules = [
            'cto34_level' => 'required',
            'cto34_code' => 'required',
            'cto34_name' => 'required'
        ];

        $messages = [

            'cto34_level.required' => 'Nivel requerido.',
            'cto34_code.required' => 'Clave requerida.',
            'cto34_name.required' => 'Nombre requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => 'Error de validación',
                'data' => [],
                'errors' => [
                    'all' => $errors,
                    'first' => $errors->first()
                ]
            ];

        } else {

            $local = Local::find($request->input('cto34_id'))->setConnection('mysql-writer');

            $local->UbicaLocalAlias = $request->input('cto34_code').' - '.$request->input('cto34_name');
            $local->UbicaLocalNumero = 1;//$request->input('cto34_number');
            $local->UbicaLocalClave = $request->input('cto34_code');
            $local->UbicaLocalNombre = $request->input('cto34_name');
            $local->UbicaLocalTipo = $request->input('cto34_type');
            $local->UbicaLocalArea = $request->input('cto34_area');
            $local->UbicaLocalSumaAreaNivel = (!empty($request->input('cto34_sumArea'))) ? 1 : 0;
            $local->tbUbicaNivelID_Local = $request->input('cto34_level');

            if ($local->save()) {

                $responseJson['data'] = [
                    'level' => [
                        'id' => $local->tbUbicaLocalID,
                        'name' => $request->input('cto34_code').' - '.$request->input('cto34_name'),
                    ],
                    'base' => $request->input('_base')
                ];


            } else {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede actualizar el local, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);
    }

    public function doBuildingUpdate(Request $request) {

        if(!$request->ajax()){
            return response()->json(['message' => 'Method not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Edificio actualizado correctamente.',
            'data' => [],
        ];


        $rules = [

            'cto34_code' => 'required',
            'cto34_name' => 'required'
        ];

        $messages = [

            'cto34_code.required' => 'Clave requerida.',
            'cto34_name.required' => 'Nombre requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => 'Error de validación',
                'data' => [],
                'errors' => [
                    'all' => $errors,
                    'first' => $errors->first()
                ]
            ];

        } else {

            $id = $request->input('cto34_id');

            $building = Building::find($id)->setConnection('mysql-writer');

            $building->UbicaEdificioAlias = $request->input('cto34_code').' - '.$request->input('cto34_name');
            $building->UbicaEdificioClave = $request->input('cto34_code');
            $building->UbicaEdificioNombre = $request->input('cto34_name');
            $building->UbicaEdificioDescripcion = $request->input('cto34_description');
            $building->UbicaEdificioAreaDesplante = $request->input('cto34_shadeArea');
            $building->UbicaEdificioAreaTotal = $request->input('cto34_totalAreaInt');
            $building->UbicaEdificioAreaTotalExterior = $request->input('cto34_totalAreaExt');
            $building->UbicaEdificioNiveles = $request->input('cto34_levelsTotal');

            if ($building->save()) {

                $responseJson['data'] = [
                    'building' => [
                        'id' => $building->tbUbicaEdificioID,
                        'name' => $request->input('cto34_code').' - '.$request->input('cto34_name'),
                    ],
                    'base' => $request->input('_base')
                ];


            } else {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede actualizar el edificio, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);
    }

    public function doContractDeliverableSave(Request $request) {

        if (!$request->ajax()) {
            return response()->json(['message' => 'Method not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Entregable agregado correctamente.',
            'data' => [],
        ];


        $rules = [
            'cto34_contractDelivery' => 'required',
            'cto34_title' => 'required'
        ];

        $messages = [

            'cto34_contractDelivery.required' => 'Contrato requerido.',
            'cto34_title.required' => 'Título requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => 'Error de validación',
                'data' => [],
                'errors' => [
                    'all' => $errors,
                    'first' => $errors->first()
                ]
            ];

        } else {

            $deliverable = new ContractDeliverable();
            $deliverable->setConnection('mysql-writer');

            $deliverable->tbContratoID_ContratoEntregable = $request->input('cto34_contractDelivery');
            $deliverable->ContratoEntregableNombre = $request->input('cto34_title');
            $deliverable->ContratoEntregablePlazo = $request->input('cto34_time');
            $deliverable->ContratoEntregableDescripcion = $request->input('cto34_description');
            $deliverable->ContratoEntregableStatus = $request->input('cto34_status');
            $deliverable->ContratoEntregableFechaEntregado = $request->input('cto34_deliveryDate');
            $deliverable->created_at = date("d-m-Y H:i");

            if ($deliverable->save()) {

                $responseJson['data'] = [
                    'level' => [
                        'id' => $deliverable->tbContratoEntregableID,
                        'name' => $request->input('cto34_title'),
                    ],
                    'base' => $request->input('_base')
                ];


            } else {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede guardar el entregable, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);
    }

    public function doContractDeliverableUpdate(Request $request) {

        if (!$request->ajax()) {
            return response()->json(['message' => 'Method not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Entregable actualizado correctamente.',
            'data' => [],
        ];


        $rules = [
            'cto34_contractDelivery' => 'required',
            'cto34_title' => 'required'
        ];

        $messages = [

            'cto34_contractDelivery.required' => 'Contrato requerido.',
            'cto34_title.required' => 'Título requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => 'Error de validación',
                'data' => [],
                'errors' => [
                    'all' => $errors,
                    'first' => $errors->first()
                ]
            ];

        } else {

            $id = $request->input('cto34_id');
            $deliverable = ContractDeliverable::find($id)->setConnection('mysql-writer');

            $deliverable->ContratoEntregableNombre = $request->input('cto34_title');
            $deliverable->ContratoEntregablePlazo = $request->input('cto34_time');
            $deliverable->ContratoEntregableDescripcion = $request->input('cto34_description');
            $deliverable->ContratoEntregableStatus = $request->input('cto34_status');
            $deliverable->ContratoEntregableFechaEntregado = $request->input('cto34_deliveryDate');
            $deliverable->updated_at = date("d-m-Y H:i");
            if ($deliverable->save()) {

                $responseJson['data'] = [
                    'level' => [
                        'id' => $deliverable->tbContratoEntregableID,
                        'name' => $request->input('cto34_title'),
                    ],
                    'base' => $request->input('_base')
                ];


            } else {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede actualizar el entregable, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);
    }

    public function doRecordClosed(Request $request) {

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Registro cerrado correctamente.',
            'data' => [],
        ];

        $id = $request->input('id');
        $work = $request->input('work');
        $table = $request->input('table');
        $tableId = $request->input('tableId');
        $status = $request->input('status');

        $rules = [

            'id' => 'required',
            // 'work' => 'required',
            'table' => 'required',
            'tableId' => 'required',
            'status' => 'required'
        ];

        $messages = [

            'id.required' => 'Id de registro requerido.',
            // 'work.required' => 'Id de obra requerido.',
            'table.required' => 'Nombre de tabla requerido.',
            'tableId.required' => 'Id de tabla requerido.',
            'status.required' => 'Estatus requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $responseJson = [
                'status' => false,
                'code' => 203,
                'message' => 'Error de validación',
                'data' => [],
                'errors' => [
                    'all' => $errors,
                    'first' => $errors->first()
                ]
            ];

        } else {

            $close = DB::connection('mysql-writer')->table($table)->where($tableId, '=', $id)->update(['RegistroCerrado' => $status]);

            if (!$close) {

                $responseJson = [
                    'status' => false,
                    'code' => 500,
                    'message' => 'No se puede cerrar el registro, intente nuevamente.',
                    'data' => [],
                ];
            }
        }

        return response()->json($responseJson);
    }
}
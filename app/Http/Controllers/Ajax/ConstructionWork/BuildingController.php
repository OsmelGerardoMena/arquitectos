<?php

namespace App\Http\Controllers\Ajax;

use Validator;
use DB;

use App\Http\Controllers\AppController;
use App\Models\Building;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
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
            $building->UbicaEdificioAreaDesplante = $request->input('cto34_shadyArea');
            $building->UbicaEdificioAreaTotal = 0;
            $building->UbicaEdificioNiveles = 0;
            $building->tbObraID_UbicaEdificio = $request->input('cto34_work');

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

    public function doBuildingClosed(Request $request) {

        if(!$request->ajax()){
            return response()->json(['message' => 'Method not allowed']);
        }

        $responseJson = [
            'status' => true,
            'code' => 200,
            'message' => 'Edificio cerrado correctamente.',
            'data' => [],
        ];


        $rules = [

            'cto34_id' => 'required',
            'cto34_work' => 'required'
        ];

        $messages = [

            'cto34_id.required' => 'Id de edificio requerido.',
            'cto34_work.required' => 'Id de obra requerido.'
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
            $building->RegistroCerrado = 1;

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
                    'message' => 'No se puede cerrar el edificio, intente nuevamente.',
                    'data' => [],
                ];
            }

        }

        return response()->json($responseJson);
    }
}
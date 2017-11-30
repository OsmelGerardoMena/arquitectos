<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AppController;
use DB;
use Session;
use Response;
use App\Models\Business;
use Illuminate\Http\Request;
 
use App\Http\Requests;

class ComunicacionesController extends Controller
{
    //
    public function cartapoder(Request $request,$id){

    	$cartasPoder = DB::table('tbCartaPoder')
    	->join('TbLocalidad','tblocalidad.tbLocalidadID','=','tbLocalidadID_CPLugarExpedicion')
    	->where('TbCartaPoderID',$id)->get();

    	return Response::json($cartasPoder);
    }
}

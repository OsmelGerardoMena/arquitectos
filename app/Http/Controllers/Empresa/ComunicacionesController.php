<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AppController;
use DB;
use Session;
use App\Models\Business;
use Illuminate\Http\Request;
 
use App\Http\Requests;

class ComunicacionesController extends Controller
{
    //
    public function obligaciones(){
    	return Session::get('empresa.empresa_id');
    }
}

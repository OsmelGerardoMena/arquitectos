<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AppController;
use DB;
use Session;
use App\Models\Business;
use App\Models\ConstructionWork;

use Illuminate\Http\Request;


class EmpresaController extends AppController
{
    //

    public function indexEmpresa(Request $request) {

    	
    	$listEmpresas = Business::findOrFail($request->selectEmpresa);

    	$comboEmpresas = Business::where('RegistroCerrado',0)->where('RegistroInactivo',0)
    	->get();

    	$works = ConstructionWork::orderBy('TbObraID', 'DESC')->where('RegistroInactivo', '=', 0)
    	->take(5)->get();
    	
    	$cartasPoder = DB::table('tbCartaPoder')->where('TbmiEmpresaID_CartaPoder', $listEmpresas->tbDirEmpresaID)->paginate(5);
        /**
         * Variables de Session del sistema
         */
        $request->session()->put('empresa.empresa', $listEmpresas->EmpresaAlias );
        $request->session()->put('empresa.empresa_id', $listEmpresas->tbDirEmpresaID );
      
    	$viewData = [
            'page' => [
                'title' => $listEmpresas->EmpresaAlias,
            ],
            'works' => [
                'all' => $works,
            ],
        ];
    	return view('panel.empresa.index',$viewData)->with(['empresas' => $comboEmpresas,'cartas' => $cartasPoder]);
    }


}

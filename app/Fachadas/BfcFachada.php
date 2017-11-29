<?php
namespace App\Fachadas;

use App\Models\Business;

class BfcFachada{

   public function testingFacades() {
      echo "Testing the Facades in Laravel.";
   }

   public function listadoEmpresas() {
   	$listEmpresas = Business::where('RegistroCerrado',0)->where('RegistroInactivo',0)->get();
   	return $listEmpresas;
   }


}
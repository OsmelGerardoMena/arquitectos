<?php
namespace app\Fachadas\Facades;
use Illuminate\Support\Facades\Facade;

class BfcFachada extends Facade{

   protected static function getFacadeAccessor() { return 'bfc'; }
   
}
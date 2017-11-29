<?php

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cookie;
use Carbon\Carbon;

class AppController extends Controller
{
    public function __construct() {
        $this->setLocale();
    }

    private function setLocale() {

        date_default_timezone_set('America/Mexico_City');

        $locale =  Cookie::get('locale'); 

        if ($locale) {

            App::setLocale($locale);
            Carbon::setLocale($locale);
            setlocale(LC_ALL, get_locale($locale));

        } else {
            App::setLocale('es');
            Carbon::setLocale('es');
            setlocale(LC_ALL, 'es_MX');
        }
    }
}
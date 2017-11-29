<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ConstructionWork;

class HomeController extends AppController
{
    private $viewsPath = 'panel.home.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex() {

        $works = ConstructionWork::orderBy('tbObraID', 'DESC')->where('RegistroInactivo', '=', 0)->take(5)->get();

        $viewData = [
            'page' => [
                'title' => 'Escritorio',
            ],

            'works' => [
                'all' => $works,
            ],
        ];

        return view($this->viewsPath.'index', $viewData);
    }
}
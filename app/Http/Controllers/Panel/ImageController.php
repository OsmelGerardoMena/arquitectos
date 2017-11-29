<?php

namespace App\Http\Controllers\Panel;

use DB;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;

use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends AppController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    /**
     * Get image of Storage
     *
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request,$image)
    {

        $path = storage_path().'/app/public/cto_images/'.$image;
        if (file_exists($path)) { 
            //return Response::download($path);
            return Response()->download($path);
        }else{
            abort(403, 'Unauthorized action.');
        }

    }
}
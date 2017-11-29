<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Illuminate\Support\Facades\Bus;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\BusinessWork;
use App\Models\Business;
use App\Models\ConstructionWork;
use App\Models\PersonWork;
use App\Models\PersonBusinessWork;
use App\Models\Person;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends AppController
{
    private $route;
    private $childRoute = "persons";
    private $viewsPath = 'panel.constructionwork.person.';
    private $paginate = 25;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->route = url('panel/constructionwork');
    }

    /**
     * Index
     * Página principal de la sección
     *
     * @param $request Request
     * @param $workId int id de la obra
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showIndex(Request $request, $workId, $id = null)
    {
        $work = ConstructionWork::find($workId);
        $persons = PersonBusinessWork::orderBy('tbDirPersonaEmpresaObraID', 'DESC')
            ->where('tbObraID_DirPersonaObra', '=', $workId)
            ->paginate($this->paginate);
        $person = $persons[0];

        if ($id != null)
            $person = PersonBusinessWork::find($id);

        //dd($person);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Personas",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $persons->links(),
                    'prev' => $persons->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $persons->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $persons->currentPage(),
                    'first' => $persons->firstItem(),
                    'last' => $persons->lastPage(),
                    'total' => $persons->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'directory',
                    'child' => 'persons'
                ],
            ],

            'persons' => [
                'all' => $persons,
                'one' => $person
            ],

            'works' => [
                'one' => $work,
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Show Search
     * Muestra los registros o el registro por busqueda
     *
     * @param $request Request
     * @param $workId int id de la obra
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showSearch(Request $request, $workId, $id = null)
    {
        $work = ConstructionWork::find($workId);
        $persons = null;
        $person = null;
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $search = '';
        $hasDelete = false;

        if (!$request->has('filter')) {

            if (!$request->has('q') || empty($request->q)) {
                return redirect($redirect)
                    ->withErrors(['Se debe ingresar un dato para la busqueda'])
                    ->withInput();
            }
        }

        if ($request->has('q') && !empty($request->q)) {

            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%' . $request->q . '%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                'DirPersonaEmpresaObraCargo', 'LIKE', "{$search}"
            ];
        }

        // Verificamos si existe el filtro
        if ($request->has('status') && !empty($request->status)) {

            switch ($request->status) {
                case 'active':
                    $filters[] = [
                        'RegistroCerrado', '=', 0
                    ];
                    break;

                case 'closed':
                    $filters[] = [
                        'RegistroCerrado', '=', 1
                    ];
                    break;

                case 'deleted':
                    $hasDelete = true;
                    break;

                default:
                    break;
            }
        }

        $persons = PersonBusinessWork::orderBy('tbDirPersonaEmpresaObraID', 'DESC')
            ->where('tbObraID_DirPersonaObra', '=', $workId)
            ->whereHas('personsBusiness', function ($query) use ($search) {
                $query->whereHas('person', function($query) use($search) {
                    $query->where('PersonaNombreDirecto', 'LIKE', $search);
                });
            })
            ->orWhere(function ($query) use ($workId, $filters) {
                $query->where('tbObraID_DirPersonaObra', '=', $workId)
                    ->where($filters);
            })
            /*->where(function ($query) use ($workId, $search) {
                $query->where('tbObraID_DirPersonaObra', '=', $workId)
                    ->with([
                        'personsBusiness' => function($query) use ($search) {
                            $query->whereHas('person', function($query) use($search) {
                                $query->where('PersonaNombreDirecto', 'LIKE', $search);
                            });
                        },
                    ]);
            })*/
            ->paginate($this->paginate);

        if ($hasDelete) {

        }

        if ($persons->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $person = $persons[0];

        if ($id != null)
            $person = PersonBusinessWork::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Personas",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $persons->links(),
                    'prev' => $persons->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->previousPageUrl(),
                    'next' => $persons->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->nextPageUrl(),
                    'current' => $persons->currentPage(),
                    'first' => $persons->firstItem(),
                    'last' => $persons->lastPage(),
                    'total' => $persons->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'directory',
                    'child' => 'persons'
                ],
            ],

            'persons' => [
                'all' => $persons,
                'one' => $person
            ],

            'works' => [
                'one' => $work,
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $persons->count(),
                'query' => $request->q,
            ]
        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Show Save
     * Muestra la vista para guardar un registro
     *
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function showSave(Request $request, $workId) {

        $work = ConstructionWork::find($workId);
        $persons = PersonBusinessWork::orderBy('tbDirPersonaEmpresaObraID', 'DESC')
            ->where('tbObraID_DirPersonaObra', '=', $workId)
            ->paginate($this->paginate);
        $business = BusinessWork::orderBy('tbDirEmpresaObraID', 'DESC')->where('tbObraID_DirEmpresaObra', '=', $workId)->get();

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Personas",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/save',
                'pagination' => [
                    'links' => $persons->links(),
                    'prev' => $persons->setPath("{$this->route}/{$workId}/{$this->childRoute}/save")->previousPageUrl(),
                    'next' => $persons->setPath("{$this->route}/{$workId}/{$this->childRoute}/save")->nextPageUrl(),
                    'current' => $persons->currentPage(),
                    'first' => $persons->firstItem(),
                    'last' => $persons->lastPage(),
                    'total' => $persons->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'directory',
                    'child' => 'persons'
                ],
            ],

            'persons' => [
                'all' => $persons
            ],

            'business' => [
                'all' => $business
            ],

            'works' => [
                'one' => $work,
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];

        return view($this->viewsPath.'save', $viewData);
    }

    /**
     * Show Update
     * Muestra la vista para actualizar un registro
     *
     * @param $workId int Id de la obra
     * @param $id int Id del registro a actualizar
     * @return \Illuminate\Http\Response
     */
    public function showUpdate(Request $request, $workId, $id = null)
    {
        $work = ConstructionWork::find($workId);
        $persons = PersonBusinessWork::orderBy('tbDirPersonaEmpresaObraID', 'DESC')
            ->where('tbObraID_DirPersonaObra', '=', $workId)
            ->paginate($this->paginate);
        $person = $persons[0];

        if ($id != null)
            $person = PersonBusinessWork::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Personas",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $persons->links(),
                    'prev' => $persons->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $persons->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $persons->currentPage(),
                    'first' => $persons->firstItem(),
                    'last' => $persons->lastPage(),
                    'total' => $persons->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'directory',
                    'child' => 'persons'
                ],
            ],

            'persons' => [
                'all' => $persons,
                'one' => $person
            ],

            'works' => [
                'one' => $work,
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],
        ];

        return view($this->viewsPath.'update', $viewData);
    }

    /**
     * Do Save
     * Realiza la acción para guardar un registro
     *
     * @return \Illuminate\Http\Response
     */
    public function doSave(Request $request) {

        $workId = $request->input('cto34_work');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $redirectError = "{$this->route}/{$workId}/{$this->childRoute}/save";

        $rules = [
            'cto34_person' => 'required',
            'cto34_job' => 'required',
            'cto34_work' => 'required',
        ];

        $messages = [
            'cto34_person.required' => 'Persona requerida.',
            'cto34_job.required' => 'Cargo en la obra requerida.',
            'cto34_work.required' => 'Id de obra requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }


        $person = new PersonBusinessWork();
        $person->setConnection('mysql-writer');

        $person->tbDirPersonaEmpresaID_DirPersonaEmpresaObra = $request->input('cto34_person');
        $person->tbDirEmpresaID_DirPersonaEmpresaObra = $request->input('cto34_business');
        $person->DirPersonaEmpresaObraCargo = $request->input('cto34_job');
        $person->tbObraID_DirPersonaObra = $workId;

        if (!$person->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede guardar la persona, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Persona agregada correctamente.');
    }

    /**
     * Do Update
     * Realiza la acción de actualizar un registro
     *
     * @return \Illuminate\Http\Response
     */
    public function doUpdate(Request $request) {

        $id = $request->input('cto34_id');
        $workId = $request->input('cto34_work');
        $hasSearch = $request->input('_hasSearch');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info/{$id}{$request->input('_query')}";
        $redirectError = URL::previous();

        if ($hasSearch)
            $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/search/{$id}{$request->input('_query')}";

        $rules = [
            'cto34_person' => 'required',
            'cto34_business' => 'required',
            'cto34_job' => 'required',
            'cto34_work' => 'required',
        ];

        $messages = [
            'cto34_person.required' => 'Persona requerida.',
            'cto34_business.required' => 'Empresa requerida.',
            'cto34_job.required' => 'Cargo en la obra requerida.',
            'cto34_work.required' => 'Id de obra requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $person = PersonBusinessWork::find($id)->setConnection('mysql-writer');

        $image = '';

        // Save imagen
        $file = $request->file('cto34_img');

        if (!empty($file)) {
            $altoancho = GetImageSize($file);  
            if ($altoancho[0]>1200 or $altoancho[1]>1200){
                return redirect($redirectError)
                ->withErrors(['El tamaño maximo de la imagen es 1200 x 1200 pixeles'])
                ->withInput();    
            }else{
                //$nombre = $file->getClientOriginalName();
                $nombre = md5($file . microtime());
                $extension = $file->getClientOriginalExtension();
                $image = $nombre.'.'.$extension;
                \Storage::disk('cto')->put($image,  \File::get($file));
                \Storage::disk('cto')->delete($request->input('cto34_imgOld'));
                /*$disk = storage_path('app/public/cto_images');
                $resize = new \App\Libraries\ResizeImage($file);
                $resize->resizeTo(1200, 1200);
                $resize->saveImage($disk.'/'.$image);*/                
            }
        }
        $person->PersonImagen = $image;
        $person->tbDirPersonaEmpresaID_DirPersonaEmpresaObra = $request->input('cto34_person');
        $person->tbDirEmpresaID_DirPersonaEmpresaObra = $request->input('cto34_business');
        $person->DirPersonaEmpresaObraCargo = $request->input('cto34_job');

        if (!$person->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede actualizar la persona, intenten nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Persona actualizada correctamente.');

    }

    /**
     * Do Delete
     * Realiza la acción de eliminar un registro
     *
     * @param $request Request
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function doDelete(Request $request, $workId) {

        $id = $request->input('cto34_id');
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";

        $rules = [
            'cto34_id' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de edificio requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $person = PersonBusinessWork::find($id)->setConnection('mysql-writer');

        if (!$person->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el edificio, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Edificio <b>'.$person->UbicaEdificioAlias.'</b> eliminado correctamente.');


    }

    /**
     * Do Restore
     * Realiza la acción de restaurar un registro eliminado
     *
     * @param $request Request
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function doRestore(Request $request, $workId) {

        $id = $request->input('cto34_id');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $redirectError = URL::previous();

        $rules = [
            'cto34_id' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de edificio requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $person = PersonBusinessWork::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$person->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el edificio, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Edificio restaurado correctamente.');
    }
}
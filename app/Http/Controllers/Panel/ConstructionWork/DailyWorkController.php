<?php

namespace App\Http\Controllers\Panel\ConstructionWork;

use DB;
use Illuminate\Support\Facades\Bus;
use Validator;
use URL;

use App\Http\Controllers\AppController;
use App\Http\Requests;
use App\Models\Business;
use App\Models\ConstructionWork;
use App\Models\Contract;
use App\Models\Currency;
use App\Models\Group;
use App\Models\Level;
use App\Models\Building;
use App\Models\Local;
use App\Models\DailyWork;
use App\Models\Person;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyWorkController extends AppController
{
    private $route;
    private $childRoute = "dailys_work";
    private $viewsPath = 'panel.constructionwork.dailywork.';
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
        $dailywork = DailyWork::orderBy('DiarioFolio', 'DESC')->where('tbObraID_Diario', '=', $workId)->paginate($this->paginate);
        $dailyworkOne = $dailywork[0];

        if ($id != null)
            $dailyworkOne = DailyWork::find($id);

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Diario",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/info',
                'pagination' => [
                    'links' => $dailywork->links(),
                    'prev' => $dailywork->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->previousPageUrl(),
                    'next' => $dailywork->setPath("{$this->route}/{$workId}/{$this->childRoute}/info")->nextPageUrl(),
                    'current' => $dailywork->currentPage(),
                    'first' => $dailywork->firstItem(),
                    'last' => $dailywork->lastPage(),
                    'total' => $dailywork->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'coordinations',
                    'child' => 'daily'
                ],
            ],

            'dailywork' => [
                'all' => $dailywork,
                'one' => $dailyworkOne
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
     * Search
     * Muestra los registros o el registro por busqueda
     *
     * @param $request Request
     * @param $workId int id de la obra
     * @param $id int id del registro seleccionado
     * @return \Illuminate\Http\Response
     */
    public function showSearch(Request $request,$workId, $id = null)
    {
        $work = ConstructionWork::find($workId);
        $dailysWork = null;
        $dailyWork = null;
        $redirect = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $queries = (array) $request->query();
        $filters = (array) [];
        $hasDelete = false;
        $orderBy = 'DESC';

        if (!$request->has('filter')) {
            if (!$request->has('q') || empty($request->q)) {
                return redirect($redirect)
                    ->withErrors(['Se debe ingresar un dato para la busqueda'])
                    ->withInput();
            }
        }

        if ($request->has('q') && !empty($request->q)) {
            // Verificamos si el parametro contiene comodines
            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            // Creamos el primer filtro de busqueda concatenando campos
            $filters[] = [
                DB::raw("CONCAT_WS(' ', DiarioFolio, DiarioAsunto, DiarioImagenPieFoto, DiarioDescripcion)"), 'LIKE', "{$search}"
            ];
        }

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

        if ($request->has('type') && !empty($request->type)) {

            $filters[] = [
                'DiarioTipo', '=',  $request->type
            ];
        }

        if ($request->has('orderBy') && !empty($request->orderBy)) {

            $orderBy = DB::raw($request->orderBy);
        }

        $dailysWork = DailyWork::orderBy('DiarioFolio', $orderBy)
                                ->where('tbObraID_Diario', '=', $workId)
                                ->where($filters)
                                ->paginate($this->paginate);

        if ($hasDelete) {

            $dailysWork = DailyWork::onlyTrashed()
                ->orderBy('DiarioFolio', 'DESC')
                ->where('tbObraID_Diario', '=', $workId)
                ->where($filters)
                ->paginate($this->paginate);
        }

        if ($dailysWork->count() == 0) {
            return redirect($redirect)
                ->with('info', 'No hay resultados de tu busqueda: <b>'.$request->q.'</b>');
        }

        $dailyWork = $dailysWork[0];
        $autor = Person::find($dailysWork[0]->tbCTOUsuarioID_DiarioCapturo);
        $created_by = Person::find($dailysWork[0]->RegistroUsuario);

        if ($id != null) {

            $dailyWork = DailyWork::find($id);
            if ($hasDelete)
                $dailyWork = DailyWork::withTrashed()->find($id);

            $autor = Person::find($dailyWork->tbCTOUsuarioID_DiarioCapturo);
            $created_by = Person::find($dailyWork->RegistroUsuario);
        }


        
        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Diario",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$work->tbObraID}/{$this->childRoute}",
                'section' => '/search',
                'pagination' => [
                    'links' => $dailysWork->links(),
                    'prev' => $dailysWork->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->appends($queries)->previousPageUrl(),
                    'next' => $dailysWork->setPath("{$this->route}/{$workId}/{$this->childRoute}/search")->appends($queries)->nextPageUrl(),
                    'current' => $dailysWork->currentPage(),
                    'first' => $dailysWork->firstItem(),
                    'last' => $dailysWork->lastPage(),
                    'total' => $dailysWork->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'coordinations',
                    'child' => 'daily'
                ],
            ],

            'dailywork' => [
                'all' => $dailysWork,
                'one' => $dailyWork,
                'autor' => $autor,
                'created_by' => $created_by,
            ],

            'works' => [
                'one' => $work,
            ],

            'filter' => [
                'queries' => array_except($queries, ['page']),
                'query' => $request->getQueryString(),
            ],

            'search' => [
                'count' => $dailysWork->count(),
                'query' => $request->q,
            ]

        ];

        return view($this->viewsPath.'index', $viewData);
    }

    /**
     * Show Save
     * Muestra la vista para guardar un registro
     *
     * @param $request Request
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function showSave(Request $request, $workId)
    {
        $work = ConstructionWork::find($workId);        
        $dailyworkAll = DailyWork::orderBy('DiarioFolio', 'DESC')->where('tbObraID_Diario', '=', $workId)->paginate($this->paginate);
        $dailyworkFolio = DailyWork::withTrashed()
            ->orderBy('DiarioFolio', 'DESC')
            ->where(['tbObraID_Diario'=>$workId])
            ->get();

        $options = type_daily_options();
        $businessCategories = Business::categories();

        $viewData = [
            'page' => [
                'title' => "Obra / {$work->ObraClave} / Diario",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => '/save',
                'pagination' => [
                    'links' => $dailyworkAll->links(),
                    'prev' => $dailyworkAll->previousPageUrl(),
                    'next' => $dailyworkAll->nextPageUrl(),
                    'current' => $dailyworkAll->currentPage(),
                    'first' => $dailyworkAll->firstItem(),
                    'last' => $dailyworkAll->lastPage(),
                    'total' => $dailyworkAll->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'current' => [
                    'father' => 'coordinations',
                    'child' => 'daily'
                ],
            ],

            'dailywork' => [
                'all' => $dailyworkAll,
            ],

            'business' => [
                'categories' => $businessCategories,
            ],

            'count'=> (count($dailyworkFolio) > 0) ? $dailyworkFolio[0]->DiarioFolio + 1 : 1,

            'options' => $options,

            'works' => [
                'one' => $work,
            ],
        ];
        //echo "<script>alert('$work')</script>";
        // $name = User::find( Auth::id() )
        //             ->with('person')
        //             ->first();
        // $other = Auth::user()['person']->PersonaNombres;
        // dd($other);

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
        $route = "{$this->route}";
        $navigationFrom = "info";
        $search = '';
        $hasSearch = false;
        $dailyworkAll = null;
        $queryString = ($request->has('page')) ? "?page={$request->page}" : '';
        $work = ConstructionWork::find($workId);
        $options = type_daily_options();
        $redirect = URL::previous();
        $filters = [];
        $queries = $request->query();
        $section = '/info';
        $orderBy = 'DESC';

        // Url has param 'q' from search
        if ($request->has('q')) {

            $search = (strpos($request->q, '*') !== FALSE) ? str_replace('*', '%', $request->q) : '%'.$request->q.'%';

            $filters[] = [
                DB::raw("CONCAT_WS(' ', DiarioFolio, DiarioAsunto, DiarioImagenPieFoto, DiarioDescripcion)"), 'LIKE', "{$search}"
            ];

            $section = '/search';
        }

        if ($request->has('type') && !empty($request->type)) {

            $filters[] = [
                'DiarioTipo', '=',  $request->type
            ];

            $section = '/search';
        }

        if ($request->has('orderBy') && !empty($request->orderBy)) {

            $orderBy = DB::raw($request->orderBy);
            $section = '/search';
        }

        $dailyworkAll = DailyWork::orderBy('DiarioFolio', $orderBy)
            ->where('tbObraID_Diario', '=', $workId)
            ->where($filters)
            ->paginate($this->paginate);
        $dailywork = DailyWork::find($id);

        if (!$dailywork) {
            return redirect($redirect)
                ->withErrors(['Lo sentimos no puedes editar este registro esta en status eliminado.'])
                ->withInput();
        } 

        $autor = Person::find($dailywork->tbDirPersonaID_DiarioAutor);
        $created_by = Person::find($dailywork->tbCTOUsuarioID_DiarioCapturo);

        $viewData = [
            'page' => [
                'title' =>  "Obra / {$work->ObraClave} / Diario",
            ],

            'navigation' => [
                'base' => "{$this->route}/{$workId}/{$this->childRoute}",
                'section' => $section,
                'from' => $navigationFrom,
                'pagination' => [
                    'links' => $dailyworkAll->links(),
                    'prev' => $dailyworkAll->setPath("{$this->route}/{$workId}/{$this->childRoute}{$section}")->appends($queries)->previousPageUrl(),
                    'next' => $dailyworkAll->setPath("{$this->route}/{$workId}/{$this->childRoute}{$section}")->appends($queries)->nextPageUrl(),
                    'current' => $dailyworkAll->currentPage(),
                    'first' => $dailyworkAll->firstItem(),
                    'last' => $dailyworkAll->lastPage(),
                    'total' => $dailyworkAll->count()
                ],
                'page' => ($request->has('page')) ? $request->page : 0,
                'search' => $hasSearch,
                'query_string' => $queryString,
                'current' => [
                    'father' => 'coordinations',
                    'child' => 'daily'
                ],
            ],

            'search' => [
                'query' => $request->q,
            ],

            'filter' => [
                'query' => $request->getQueryString(),
            ],

            'options' => $options,
            'dailywork' => [
                'all' => $dailyworkAll,
                'one' => $dailywork,
                'autor' => $autor,
                'created_by' => $created_by,
            ],
            'works' => [
                'one' => $work,
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

        //dd($request->input('cto34_date_daily_format'));

        $workId = $request->input('cto34_work');
        $redirectSuccess = "{$this->route}/{$workId}/{$this->childRoute}/info";
        $redirectError = "{$this->route}/{$workId}/{$this->childRoute}/save";


        $rules = [
            'cto34_type_daily' => 'required',
            'cto34_date_daily' => 'required',
            'cto34_author' => 'required',
            'cto34_subject' => 'required',
        ];

        $messages = [
            'cto34_type_daily.required' => 'Tipo requerido.',
            'cto34_date_daily.required' => 'Fecha de diario requerido.',
            'cto34_author.required' => 'Autor requerido.',
            'cto34_subject.required' => 'Asunto requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

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
               /*$disk = storage_path('app/public/cto_images');
               $resize = new \App\Libraries\ResizeImage($file);
               $resize->resizeTo(1200, 1200);
               $resize->saveImage($disk.'/'.$image);*/
            }
       }

        $workId = $request->input('cto34_work');
        $dailyworkCount = DailyWork::where(['tbObraID_Diario'=>$workId])->get()->count();

        $dailywork = new DailyWork();
        $dailywork->setConnection('mysql-writer');

        $now = new \DateTime();

        $dailywork->DiarioFolio = $request->input('cto34_folio');
        $dailywork->tbObraID_Diario = $request->input('cto34_work');
        //$dailywork->tbMiEmpresaID_Diario = $request->input('');
        $dailywork->DiarioFecha = $request->input('cto34_date_daily_format');
        $dailywork->DiarioTipo = $request->input('cto34_type_daily');
        $dailywork->DiarioAsunto = $request->input('cto34_subject');
        $dailywork->DiarioDescripcion = $request->input('cto34_description');
        $dailywork->DiarioImagen = $image;
        $dailywork->DiarioImagenPieFoto = $request->input('cto34_bottom_copy');
        $dailywork->tbDirPersonaID_DiarioAutor = $request->input('cto34_author');
        $dailywork->tbCTOUsuarioID_DiarioCapturo = Auth::id();
        $dailywork->DiarioCapturoTimestamp = $now->format('Y-m-d H:i:s');
        
        // ROL when close form
        if( $request->input('cto34_close') == 1  ){
        $dailywork->RegistroCerrado = 1;
        $dailywork->RegistroRol = Auth::user()['role'];
        }

        if (!$dailywork->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede guardar el diario, intente nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Diario agregado correctamente.');
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
            'cto34_type_daily' => 'required',
            'cto34_date_daily_format' => 'required',
            'cto34_author' => 'required',
            'cto34_subject' => 'required',
        ];

        $messages = [
            'cto34_type_daily.required' => 'Tipo requerido.',
            'cto34_date_daily_format.required' => 'Fecha de diario requerido.',
            'cto34_author.required' => 'Autor requerido.',
            'cto34_subject.required' => 'Asunto requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $dailywork = DailyWork::find($id)->setConnection('mysql-writer');
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
                $dailywork->DiarioImagen = $image;
            }
        }

        $dailywork->DiarioFecha = $request->input('cto34_date_daily_format');
        $dailywork->DiarioTipo = $request->input('cto34_type_daily');
        $dailywork->DiarioAsunto = $request->input('cto34_subject');
        $dailywork->DiarioDescripcion = $request->input('cto34_description');
        $dailywork->DiarioImagenPieFoto = $request->input('cto34_bottom_copy');
        $dailywork->tbDirPersonaID_DiarioAutor = $request->input('cto34_author');

        // ROL when close form
        if( $request->input('cto34_close') == 1  ){
            $dailywork->RegistroCerrado = 1;
            $dailywork->RegistroRol = auth_permissions_id(Auth::user()['role']);
        }

        if (!$dailywork->save()) {
            return redirect($redirectError)
                ->withErrors(['No se puede actualizar el diario de obra, intente nuevamente'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Diario actualizado correctamente.');

    }

    /**
     * Do Delete
     * Realiza la acción de eliminar un registro
     *
     * @param $request Request
     * @param $workId int Id de la obra
     * @return \Illuminate\Http\Response
     */
    public function doDelete(Request $request) {

        $id = $request->input('cto34_id');
        $redirect = "{$this->route}/{$request->input('cto34_work')}/{$this->childRoute}/info";

        $rules = [
            'cto34_id' => 'required',
        ];

        $messages = [
            'cto34_id.required' => 'Id de diario de obra requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }

        $dailywork = DailyWork::find($id)->setConnection('mysql-writer');

        if (!$dailywork->delete()) {
            return redirect($redirect)
                ->withErrors(['No se puede eliminar el Diario de obra, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirect)->with('success', 'Diario de obra con asunto: <b>'.$dailywork->DiarioAsunto.'</b> eliminada correctamente.');

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
            'cto34_id.required' => 'Id de diario requerido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect($redirectError)
                ->withErrors($validator)
                ->withInput();
        }

        $dailywork = DailyWork::withTrashed()->find($id)->setConnection('mysql-writer');

        if (!$dailywork->restore()) {
            return redirect($redirectError)
                ->withErrors(['No se puede restaurar el diario, intente nuevamente.'])
                ->withInput();
        }

        return redirect($redirectSuccess)->with('success', 'Diario restaurado correctamente.');


    }
}